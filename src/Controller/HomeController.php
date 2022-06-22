<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/another-page', name: 'another-page')]
    public function anotherPage(Request $request): Response
    {
        // Get the frame ID (will be null if the request hasn't been triggered by a Turbo Frame)
        $frameId = $request->headers->get('Turbo-Frame');

        return $this->render('home/another-page.html.twig', [
            'frameId' => $frameId,
        ]);
    }

    #[Route('/block', name: 'block')]
    public function block(Request $request): Response
    {
        // Get the frame ID (will be null if the request hasn't been triggered by a Turbo Frame)
        $frameId = $request->headers->get('Turbo-Frame');

        return $this->render('home/block.html.twig', [
            'frameId' => $frameId,
        ]);
    }
}
