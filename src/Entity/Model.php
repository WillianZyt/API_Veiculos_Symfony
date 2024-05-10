<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['model'])]
    private ?int $id = null;

    #[Groups(['model'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['model'])]
    #[ORM\Column]
    private ?int $year = null;

    #[Groups(['model'])]
    #[ORM\Column(length: 50)]
    private ?string $color = null;

    #[Groups(['model'])]
    #[ORM\Column(length: 50)]
    private ?string $fuel = null;

    #[Groups(['model'])]
    #[ORM\ManyToOne(inversedBy: 'models')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Make $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getname(): ?string
    {
        return $this->name;
    }

    public function setname(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): static
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getCategory(): ?Make
    {
        return $this->category;
    }

    public function setCategory(?Make $category): static
    {
        $this->category = $category;

        return $this;
    }
}
