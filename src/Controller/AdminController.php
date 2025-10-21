<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Retweet;
use App\Entity\Tweet;
use App\Entity\User;
use App\Repository\ReportRepository;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route(name: 'app_admin')]
    public function index(UserRepository $userRepo, TweetRepository $tweetsRepo, ReportRepository $reportsRepo): Response
    {
        $users = $userRepo->count([]);
        $tweets = $tweetsRepo->count([]);
        $reports = $reportsRepo->count([]);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'tweets' => $tweets,
            'reports' => $reports
        ]);
    }

    #[Route('/listUsers', name: 'app_admin_list_user')]
    public function listUsers(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();
        return $this->render('admin/adminUser.html.twig', [
            'users' => $users,
        ]);
    }
    //supprimer report
    #[Route(('/report/{id}/delete'),name: 'app_admin_delete_report', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function deleteReport(Report $report, EntityManagerInterface $em): Response
    {
        $em->remove($report);
        $em->flush();

        $this->addFlash('success', 'Report Supprimé !');

        return $this->redirectToRoute('app_admin');
    }

    //supprimer tweet
    #[Route(('/tweet/{id}/delete'), name: 'app_admin_delete_tweet', requirements: ['id' => '\d+'])]
    public function deleteTweet(Tweet $tweet, EntityManagerInterface $em): Response
    {
        
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

    // Suspendre/Bannir un utilisateur (isBanned)
        #[Route('/user/{id}/ban', name: 'app_admin_ban_user', requirements: ['id' => '\d+'])]
        public function banUser(User $user, EntityManagerInterface $em) : Response
        {
            $user->setIsBanned(true);

        $em->flush();

        $this->addFlash('success', "{$user->getUsername()} a été banni !");

        return $this->redirectToRoute('app_admin');
    }

    // Désactivé un compte utilisateurs (isActive)
    #[Route('/user/{id}/disable', name: 'app_admin_disable_user', requirements: ['id' => '\d+'])]
    public function disableUser(User $user, EntityManagerInterface $em) : Response
    {
        $user->setIsActive(false);
        $em->flush();

        $this->addFlash('success', "{$user->getUsername()} a été désactivé.");

        return $this->redirectToRoute('app_admin');
    }
}
