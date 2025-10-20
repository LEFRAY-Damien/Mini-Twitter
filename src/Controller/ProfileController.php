<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function myProfile(UserInterface $user): Response
    {
        return $this->render('profile/profile_view.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non valide.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                    $user->setAvatar($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’avatar.');
                }
            }

            $em->flush();
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/profile_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/profile/{id}', name: 'app_profile_view')]
    public function viewProfile(User $user): Response
    {
        return $this->render('profile/profile_view.html.twig', [
            'user' => $user,
        ]);
    }

    // #[Route('/profile', name: 'app_profile')]
    // public function profile(Request $request, EntityManagerInterface $em, UserInterface $user): Response
    // {
    //     $form = $this->createForm(UserType::class, $user);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em->persist($user);
    //         $em->flush();

    //         $this->addFlash('success', 'Profil mis à jour !');
    //         return $this->redirectToRoute('app_profile');
    //     }

    //     return $this->render('profile/profile.html.twig', [
    //         'form' => $form->createView(),
    //         'user' => $user,
    //     ]);
    // }
}
