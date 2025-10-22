<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AuthorType;

#[Route('/author')]
class AuthorController extends AbstractController
{/*
    private $authors = [
        ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100],
        ['id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ],
        ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/{name}', name: 'app_author_show')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/authors', name: 'app_author_list')]
    public function listAuthors(): Response
    {
        $totalBooks = 0;
        foreach ($this->authors as $author) {
            $totalBooks += $author['nb_books'];
        }

        return $this->render('author/list.html.twig', [
            'authors' => $this->authors,
            'totalBooks' => $totalBooks,
        ]);
    }

    #[Route('/author/details/{id}', name: 'app_author_details')]
    public function authorDetails($id): Response
    {
        $author = null;
        foreach ($this->authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }
        
        

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }*/
        #[Route('/list', name: 'author_list')]
public function list(AuthorRepository $authorRepository): Response
{
    $authors = $authorRepository->findAll();

    return $this->render('author/list.html.twig', [
        'authors' => $authors,
    ]);
}
#[Route('/addStatic', name: 'author_add_static')]
public function addStatic(EntityManagerInterface $em): Response
{
    $author = new Author();
    $author->setAuthorName("seji bouallegue");
    $author->setEmail("example@gmail.com");
    $author->setNbBooks(0);

    $em->persist($author);

    $em->flush();

    return $this->redirectToRoute('author_list');
}
#[Route('/insert', name: 'author_insert')]
    public function insert(EntityManagerInterface $em): Response
    {
        $author = new Author;
        $author->setAuthorName("aboul elkassem");
        $author->setEmail("kassem@gmail.com");

        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('app_authList');
    }
#[Route('/add', name: 'author_add')]
public function add(Request $request, EntityManagerInterface $em): Response
{
    $author = new Author();
    $author->setNbBooks(0); 

    $form = $this->createForm(AuthorType::class, $author);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('author_list');
    }

    return $this->render('author/form.html.twig', [
        'addAuth' => $form->createView(), 
    ]);
}
#[Route('/update/{id}', name: 'author_update')]
public function update(Request $request, EntityManagerInterface $em, AuthorRepository $repo, $id): Response
{
    $author = $repo->find($id);

    $form = $this->createForm(AuthorType::class, $author);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('author_list');
    }
    return $this->render('author/form.html.twig', [
        'addAuth' => $form->createView(),
    ]);
}
#[Route('/delete/{id}', name: 'author_delete')]
public function delete(EntityManagerInterface $em, AuthorRepository $repo, $id): Response
{
    $author = $repo->find($id);

    $em->remove($author);

    $em->flush();

    return $this->redirectToRoute('author_list');
}
}