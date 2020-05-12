<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_identifier;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Reponse", mappedBy="question", cascade={"persist", "remove"})
     */
    private $reponse;

    public function  __construct()
    {
        $this->created_at = new DateTime();
        $this->status = 0;
        $this->author_identifier = "un visiteur annonyme !";
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthorIdentifier(): ?string
    {
        return $this->author_identifier;
    }

    public function setAuthorIdentifier(string $author_identifier): self
    {
        $this->author_identifier = $author_identifier;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(Reponse $reponse): self
    {
        $this->reponse = $reponse;

        // set the owning side of the relation if necessary
        if ($this !== $reponse->getQuestion()) {
            $reponse->setQuestion($this);
        }

        return $this;
    }
}
