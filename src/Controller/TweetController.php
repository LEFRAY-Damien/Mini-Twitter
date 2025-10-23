<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweet->setUser($this->getUser());

            $mediaFile = $form->get('media')->getData();

            if ($mediaFile) {
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();

                try {
                    $mediaFile->move(
                        $this->getParameter('media_directory'),
                        $newFilename
                    );
                    $tweet->setMedia($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload du media.');
                }
            }

            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/new.html.twig', [
            'tweet' => $tweet,
            'form' => $form->createView(),
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

    #[IsGranted('ROLE_USER')]
    #[Route('/tweet/{id}/delete', name: 'app_tweet_delete')]
    public function deleteTweet(Tweet $tweet, EntityManagerInterface $em): Response
    {

        // SUPPRESSION DU FICHIER IMAGE ASSOCIÉ AU TWEET
        $mediaFilename = $tweet->getMedia();
        if ($mediaFilename) {
            $mediaPath = $this->getParameter('media_directory') . '/' . $mediaFilename;
            if (file_exists($mediaPath)) {
                unlink($mediaPath); 
            }
        }

        foreach ($tweet->getReports() as $report) {
            $em->remove($report);
        }

        foreach ($tweet->getRetweets() as $retweet) {
            $em->remove($retweet);
        }

        foreach ($tweet->getLikes() as $like) {
            $em->remove($like);
        }
        $em->remove($tweet);
        $em->flush();

        $this->addFlash('success', 'Tweet et retweets supprimés !');

        return $this->redirectToRoute('app_tweet_index');
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
