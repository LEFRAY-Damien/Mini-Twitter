<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tweet')]
final class TweetController extends AbstractController
{
    #[Route(name: 'app_tweet_index', methods: ['GET'])]
    public function index(TweetRepository $tweetRepository): Response
    {
        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tweet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/new.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'])]
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tweet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/edit.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tweet_delete', methods: ['POST'])]
    public function delete(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tweet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }
}
