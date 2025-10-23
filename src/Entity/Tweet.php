<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'tweet')]
    private Collection $reports;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->reports = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->retweets = new ArrayCollection();
        $this->replies = new ArrayCollection();
    }
    /**
     *  @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'tweet')]
    private Collection $likes;


    /**
     * @var Collection<int, Retweet>
     */
    #[ORM\OneToMany(targetEntity: Retweet::class, mappedBy: 'tweet')]
    private Collection $retweets;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

    /**
     * @var Collection<int, Replies>
     */
    #[ORM\OneToMany(targetEntity: Replies::class, mappedBy: 'tweet')]
    private Collection $replies;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setTweet($this);
        }
        return $this;
    }
    /** 
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setTweet($this);
        }
        return $this;
    }
    /**
     * @return Collection<int, Retweet>
     */
    public function getRetweets(): Collection
    {
        return $this->retweets;
    }

    public function addRetweet(Retweet $retweet): static
    {
        if (!$this->retweets->contains($retweet)) {
            $this->retweets->add($retweet);
            $retweet->setTweet($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getTweet() === $this) {
                $report->setTweet(null);
            }
        }
        return $this;
    }
    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTweet() === $this) {
                $like->setTweet(null);
            }
        }
        return $this;
    }
    public function removeRetweet(Retweet $retweet): static
    {
        if ($this->retweets->removeElement($retweet)) {
            // set the owning side to null (unless already changed)
            if ($retweet->getTweet() === $this) {
                $retweet->setTweet(null);
            }
        }

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection<int, Replies>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Replies $reply): static
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setTweet($this);
        }

        return $this;
    }

    public function removeReply(Replies $reply): static
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getTweet() === $this) {
                $reply->setTweet(null);
            }
        }

        return $this;
    }
}
