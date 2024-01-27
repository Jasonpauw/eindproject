<?php

namespace App\Entity;

use App\Repository\FriendsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendsRepository::class)]
class Friends
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userid = null;

    #[ORM\Column]
    private ?int $friendid = null;

    #[ORM\Column]
    private ?bool $requested = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(int $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    public function getFriendid(): ?int
    {
        return $this->friendid;
    }

    public function setFriendid(int $friendid): static
    {
        $this->friendid = $friendid;

        return $this;
    }

    public function isRequested(): ?bool
    {
        return $this->requested;
    }

    public function setRequested(bool $requested): static
    {
        $this->requested = $requested;

        return $this;
    }
}
