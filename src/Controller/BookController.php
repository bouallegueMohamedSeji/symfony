<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    
    #[Route('/list', name: 'book_list')]
    public function list(BookRepository $bookRepository): Response
    {
        $publishedBooks = $bookRepository->findBy(['published' => true]);

        $countPublished = $bookRepository->count(['published' => true]);
        $countUnpublished = $bookRepository->count(['published' => false]);

        return $this->render('book/list.html.twig', [
            'books' => $publishedBooks,
            'publishedCount' => $countPublished,
            'unpublishedCount' => $countUnpublished,
        ]);
    }

    
    #[Route('/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book->setPublished(true); // Initialisé à True [cite: 36]

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique d'incrémentation de nb_books [cite: 36]
            $author = $book->getAuthor();
            if ($author) {
                $author->setNbBooks($author->getNbBooks() + 1);
                $em->persist($author); // Important : persister l'auteur mis à jour
            }

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   
    #[Route('/edit/{id}', name: 'book_edit')]
    public function edit(Request $request, EntityManagerInterface $em, BookRepository $repo, $id): Response
    {
        $book = $repo->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); 
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    
    #[Route('/delete/{id}', name: 'book_delete')]
    public function delete(EntityManagerInterface $em, BookRepository $repo, $id): Response
    {
        $book = $repo->find($id);

        if ($book) {
            // Logique de décrémentation de nb_books
            $author = $book->getAuthor();
            if ($author) {
                $author->setNbBooks($author->getNbBooks() - 1);
                $em->persist($author);
            }

            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute('book_list');
    }

    
    #[Route('/show/{id}', name: 'book_show')]
    public function show(BookRepository $repo, $id): Response
    {
        $book = $repo->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}