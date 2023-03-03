<?php

namespace App\Entity;

use App\Repository\NosnikRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NosnikRepository::class)
 */
class Nosnik
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $artysta;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $tytul;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank
     * @Assert\Choice({"cd", "vinyl", "mp3"})
     */
    private $nosnik;

    /**
     * @ORM\Column(type="string", length=4)
     * @Assert\NotBlank
     * @Assert\Length(min=4)
     */
    private $rok_wydania;

    /**
     * @ORM\OneToMany(targetEntity=Utwory::class, mappedBy="nosnik")
     * @MaxDepth(4)
     */
    private $utwory;

    public function __construct()
    {
        $this->utwory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtysta(): ?string
    {
        return $this->artysta;
    }

    public function setArtysta(string $artysta): self
    {
        $this->artysta = $artysta;

        return $this;
    }

    public function getTytul(): ?string
    {
        return $this->tytul;
    }

    public function setTytul(?string $tytul): self
    {
        $this->tytul = $tytul;

        return $this;
    }

    public function getNosnik(): ?string
    {
        return $this->nosnik;
    }

    public function setNosnik(?string $nosnik): self
    {
        $this->nosnik = $nosnik;

        return $this;
    }

    public function getRokWydania(): ?string
    {
        return $this->rok_wydania;
    }

    public function setRokWydania(?string $rok_wydania): self
    {
        $this->rok_wydania = $rok_wydania;

        return $this;
    }

    /**
     * @return Collection<int, Utwory>
     */
    public function getUtwory(): Collection
    {
        return $this->utwory;
    }

    public function addUtwory(Utwory $utwory): self
    {
        if (!$this->utwory->contains($utwory)) {
            $this->utwory[] = $utwory;
            $utwory->setNosnik($this);
        }

        return $this;
    }

    public function removeUtwory(Utwory $utwory): self
    {
        if ($this->utwory->removeElement($utwory)) {
            // set the owning side to null (unless already changed)
            if ($utwory->getNosnik() === $this) {
                $utwory->setNosnik(null);
            }
        }

        return $this;
    }
}
