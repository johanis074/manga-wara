<?php

namespace App\Controller;

use Editor;
use CategoryManga;
use App\Entity\Book;
use App\Form\BookType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BookController extends AbstractController
    {
        #[Route('/books', name: 'books_index')]
    public function index(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'name_asc'); // Tri par défaut : Nom A-Z
        $category = $request->query->get('category', null); // Filtrage par catégorie
        $editor = $request->query->get('editor', null); // Filtrage par éditeur

        $query = $bookRepository->findByFilters($sort, $category, $editor); // Applique les tris et filtres

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
            'categories' => CategoryManga::cases(), // Récupère les catégories dynamiquement
            'editors' => Editor::cases() // Récupère les éditeurs dynamiquement
        ]);
    }




    #[Route('/new', name: 'books_new', methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();
    
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.webp';
    
                try {
                    $uploadedFilePath = $this->convertImageToWebpAndSave($pictureFile, $newFilename);
                    $book->setPicture($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Échec du téléchargement ou de la conversion de l\'image.');
                    return $this->redirectToRoute('books_new');
                }
            }
    
            $entityManager->persist($book);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_books');
        }
    
        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function convertImageToWebpAndSave(UploadedFile $file, string $newFilename): string
    {
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \RuntimeException('Impossible de lire l\'image.');
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
            throw new \RuntimeException('Impossible de charger l\'image.');
        }
    
        $destinationPath = $this->getParameter('pictures_directory') . '/' . $newFilename;
        imagewebp($image, $destinationPath, 80);
        imagedestroy($image);
    
        return $destinationPath;
    }

    #[Route('/books/{id}', name: 'books_show', methods: ['GET', 'POST'])]
    public function books_show(int $id, BookRepository $bookRepository, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }
    
        $query = $entityManager->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->where('c.book = :book')
            ->setParameter('book', $book)
            ->orderBy('c.date', 'DESC')
            ->getQuery();
    
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
    
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBook($book);
            $comment->setUser($this->getUser());
            $comment->setDate(new \DateTimeImmutable());
    
            $entityManager->persist($comment);
            $entityManager->flush();
    
            return $this->redirectToRoute('books_show', ['id' => $id]);
        }
    
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
