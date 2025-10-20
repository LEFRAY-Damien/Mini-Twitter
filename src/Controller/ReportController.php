<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Tweet;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/report')]
final class ReportController extends AbstractController
{
    #[Route('/tweet/{id}/report', name: 'app_report_form',  methods: ['GET', 'POST'])]
    public function makeReport(Request $request, Tweet $tweet, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();

        $existing = $em->getRepository(Report::class)->findOneBy([
            'reported' => $user,
            'tweet' => $tweet
        ]);
        
        if ($existing) {
        $this->addFlash('info', 'Vous avez déjà signalé ce tweet.');
        return $this->redirectToRoute('app_tweet_index');
     }

       
        $report = new Report();
        $report->setTweet($tweet);
        $report->setReported($user);

        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setStatus('new');
            $em->persist($report);
            $em->flush();

            $this->addFlash('success', 'Tweet signalé avec succès');

            return $this->redirectToRoute('app_tweet_index');
        }

        return $this->render('report/index.html.twig', [
        'form' => $form->createView(),
        'tweet' => $tweet,
    ]);
        
    }

    #[Route('/listTweets', name: 'app_tweet_report')]
    
        public function listTweets(ReportRepository $reportRepository) : Response 
        {
            $reports = $reportRepository->findBy(['status' => 'new'], ['createdAt' => 'DESC']);
            return $this->render('admin/adminTweet.html.twig', [
                     'reports' => $reports,
                ]);
        }
        
}