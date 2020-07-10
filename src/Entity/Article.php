<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdateDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Categorys::class, inversedBy="articles")
     */
    private $categorys;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=6, nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $currency;

    public function __construct()
    {
        $this->categorys = new ArrayCollection();
        $this->currency = 'EUR';
        $this->amount = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->lastUpdateDate;
    }

    public function setLastUpdateDate(?\DateTimeInterface $lastUpdateDate): self
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Categorys[]
     */
    public function getCategorys(): Collection
    {
        return $this->categorys;
    }

    public function addCategory(Categorys $category): self
    {
        if (!$this->categorys->contains($category)) {
            $this->categorys[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categorys $category): self
    {
        if ($this->categorys->contains($category)) {
            $this->categorys->removeElement($category);
        }

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
