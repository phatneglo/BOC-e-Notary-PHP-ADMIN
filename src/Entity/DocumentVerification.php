<?php

namespace PHPMaker2024\eNotary\Entity;

use DateTime;
use DateTimeImmutable;
use DateInterval;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\DBAL\Types\Types;
use PHPMaker2024\eNotary\AbstractEntity;
use PHPMaker2024\eNotary\AdvancedSecurity;
use PHPMaker2024\eNotary\UserProfile;
use function PHPMaker2024\eNotary\Config;
use function PHPMaker2024\eNotary\EntityManager;
use function PHPMaker2024\eNotary\RemoveXss;
use function PHPMaker2024\eNotary\HtmlDecode;
use function PHPMaker2024\eNotary\EncryptPassword;

/**
 * Entity class for "document_verification" table
 */
#[Entity]
#[Table(name: "document_verification")]
class DocumentVerification extends AbstractEntity
{
    #[Id]
    #[Column(name: "verification_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "document_verification_verification_id_seq")]
    private int $verificationId;

    #[Column(name: "notarized_id", type: "integer", nullable: true)]
    private ?int $notarizedId;

    #[Column(name: "document_number", type: "string")]
    private string $documentNumber;

    #[Column(type: "string")]
    private string $keycode;

    #[Column(name: "verification_url", type: "string", nullable: true)]
    private ?string $verificationUrl;

    #[Column(name: "qr_code_path", type: "string", nullable: true)]
    private ?string $qrCodePath;

    #[Column(name: "is_active", type: "boolean", nullable: true)]
    private ?bool $isActive;

    #[Column(name: "expiry_date", type: "datetime", nullable: true)]
    private ?DateTime $expiryDate;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "failed_attempts", type: "integer", nullable: true)]
    private ?int $failedAttempts;

    #[Column(name: "blocked_until", type: "datetime", nullable: true)]
    private ?DateTime $blockedUntil;

    public function __construct()
    {
        $this->failedAttempts = 0;
    }

    public function getVerificationId(): int
    {
        return $this->verificationId;
    }

    public function setVerificationId(int $value): static
    {
        $this->verificationId = $value;
        return $this;
    }

    public function getNotarizedId(): ?int
    {
        return $this->notarizedId;
    }

    public function setNotarizedId(?int $value): static
    {
        $this->notarizedId = $value;
        return $this;
    }

    public function getDocumentNumber(): string
    {
        return HtmlDecode($this->documentNumber);
    }

    public function setDocumentNumber(string $value): static
    {
        $this->documentNumber = RemoveXss($value);
        return $this;
    }

    public function getKeycode(): string
    {
        return HtmlDecode($this->keycode);
    }

    public function setKeycode(string $value): static
    {
        $this->keycode = RemoveXss($value);
        return $this;
    }

    public function getVerificationUrl(): ?string
    {
        return HtmlDecode($this->verificationUrl);
    }

    public function setVerificationUrl(?string $value): static
    {
        $this->verificationUrl = RemoveXss($value);
        return $this;
    }

    public function getQrCodePath(): ?string
    {
        return HtmlDecode($this->qrCodePath);
    }

    public function setQrCodePath(?string $value): static
    {
        $this->qrCodePath = RemoveXss($value);
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $value): static
    {
        $this->isActive = $value;
        return $this;
    }

    public function getExpiryDate(): ?DateTime
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(?DateTime $value): static
    {
        $this->expiryDate = $value;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $value): static
    {
        $this->createdAt = $value;
        return $this;
    }

    public function getFailedAttempts(): ?int
    {
        return $this->failedAttempts;
    }

    public function setFailedAttempts(?int $value): static
    {
        $this->failedAttempts = $value;
        return $this;
    }

    public function getBlockedUntil(): ?DateTime
    {
        return $this->blockedUntil;
    }

    public function setBlockedUntil(?DateTime $value): static
    {
        $this->blockedUntil = $value;
        return $this;
    }
}
