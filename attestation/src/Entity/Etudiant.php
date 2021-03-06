<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EtudiantRepository::class)
 */
class Etudiant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("etudiant")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("etudiant")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\OneToOne(targetEntity=Attestation::class, mappedBy="etudiant", cascade={"persist", "remove"})
     * @Groups("etudiant")
     */
    private $attestation;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="etudiants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $convention;

    public function __toString()
    {
        return $this->nom." ".$this->prenom;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(Attestation $attestation): self
    {
        // set the owning side of the relation if necessary
        if ($attestation->getEtudiant() !== $this) {
            $attestation->setEtudiant($this);
        }

        $this->attestation = $attestation;

        return $this;
    }

    public function getConvention(): ?Convention
    {
        return $this->convention;
    }

    public function setConvention(?Convention $convention): self
    {
        $this->convention = $convention;

        return $this;
    }
}
