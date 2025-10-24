<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Créer un utilisateur admin',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask("Email de l'admin");
        $username = $io->ask("Nom de l'admin");
        $plainPassword = $io->askHidden("Mot de passe");

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
        $user->setUsername($username);
        $user->setIsActive(false);
        $user->setIsBanned(false);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Utilisateur admin créé !');

        return Command::SUCCESS;
    }
}
