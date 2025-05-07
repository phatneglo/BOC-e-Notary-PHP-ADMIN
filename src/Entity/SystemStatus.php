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
 * Entity class for "system_status" table
 */
#[Entity]
#[Table(name: "system_status")]
class SystemStatus extends AbstractEntity
{
    #[Id]
    #[Column(name: "status_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "system_status_status_id_seq")]
    private int $statusId;

    #[Column(type: "string")]
    private string $status;

    #[Column(type: "text", nullable: true)]
    private ?string $message;

    #[Column(type: "bigint", nullable: true)]
    private ?string $uptime;

    #[Column(name: "active_users", type: "integer", nullable: true)]
    private ?int $activeUsers;

    #[Column(name: "queue_size", type: "integer", nullable: true)]
    private ?int $queueSize;

    #[Column(name: "average_processing_time", type: "float", nullable: true)]
    private ?float $averageProcessingTime;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    public function getStatusId(): int
    {
        return $this->statusId;
    }

    public function setStatusId(int $value): static
    {
        $this->statusId = $value;
        return $this;
    }

    public function getStatus(): string
    {
        return HtmlDecode($this->status);
    }

    public function setStatus(string $value): static
    {
        $this->status = RemoveXss($value);
        return $this;
    }

    public function getMessage(): ?string
    {
        return HtmlDecode($this->message);
    }

    public function setMessage(?string $value): static
    {
        $this->message = RemoveXss($value);
        return $this;
    }

    public function getUptime(): ?string
    {
        return $this->uptime;
    }

    public function setUptime(?string $value): static
    {
        $this->uptime = $value;
        return $this;
    }

    public function getActiveUsers(): ?int
    {
        return $this->activeUsers;
    }

    public function setActiveUsers(?int $value): static
    {
        $this->activeUsers = $value;
        return $this;
    }

    public function getQueueSize(): ?int
    {
        return $this->queueSize;
    }

    public function setQueueSize(?int $value): static
    {
        $this->queueSize = $value;
        return $this;
    }

    public function getAverageProcessingTime(): ?float
    {
        return $this->averageProcessingTime;
    }

    public function setAverageProcessingTime(?float $value): static
    {
        $this->averageProcessingTime = $value;
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
}
