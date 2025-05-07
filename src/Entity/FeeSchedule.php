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
 * Entity class for "fee_schedules" table
 */
#[Entity]
#[Table(name: "fee_schedules")]
class FeeSchedule extends AbstractEntity
{
    #[Id]
    #[Column(name: "fee_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "fee_schedules_fee_id_seq")]
    private int $feeId;

    #[Column(name: "template_id", type: "integer", nullable: true)]
    private ?int $templateId;

    #[Column(name: "fee_name", type: "string")]
    private string $feeName;

    #[Column(name: "fee_amount", type: "decimal")]
    private string $feeAmount;

    #[Column(name: "fee_type", type: "string", nullable: true)]
    private ?string $feeType;

    #[Column(type: "string", nullable: true)]
    private ?string $currency;

    #[Column(name: "effective_from", type: "date")]
    private DateTime $effectiveFrom;

    #[Column(name: "effective_to", type: "date", nullable: true)]
    private ?DateTime $effectiveTo;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "created_by", type: "integer", nullable: true)]
    private ?int $createdBy;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "updated_by", type: "integer", nullable: true)]
    private ?int $updatedBy;

    #[Column(name: "is_active", type: "boolean", nullable: true)]
    private ?bool $isActive;

    #[Column(type: "text", nullable: true)]
    private ?string $description;

    public function __construct()
    {
        $this->feeType = "fixed";
        $this->currency = "php";
    }

    public function getFeeId(): int
    {
        return $this->feeId;
    }

    public function setFeeId(int $value): static
    {
        $this->feeId = $value;
        return $this;
    }

    public function getTemplateId(): ?int
    {
        return $this->templateId;
    }

    public function setTemplateId(?int $value): static
    {
        $this->templateId = $value;
        return $this;
    }

    public function getFeeName(): string
    {
        return HtmlDecode($this->feeName);
    }

    public function setFeeName(string $value): static
    {
        $this->feeName = RemoveXss($value);
        return $this;
    }

    public function getFeeAmount(): string
    {
        return $this->feeAmount;
    }

    public function setFeeAmount(string $value): static
    {
        $this->feeAmount = $value;
        return $this;
    }

    public function getFeeType(): ?string
    {
        return HtmlDecode($this->feeType);
    }

    public function setFeeType(?string $value): static
    {
        $this->feeType = RemoveXss($value);
        return $this;
    }

    public function getCurrency(): ?string
    {
        return HtmlDecode($this->currency);
    }

    public function setCurrency(?string $value): static
    {
        $this->currency = RemoveXss($value);
        return $this;
    }

    public function getEffectiveFrom(): DateTime
    {
        return $this->effectiveFrom;
    }

    public function setEffectiveFrom(DateTime $value): static
    {
        $this->effectiveFrom = $value;
        return $this;
    }

    public function getEffectiveTo(): ?DateTime
    {
        return $this->effectiveTo;
    }

    public function setEffectiveTo(?DateTime $value): static
    {
        $this->effectiveTo = $value;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $value): static
    {
        $this->isActive = $value;
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
}
