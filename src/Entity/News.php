<?php

namespace App\Entity;
use App\Repository\NewsRepository;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * @ORM\Table(name="news", indexes={@ORM\Index(columns={"title_fr", "title_en", "text_fr", "text_en"}, flags={"fulltext"})})
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title_fr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title_en;

    /**
     * @ORM\Column(type="text")
     */
    private $text_fr;

    /**
     * @ORM\Column(type="text")
     */
    private $text_en;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $News_img;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="news")
     */
    private $User;

    public function __construct()
    {
        $this->Created_at = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleFr(): ?string
    {
        return $this->title_fr;
    }

    public function setTitleFr(string $title_fr): self
    {
        $this->title_fr = $title_fr;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(string $title_en): self
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function getTextFr(): ?string
    {
        return $this->text_fr;
    }

    public function setTextFr(string $text_fr): self
    {
        $this->text_fr = $text_fr;

        return $this;
    }

    public function getTextEn(): ?string
    {
        return $this->text_en;
    }

    public function setTextEn(string $text_en): self
    {
        $this->text_en = $text_en;

        return $this;
    }

    public function getNewsImg(): ?string
    {
        return $this->News_img;
    }

    public function setNewsImg(string $News_img): self
    {
        $this->News_img = $News_img;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->Created_at;
    }

    public function setCreatedAt(\DateTimeInterface $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
