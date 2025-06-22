<?php

namespace App\Controller;

use App\Enum\CategoryManga;
use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BookType;
use App\Form\CommentType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/books', name: 'books_index')]
    public function index(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'name_asc');
        $categoryRaw = $request->query->get('category');

        // On récupère editor en majuscule ou null
        $editor = strtoupper($request->query->get('editor', ''));
        if ($editor === '') {
            $editor = null;
        }

        // Convertir la chaîne en enum ou null si pas trouvé
        $category = null;
        if ($categoryRaw !== null) {
            foreach (CategoryManga::cases() as $case) {
                if ($case->value === $categoryRaw) {
                    $category = $case;
                    break;
                }
            }
        }

        $query = $bookRepository->findByFilters($sort, $categoryRaw, $editor);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('book/index.html.twig', [
            'pagination' => $pagination,
            'current_sort' => $sort,
            'current_category' => $category,
            'current_editor' => $editor,
        ]);
    }

    #[Route('/admin/books/new', name: 'books_new', methods:['GET','POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $book = new Book();
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.webp';

                try {
                    $uploadedFilePath = $this->convertImageToWebpAndSave($pictureFile, $newFilename);
                    $book->setPicture($newFilename);
                } catch (\Exception $e) {
                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse(['success' => false, 'message' => 'Erreur image : ' . $e->getMessage()], 400);
                    }
                    $this->addFlash('error', 'Erreur image : ' . $e->getMessage());
                    return $this->redirectToRoute('books_new');
                }
            }

            try {
                $entityManager->persist($book);
                $entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'success' => true,
                        'message' => 'Livre ajouté avec succès.',
                        'redirectUrl' => $this->generateUrl('books_index')  // URL pour la redirection côté client
                    ]);
                }

                $this->addFlash('success', 'Livre ajouté avec succès.');
                return $this->redirectToRoute('books_index');
            } catch (\Exception $e) {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(['success' => false, 'message' => 'Erreur enregistrement livre : ' . $e->getMessage()], 500);
                }
                return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                    'message' => 'Erreur enregistrement livre : ' . $e->getMessage()
                ]);
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $field = $error->getOrigin()->getName();
                    $errors[$field][] = $error->getMessage();
                }
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Le formulaire contient des erreurs.',
                    'errors' => $errors,
                ], 400);
            }
            $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez les corriger.');
        }
    }

    return $this->render('book/new.html.twig', [
        'form' => $form->createView(),
    ]);
}




    private function convertImageToWebpAndSave(UploadedFile $file, string $newFilename): string
    {
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \RuntimeException("Impossible de lire l'image.");
        }

        switch ($imageInfo[2]) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file->getPathname());
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file->getPathname());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file->getPathname());
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            default:
                throw new \RuntimeException('Format d\'image non pris en charge.');
        }

        if (!$image) {
            throw new \RuntimeException("Impossible de charger l'image.");
        }

        $destinationPath = $this->getParameter('pictures_directory') . '/' . $newFilename;
        imagewebp($image, $destinationPath, 80);
        imagedestroy($image);

        return $destinationPath;
    }

    #[Route('/books/{id}', name: 'books_show', methods: ['GET', 'POST'])]
    public function books_show(int $id, BookRepository $bookRepository, EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        try {
            $book = $bookRepository->find($id);
            if (!$book) {
                throw $this->createNotFoundException('Livre non trouvé');
            }

            $book->setViews($book->getViews() + 1);
            $em->persist($book);
            $em->flush();

            $query = $em->getRepository(Comment::class)
                ->createQueryBuilder('c')
                ->where('c.book = :book')
                ->setParameter('book', $book)
                ->orderBy('c.date', 'DESC')
                ->getQuery();

            $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            $collectionName = $book->getCollectionName();
            $relatedBooks = $bookRepository->findByCollectionNameExcludingId($collectionName, $book->getId());

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setBook($book);
                $comment->setUser($this->getUser());
                $comment->setDate(new \DateTimeImmutable());

                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('books_show', ['id' => $id]);
            }

            return $this->render('book/show.html.twig', [
                'book' => $book,
                'form' => $form->createView(),
                'pagination' => $pagination,
                'relatedBooks' => $relatedBooks,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur d\'affichage du livre : ' . $e->getMessage()
            ]);
        }
    }
}


