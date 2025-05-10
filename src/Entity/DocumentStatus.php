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
 * Entity class for "document_statuses" table
 */
#[Entity]
#[Table(name: "document_statuses")]
class DocumentStatus extends AbstractEntity
{
    #[Id]
    #[Column(name: "status_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "document_statuses_status_id_seq")]
    private int $statusId;

    #[Column(name: "status_code", type: "string", unique: true)]
    private string $statusCode;

    #[Column(name: "status_name", type: "string")]
    private string $statusName;

    #[Column(type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "is_active", type: "boolean")]
    private bool $isActive;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime")]
    private DateTime $updatedAt;

    public function getStatusId(): int
    {
        return $this->statusId;
    }

    public function setStatusId(int $value): static
    {
        $this->statusId = $value;
        return $this;
    }

    public function getStatusCode(): string
    {
        return HtmlDecode($this->statusCode);
    }

    public function setStatusCode(string $value): static
    {
        $this->statusCode = RemoveXss($value);
        return $this;
    }

    public function getStatusName(): string
    {
        return HtmlDecode($this->statusName);
    }

    public function setStatusName(string $value): static
    {
        $this->statusName = RemoveXss($value);
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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $value): static
    {
        $this->isActive = $value;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $value): static
    {
        $this->createdAt = $value;
        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $value): static
    {
        $this->updatedAt = $value;
        return $this;
    }
}
