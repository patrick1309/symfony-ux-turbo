<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/books', name: 'books')]
    public function index(
        BookRepository $bookRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(BookType::class, new Book);
        $emptyForm = clone $form;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $em->persist($book);
            $em->flush();

            $form = $emptyForm;
        }

        return $this->render('book/index.html.twig', [
            'form' => $form->createView(),
            'books' => $bookRepository->findAll()
        ]);
    }

    #[Route('/book/{id}', name: 'book')]
    public function show(Request $request, EntityManagerInterface $em, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }
}
