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
 * Entity class for "payment_methods" table
 */
#[Entity]
#[Table(name: "payment_methods")]
class PaymentMethod extends AbstractEntity
{
    #[Id]
    #[Column(name: "method_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "payment_methods_method_id_seq")]
    private int $methodId;

    #[Column(name: "method_name", type: "string")]
    private string $methodName;

    #[Column(name: "method_code", type: "string", unique: true)]
    private string $methodCode;

    #[Column(type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "is_active", type: "boolean", nullable: true)]
    private ?bool $isActive;

    #[Column(name: "requires_verification", type: "boolean", nullable: true)]
    private ?bool $requiresVerification;

    #[Column(name: "additional_fields", type: "json", nullable: true)]
    private mixed $additionalFields;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "created_by", type: "integer", nullable: true)]
    private ?int $createdBy;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "updated_by", type: "integer", nullable: true)]
    private ?int $updatedBy;

    public function getMethodId(): int
    {
        return $this->methodId;
    }

    public function setMethodId(int $value): static
    {
        $this->methodId = $value;
        return $this;
    }

    public function getMethodName(): string
    {
        return HtmlDecode($this->methodName);
    }

    public function setMethodName(string $value): static
    {
        $this->methodName = RemoveXss($value);
        return $this;
    }

    public function getMethodCode(): string
    {
        return HtmlDecode($this->methodCode);
    }

    public function setMethodCode(string $value): static
    {
        $this->methodCode = RemoveXss($value);
        return $this;
    }

    public function getDescription(): ?string
    {
        return HtmlDecode($this->description);
    }

    public function setDescription(?string $value): static
    {
        $this->description = RemoveXss($value);
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

    public function getRequiresVerification(): ?bool
    {
        return $this->requiresVerification;
    }

    public function setRequiresVerification(?bool $value): static
    {
        $this->requiresVerification = $value;
        return $this;
    }

    public function getAdditionalFields(): mixed
    {
        return HtmlDecode($this->additionalFields);
    }

    public function setAdditionalFields(mixed $value): static
    {
        $this->additionalFields = RemoveXss($value);
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

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?int $value): static
    {
        $this->createdBy = $value;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $value): static
    {
        $this->updatedAt = $value;
        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?int $value): static
    {
        $this->updatedBy = $value;
        return $this;
    }
}
