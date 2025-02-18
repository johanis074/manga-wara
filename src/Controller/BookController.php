<?php

namespace App\Controller;

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
    #[Route('/books', name: 'app_books', methods: ['GET'])]
public function index(BookRepository $repository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
{
    // Créez une requête pour récupérer les livres
    $query = $entityManager->getRepository(Book::class)->createQueryBuilder('b')
        ->getQuery();

    // Paginez la requête
    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Numéro de la page, 1 par défaut
        50 // Nombre d'éléments par page
    );

    // Rendez le template avec les livres paginés
    return $this->render('book/index.html.twig', [
        'pagination' => $pagination,
    ]);
}
        
    // return $this->render('book/index.html.twig', [
    //     'books' => $books,
    // ]);

    #[Route('/new', name: 'books_new', methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();
    
            if ($pictureFile) {
                // Récupérer le nom d'origine du fichier
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Assurer que le nom de fichier est sécurisé
                $safeFilename = $slugger->slug($originalFilename);
                // Générer un nouveau nom de fichier unique avec l'extension .webp
                $newFilename = $safeFilename . '-' . uniqid() . '.webp';
    
                try {
                    // Convertir l'image en WebP avant de la sauvegarder
                    $uploadedFilePath = $this->convertImageToWebpAndSave($pictureFile, $newFilename);
    
                    // Enregistrer le nom de fichier dans l'entité Book
                    $book->setPicture($newFilename);
    
                } catch (\Exception $e) {
                    // Gérer l'exception si quelque chose se passe mal pendant le téléchargement ou la conversion
                    $this->addFlash('error', 'Échec du téléchargement ou de la conversion de l\'image.');
                    return $this->redirectToRoute('books_new');
                }
            }
    
            // Persister et flusher l'entité dans la base de données
            $entityManager->persist($book);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_books');
        }
    
        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * Convertit une image en WebP et la sauvegarde dans le répertoire spécifié.
     *
     * @param UploadedFile $file Fichier téléchargé
     * @param string $newFilename Nom du fichier WebP à sauvegarder
     * @return string Chemin absolu du fichier sauvegardé
     */
    private function convertImageToWebpAndSave(UploadedFile $file, string $newFilename): string
    {
        // Charger l'image depuis le fichier téléchargé
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \RuntimeException('Impossible de lire l\'image.');
        }
    
        // Créer une ressource d'image selon le type de fichier
        switch ($imageInfo[2]) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file->getPathname());
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file->getPathname());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file->getPathname());
                // Conserver la transparence pour les images PNG
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
    
        // Définir le chemin de destination
        $destinationPath = $this->getParameter('pictures_directory') . '/' . $newFilename;
    
        // Sauvegarder l'image au format WebP avec une qualité de 80%
        imagewebp($image, $destinationPath, 80);
    
        // Libérer la mémoire utilisée par l'image
        imagedestroy($image);
    
        // Retourner le chemin absolu du fichier sauvegardé
        return $destinationPath;
    }

    #[Route('/books/{id}', name: 'books_show', methods: ['GET', 'POST'])]
public function books_show(
    int $id,
    BookRepository $bookRepository,
    EntityManagerInterface $entityManager,
    Request $request,
    PaginatorInterface $paginator
): Response {
    // Récupérer le livre par son ID
    $book = $bookRepository->find($id);
    if (!$book) {
        throw $this->createNotFoundException('Livre non trouvé');
    }

    // Pagination des commentaires associés au livre
    $query = $entityManager->getRepository(Comment::class)
        ->createQueryBuilder('c')
        ->where('c.book = :book')
        ->setParameter('book', $book)
        ->orderBy('c.date', 'DESC') // Optionnel : trier par date de création
        ->getQuery();

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Numéro de page par défaut
        10 // Nombre de commentaires par page
    );

    // Créer un nouveau commentaire
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Associer le livre au commentaire
        $comment->setBook($book);
        $comment->setUser($this->getUser()); // Optionnel : associer l'utilisateur connecté au commentaire

        // Optionnel : définir la date de création du commentaire
        $comment->setDate(new \DateTimeImmutable());

        // Persister et enregistrer le commentaire
        $entityManager->persist($comment);
        $entityManager->flush();

        // Rediriger vers la page du livre pour éviter la soumission multiple du formulaire
        return $this->redirectToRoute('books_show', ['id' => $id]);
    }

    // Rendre la vue avec les données nécessaires
    return $this->render('book/show.html.twig', [
        'book' => $book,
        'form' => $form->createView(),
        'pagination' => $pagination,
    ]);
}
}
?>
