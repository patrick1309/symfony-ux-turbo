<?php

namespace App\Controller;

use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function chat(
        Request $request,
        HubInterface $hub
    ): Response
    {
        $form = $this->createFormBuilder()
            ->add('message', TextType::class, ['attr' => ['autocomplete' => 'off']])
            ->add('submit', SubmitType::class)
            ->getForm();

        $emptyForm = clone $form;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 🔥 The magic happens here! 🔥
            // The HTML update is pushed to the client using Mercure
            $hub->publish(new Update(
                'chat',
                $this->renderView('chat/message.stream.html.twig', ['message' => $data['message']])
            ));

            // Force an empty form to be rendered below
            // It will replace the content of the Turbo Frame after a post
            $form = $emptyForm;
        }

        

        return $this->render('chat/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
