<?php

namespace App\Entity;

use App\Repository\ConventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConventionRepository::class)
 */
class Convention
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
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbHeur;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="convention", orphanRemoval=true)
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Attestation::class, mappedBy="convention")
     */
    private $attestation;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->attestation = new ArrayCollection();
    }

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

    public function getNbHeur(): ?int
    {
        return $this->nbHeur;
    }

    public function setNbHeur(int $nbHeur): self
    {
        $this->nbHeur = $nbHeur;

        return $this;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setConvention($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getConvention() === $this) {
                $etudiant->setConvention(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attestation[]
     */
    public function getAttestation(): Collection
    {
        return $this->attestation;
    }

    public function addAttestation(Attestation $attestation): self
    {
        if (!$this->attestation->contains($attestation)) {
            $this->attestation[] = $attestation;
            $attestation->setConvention($this);
        }

        return $this;
    }

    public function removeAttestation(Attestation $attestation): self
    {
        if ($this->attestation->removeElement($attestation)) {
            // set the owning side to null (unless already changed)
            if ($attestation->getConvention() === $this) {
                $attestation->setConvention(null);
            }
        }

        return $this;
    }
}
