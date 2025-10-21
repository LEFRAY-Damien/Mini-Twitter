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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tweet')]
final class TweetController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(name: 'app_tweet_index', methods: ['GET'])]
    public function index(TweetRepository $tweetRepository): Response
    {   
        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweetRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_tweet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweet->setUser($this->getUser());
            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/new.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_tweet_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tweet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }

    //    #[Route('/listTweets/{id}', name: 'app_tweet_show')]
    //     public function showTweet(int $id, TweetRepository $tweetRepository  ) : Response
    //     {
    //         $tweetShow = $tweetRepository->find($id);
    //         if (!$tweet) {
    //             throw $this->createNotFoundException('Tweet Non Trouvé');
    //         }

    //         return $this->render('tweet/index.html.twig', [
    //             'tweet' => $tweet
    //         ])
    //     }
    
}
