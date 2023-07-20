<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $corp = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $deuxiemecorps = null;

    #[ORM\Column(length: 255)]
    private ?string $NumPage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCorp(): ?string
    {
        return $this->corp;
    }

    public function setCorp(string $corp): self
    {
        $this->corp = $corp;

        return $this;
    }

    public function getDeuxiemecorps(): ?string
    {
        return $this->deuxiemecorps;
    }

    public function setDeuxiemecorps(?string $deuxiemecorps): self
    {
        $this->deuxiemecorps = $deuxiemecorps;

        return $this;
    }

    public function getNumPage(): ?int
    {
        return $this->NumPage;
    }

    public function setNumPage(int $NumPage): self
    {
        $this->NumPage = $NumPage;

        return $this;
    }
}
