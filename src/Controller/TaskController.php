<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(TaskType::class, new Task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            // 🔥 The magic happens here! 🔥
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->render('task/success.stream.html.twig', ['task' => $task]);
            }

            // If the client doesn't support JavaScript, or isn't using Turbo, the form still works as usual.
            // Symfony UX Turbo is all about progressively enhancing your apps!
            return $this->redirectToRoute('task_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
