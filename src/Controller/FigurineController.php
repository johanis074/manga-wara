<?php

namespace App\Controller;


use brand;
use App\Entity\Figurine;
use App\Form\FigurineType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\FigurineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FigurineController extends AbstractController
{
    #[Route('/figurines', name: 'figurines_index')]
    public function index(Request $request, FigurineRepository $figurineRepository, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'name_asc'); // Tri par défaut : Nom A-Z
        $brand = $request->query->get('brand', null); // Filtrage par marque

        $query = $figurineRepository->findByFilters($sort, $brand); // Applique les tris et filtres

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('figurine/index.html.twig', [
            'pagination' => $pagination,
            'current_sort' => $sort,
            'current_brand' => $brand,
            'brands' => brand::cases(), // Récupère les catégories dynamiquement
        ]);
    }

    #[Route('/figurines/new', name: 'figurines_new', methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $figurine = new Figurine();
        $form = $this->createForm(FigurineType::class, $figurine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.webp';

                try {
                    $uploadedFilePath = $this->convertImageToWebpAndSave($pictureFile, $newFilename);
                    $figurine->setPicture($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Échec du téléchargement ou de la conversion de l\'image.');
                    return $this->redirectToRoute('figurines_new');
                }
            }

            $entityManager->persist($figurine);
            $entityManager->flush();

            return $this->redirectToRoute('figurines_index');
        }

        return $this->render('figurine/new.html.twig', [
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

    #[Route('/figurines/{id}', name: 'figurines_show', methods: ['GET', 'POST'])]
    public function show(
        int $id,
        FigurineRepository $figurineRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $figurine = $figurineRepository->find($id);
        if (!$figurine) {
            throw $this->createNotFoundException('Figurine non trouvée');
        }

        // ✅ Incrémentation des vues
        $figurine->setViews($figurine->getViews() + 1);
        $entityManager->persist($figurine);
        $entityManager->flush();

        $query = $entityManager->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->where('c.figurine = :figurine')
            ->setParameter('figurine', $figurine)
            ->orderBy('c.date', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setFigurine($figurine);
            $comment->setUser($this->getUser());
            $comment->setDate(new \DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('figurines_show', ['id' => $id]);
        }

        return $this->render('figurine/show.html.twig', [
            'figurine' => $figurine,
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}


