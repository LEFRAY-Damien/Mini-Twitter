<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tweet')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Tweet $relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRelation(): ?Tweet
    {
        return $this->relation;
    }

    public function setRelation(?Tweet $relation): static
    {
        $this->relation = $relation;

        return $this;
    }
}
