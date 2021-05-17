<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name_Category_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_Category_en;

    /**
     * @ORM\OneToMany(targetEntity=Dog::class, mappedBy="Category")
     */
    private $Dogs;

    public function __construct()
    {
        $this->Dogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategoryFr(): ?string
    {
        return $this->name_Category_fr;
    }

    public function setNameCategoryFr(string $name_Category_fr): self
    {
        $this->name_Category_fr = $name_Category_fr;

        return $this;
    }

    public function getNameCategoryEn(): ?string
    {
        return $this->name_Category_en;
    }

    public function setNameCategoryEn(string $name_Category_en): self
    {
        $this->name_Category_en = $name_Category_en;

        return $this;
    }

    /**
     * @return Collection|Dog[]
     */
    public function getDogs(): Collection
    {
        return $this->Dogs;
    }

    public function addDog(Dog $Dog): self
    {
        if (!$this->Dogs->contains($Dog)) {
            $this->Dogs[] = $Dog;
            $Dog->setCategory($this);
        }

        return $this;
    }

    public function removeDog(Dog $Dog): self
    {
        if ($this->Dogs->removeElement($Dog)) {
            // set the owning side to null (unless already changed)
            if ($Dog->getCategory() === $this) {
                $Dog->setCategory(null);
            }
        }
        return $this;
    }
}
