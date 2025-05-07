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
 * Entity class for "document_activity_logs" table
 */
#[Entity]
#[Table(name: "document_activity_logs")]
class DocumentActivityLog extends AbstractEntity
{
    #[Id]
    #[Column(name: "log_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "document_activity_logs_log_id_seq")]
    private int $logId;

    #[Column(name: "document_id", type: "integer")]
    private int $documentId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(type: "string")]
    private string $action;

    #[Column(type: "text", nullable: true)]
    private ?string $details;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "ip_address", type: "string", nullable: true)]
    private ?string $ipAddress;

    #[Column(name: "user_agent", type: "text", nullable: true)]
    private ?string $userAgent;

    public function getLogId(): int
    {
        return $this->logId;
    }

    public function setLogId(int $value): static
    {
        $this->logId = $value;
        return $this;
    }

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function setDocumentId(int $value): static
    {
        $this->documentId = $value;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $value): static
    {
        $this->userId = $value;
        return $this;
    }

    public function getAction(): string
    {
        return HtmlDecode($this->action);
    }

    public function setAction(string $value): static
    {
        $this->action = RemoveXss($value);
        return $this;
    }

    public function getDetails(): ?string
    {
        return HtmlDecode($this->details);
    }

    public function setDetails(?string $value): static
    {
        $this->details = RemoveXss($value);
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

    public function getIpAddress(): ?string
    {
        return HtmlDecode($this->ipAddress);
    }

    public function setIpAddress(?string $value): static
    {
        $this->ipAddress = RemoveXss($value);
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return HtmlDecode($this->userAgent);
    }

    public function setUserAgent(?string $value): static
    {
        $this->userAgent = RemoveXss($value);
        return $this;
    }
}
