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
 * Entity class for "support_request_history" table
 */
#[Entity]
#[Table(name: "support_request_history")]
class SupportRequestHistory extends AbstractEntity
{
    #[Id]
    #[Column(name: "history_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "support_request_history_history_id_seq")]
    private int $historyId;

    #[Column(name: "request_id", type: "integer")]
    private int $requestId;

    #[Column(type: "string")]
    private string $status;

    #[Column(type: "text", nullable: true)]
    private ?string $comment;

    #[Column(name: "created_by", type: "integer", nullable: true)]
    private ?int $createdBy;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    public function getHistoryId(): int
    {
        return $this->historyId;
    }

    public function setHistoryId(int $value): static
    {
        $this->historyId = $value;
        return $this;
    }

    public function getRequestId(): int
    {
        return $this->requestId;
    }

    public function setRequestId(int $value): static
    {
        $this->requestId = $value;
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

    public function getComment(): ?string
    {
        return HtmlDecode($this->comment);
    }

    public function setComment(?string $value): static
    {
        $this->comment = RemoveXss($value);
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
