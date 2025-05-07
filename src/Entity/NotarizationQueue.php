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
 * Entity class for "notarization_queue" table
 */
#[Entity]
#[Table(name: "notarization_queue")]
class NotarizationQueue extends AbstractEntity
{
    #[Id]
    #[Column(name: "queue_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "notarization_queue_queue_id_seq")]
    private int $queueId;

    #[Column(name: "request_id", type: "integer", unique: true, nullable: true)]
    private ?int $requestId;

    #[Column(name: "notary_id", type: "integer", nullable: true)]
    private ?int $notaryId;

    #[Column(name: "queue_position", type: "integer", nullable: true)]
    private ?int $queuePosition;

    #[Column(name: "entry_time", type: "datetime", nullable: true)]
    private ?DateTime $entryTime;

    #[Column(name: "processing_started_at", type: "datetime", nullable: true)]
    private ?DateTime $processingStartedAt;

    #[Column(name: "completed_at", type: "datetime", nullable: true)]
    private ?DateTime $completedAt;

    #[Column(type: "string", nullable: true)]
    private ?string $status;

    #[Column(name: "estimated_wait_time", type: "integer", nullable: true)]
    private ?int $estimatedWaitTime;

    public function __construct()
    {
        $this->status = "queued";
    }

    public function getQueueId(): int
    {
        return $this->queueId;
    }

    public function setQueueId(int $value): static
    {
        $this->queueId = $value;
        return $this;
    }

    public function getRequestId(): ?int
    {
        return $this->requestId;
    }

    public function setRequestId(?int $value): static
    {
        $this->requestId = $value;
        return $this;
    }

    public function getNotaryId(): ?int
    {
        return $this->notaryId;
    }

    public function setNotaryId(?int $value): static
    {
        $this->notaryId = $value;
        return $this;
    }

    public function getQueuePosition(): ?int
    {
        return $this->queuePosition;
    }

    public function setQueuePosition(?int $value): static
    {
        $this->queuePosition = $value;
        return $this;
    }

    public function getEntryTime(): ?DateTime
    {
        return $this->entryTime;
    }

    public function setEntryTime(?DateTime $value): static
    {
        $this->entryTime = $value;
        return $this;
    }

    public function getProcessingStartedAt(): ?DateTime
    {
        return $this->processingStartedAt;
    }

    public function setProcessingStartedAt(?DateTime $value): static
    {
        $this->processingStartedAt = $value;
        return $this;
    }

    public function getCompletedAt(): ?DateTime
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?DateTime $value): static
    {
        $this->completedAt = $value;
        return $this;
    }

    public function getStatus(): ?string
    {
        return HtmlDecode($this->status);
    }

    public function setStatus(?string $value): static
    {
        $this->status = RemoveXss($value);
        return $this;
    }

    public function getEstimatedWaitTime(): ?int
    {
        return $this->estimatedWaitTime;
    }

    public function setEstimatedWaitTime(?int $value): static
    {
        $this->estimatedWaitTime = $value;
        return $this;
    }
}
