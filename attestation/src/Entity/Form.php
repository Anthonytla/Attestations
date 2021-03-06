<?php

namespace App\Entity;

use App\Repository\FormRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormRepository::class)
 */
class Form
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
    private $Etudiant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $conventionName;

    /**
     * @ORM\Column(type="text")
     */
    private $Message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?string
    {
        return $this->Etudiant;
    }

    public function setEtudiant(string $Etudiant): self
    {
        $this->Etudiant = $Etudiant;

        return $this;
    }

    public function getConventionName(): ?string
    {
        return $this->conventionName;
    }

    public function setConventionName(string $conventionName): self
    {
        $this->conventionName = $conventionName;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): self
    {
        $this->Message = $Message;

        return $this;
    }
}
