<?php
// src/Entity/WebauthnCredential.php
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
 
#[ORM\Entity(repositoryClass: \App\Repository\WebauthnCredentialRepository::class)]
#[ORM\Table(name: 'webauthn_credential')]
class WebauthnCredential
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;
 
    // Relation ManyToOne : plusieurs passkeys pour un utilisateur
    #[ORM\ManyToOne(inversedBy: 'webauthnCredentials')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;
 
    // Stocke les donnees de la cle publique serialisee en JSON
    #[ORM\Column(type: 'text')]
    private string $credentialData = '';
 
    // Nom lisible de la passkey (ex: 'Mon MacBook', 'iPhone de Paul')
    #[ORM\Column(length: 255)]
    private string $name = 'Ma Passkey';
 
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
 
    #[ORM\Column]
    private \DateTimeImmutable $lastUsedAt;
 
    public function __construct()
    {
        $this->createdAt    = new \DateTimeImmutable();
        $this->lastUsedAt   = new \DateTimeImmutable();
    }
 
    // Met a jour la date de derniere utilisation lors d'une connexion
    public function touch(): void
    {
        $this->lastUsedAt = new \DateTimeImmutable();
    }
 
    // --- GETTERS ET SETTERS ---
    public function getId(): ?Uuid { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getCredentialData(): string { return $this->credentialData; }
    public function setCredentialData(string $d): static { $this->credentialData = $d; return $this; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getLastUsedAt(): \DateTimeImmutable { return $this->lastUsedAt; }
}
