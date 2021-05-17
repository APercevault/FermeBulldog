<?php

namespace App\Entity;

use App\Repository\SitePageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SitePageRepository::class)
 */
class SitePage
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
     * @ORM\Column(type="string", length=255)
     */
    private $imgFile;


    public function getImgFile(): ?string
    {
        return $this->imgFile;
    }

    public function setImgFile(string $imgFile): self
    {
        $this->imgFile = $imgFile;

        return $this;
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

}
