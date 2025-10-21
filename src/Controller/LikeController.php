<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Tweet;
use App\Form\LikeType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/like')]
final class LikeController extends AbstractController
{
    #[Route(name: 'app_like_index', methods: ['GET'])]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->render('like/index.html.twig', [
            'likes' => $likeRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_like_new', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function new(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Like::class);

        $like = new Like();
        $like->setUser($this->getUser());
        $like->setTweet($tweet);
        if (!$repository->findOneBy(['user' => $like->getUser()->getId(), 'tweet' => $tweet->getId()])) {
            $entityManager->persist($like);
            $entityManager->flush();
        } else {
            $existingLike = $repository->findOneBy(['user' => $like->getUser()->getId(), 'tweet' => $tweet->getId()]);
            $entityManager->remove($existingLike);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_like_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Like $like): Response
    {
        return $this->render('like/show.html.twig', [
            'like' => $like,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_like_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LikeType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('like/edit.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_like_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$like->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($like);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
    }
}
