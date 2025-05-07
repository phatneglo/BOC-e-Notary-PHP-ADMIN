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
 * Entity class for "document_fields" table
 */
#[Entity]
#[Table(name: "document_fields")]
class DocumentField extends AbstractEntity
{
    #[Id]
    #[Column(name: "document_field_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "document_fields_document_field_id_seq")]
    private int $documentFieldId;

    #[Column(name: "document_id", type: "integer", nullable: true)]
    private ?int $documentId;

    #[Column(name: "field_id", type: "integer", nullable: true)]
    private ?int $fieldId;

    #[Column(name: "field_value", type: "text", nullable: true)]
    private ?string $fieldValue;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "is_verified", type: "boolean", nullable: true)]
    private ?bool $isVerified;

    #[Column(name: "verified_by", type: "integer", nullable: true)]
    private ?int $verifiedBy;

    #[Column(name: "verification_date", type: "datetime", nullable: true)]
    private ?DateTime $verificationDate;

    public function getDocumentFieldId(): int
    {
        return $this->documentFieldId;
    }

    public function setDocumentFieldId(int $value): static
    {
        $this->documentFieldId = $value;
        return $this;
    }

    public function getDocumentId(): ?int
    {
        return $this->documentId;
    }

    public function setDocumentId(?int $value): static
    {
        $this->documentId = $value;
        return $this;
    }

    public function getFieldId(): ?int
    {
        return $this->fieldId;
    }

    public function setFieldId(?int $value): static
    {
        $this->fieldId = $value;
        return $this;
    }

    public function getFieldValue(): ?string
    {
        return HtmlDecode($this->fieldValue);
    }

    public function setFieldValue(?string $value): static
    {
        $this->fieldValue = RemoveXss($value);
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

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $value): static
    {
        $this->isVerified = $value;
        return $this;
    }

    public function getVerifiedBy(): ?int
    {
        return $this->verifiedBy;
    }

    public function setVerifiedBy(?int $value): static
    {
        $this->verifiedBy = $value;
        return $this;
    }

    public function getVerificationDate(): ?DateTime
    {
        return $this->verificationDate;
    }

    public function setVerificationDate(?DateTime $value): static
    {
        $this->verificationDate = $value;
        return $this;
    }
}
