<?php

namespace App\Entity;

use App\Repository\UtworyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UtworyRepository::class)
 */
class Utwory
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
    private $tytul_utworu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $artysta;

    /**
     * @ORM\ManyToOne(targetEntity=Nosnik::class, inversedBy="utwory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nosnik;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTytulUtworu(): ?string
    {
        return $this->tytul_utworu;
    }

    public function setTytulUtworu(string $tytul_utworu): self
    {
        $this->tytul_utworu = $tytul_utworu;

        return $this;
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

    public function getNosnik(): ?nosnik
    {
        return $this->nosnik;
    }

    public function setNosnik(?nosnik $nosnik): self
    {
        $this->nosnik = $nosnik;

        return $this;
    }
}
