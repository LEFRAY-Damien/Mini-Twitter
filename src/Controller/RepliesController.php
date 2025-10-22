<?php

namespace App\Controller;

use App\Entity\Replies;
use App\Entity\Tweet;
use App\Form\RepliesType;
use App\Repository\RepliesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/replies')]
final class RepliesController extends AbstractController
{
    #[Route(name: 'app_replies_index', methods: ['GET'])]
    public function index(RepliesRepository $repliesRepository): Response
    {
        return $this->render('replies/index.html.twig', [
            'replies' => $repliesRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_replies_new', methods: ['GET', 'POST'])]
    public function new(Tweet $tweet,Request $request, EntityManagerInterface $entityManager): Response
    {
        $reply = new Replies();
        $reply->setUser($this->getUser());
        $reply->setTweet($tweet);
        $form = $this->createForm(RepliesType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reply);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('replies/new.html.twig', [
            'reply' => $reply,
            'form' => $form,
            'tweet' => $tweet,
        ]);
    }

    #[Route('/{id}', name: 'app_replies_show', methods: ['GET'])]
    public function show(Replies $reply): Response
    {
        return $this->render('replies/show.html.twig', [
            'reply' => $reply,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_replies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Replies $reply, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepliesType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_replies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('replies/edit.html.twig', [
            'reply' => $reply,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_replies_delete', methods: ['POST'])]
    public function delete(Request $request, Replies $reply, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reply->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reply);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_replies_index', [], Response::HTTP_SEE_OTHER);
    }
}
