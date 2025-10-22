<?php

namespace App\Controller;

use App\Entity\Follow;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/follow')]
final class FollowController extends AbstractController
{
    #[Route('/toogle/{id}', name: 'app_follow_toggle')]
    public function toggle(User $userToFollow, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $existing = $em->getRepository(Follow::class)->findOneBy([
            'follower' => $user,
            'followed' => $userToFollow,
        ]);

        if ($existing) {
            $em->remove($existing);
            $this->addFlash('info', "Vous ne suivez plus {$userToFollow->getUsername()}.");
        }else {
            $follow = new Follow();
            $follow->setFollower($user);
            $follow->setFollowed($userToFollow);
            $em->persist($follow);
            $this->addFlash('succes', "Vous venez de suivre {$userToFollow->getUsername()}!");
        }

        $em->flush();

        return $this->redirectToRoute('app_profile_view', ['id' => $userToFollow->getId()]);
    }
}
