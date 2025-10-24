<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
       if (!$user instanceof User) {
        return;
       }

         if ($user->isBanned()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est banni. Veuillez contacté un administrateur !');
        }

         if ($user->isActive()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est désactivé. Veuillez contacté un administrateur !');
        }
    }
    public function checkPostAuth(UserInterface $user): void
    {
    }
}