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
 * Entity class for "notarization_requests" table
 */
#[Entity]
#[Table(name: "notarization_requests")]
class NotarizationRequest extends AbstractEntity
{
    #[Id]
    #[Column(name: "request_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "notarization_requests_request_id_seq")]
    private int $requestId;

    #[Column(name: "document_id", type: "integer", nullable: true)]
    private ?int $documentId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(name: "request_reference", type: "string", unique: true, nullable: true)]
    private ?string $requestReference;

    #[Column(type: "string", nullable: true)]
    private ?string $status;

    #[Column(name: "requested_at", type: "datetime", nullable: true)]
    private ?DateTime $requestedAt;

    #[Column(name: "notary_id", type: "integer", nullable: true)]
    private ?int $notaryId;

    #[Column(name: "assigned_at", type: "datetime", nullable: true)]
    private ?DateTime $assignedAt;

    #[Column(name: "notarized_at", type: "datetime", nullable: true)]
    private ?DateTime $notarizedAt;

    #[Column(name: "rejection_reason", type: "text", nullable: true)]
    private ?string $rejectionReason;

    #[Column(name: "rejected_at", type: "datetime", nullable: true)]
    private ?DateTime $rejectedAt;

    #[Column(name: "rejected_by", type: "integer", nullable: true)]
    private ?int $rejectedBy;

    #[Column(type: "integer", nullable: true)]
    private ?int $priority;

    #[Column(name: "payment_status", type: "string", nullable: true)]
    private ?string $paymentStatus;

    #[Column(name: "payment_transaction_id", type: "integer", nullable: true)]
    private ?int $paymentTransactionId;

    #[Column(name: "modified_at", type: "datetime", nullable: true)]
    private ?DateTime $modifiedAt;

    #[Column(name: "ip_address", type: "string", nullable: true)]
    private ?string $ipAddress;

    #[Column(name: "browser_info", type: "text", nullable: true)]
    private ?string $browserInfo;

    #[Column(name: "device_info", type: "text", nullable: true)]
    private ?string $deviceInfo;

    public function __construct()
    {
        $this->status = "pending";
        $this->priority = 0;
        $this->paymentStatus = "unpaid";
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

    public function getDocumentId(): ?int
    {
        return $this->documentId;
    }

    public function setDocumentId(?int $value): static
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

    public function getRequestReference(): ?string
    {
        return HtmlDecode($this->requestReference);
    }

    public function setRequestReference(?string $value): static
    {
        $this->requestReference = RemoveXss($value);
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

    public function getRequestedAt(): ?DateTime
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(?DateTime $value): static
    {
        $this->requestedAt = $value;
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

    public function getAssignedAt(): ?DateTime
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(?DateTime $value): static
    {
        $this->assignedAt = $value;
        return $this;
    }

    public function getNotarizedAt(): ?DateTime
    {
        return $this->notarizedAt;
    }

    public function setNotarizedAt(?DateTime $value): static
    {
        $this->notarizedAt = $value;
        return $this;
    }

    public function getRejectionReason(): ?string
    {
        return HtmlDecode($this->rejectionReason);
    }

    public function setRejectionReason(?string $value): static
    {
        $this->rejectionReason = RemoveXss($value);
        return $this;
    }

    public function getRejectedAt(): ?DateTime
    {
        return $this->rejectedAt;
    }

    public function setRejectedAt(?DateTime $value): static
    {
        $this->rejectedAt = $value;
        return $this;
    }

    public function getRejectedBy(): ?int
    {
        return $this->rejectedBy;
    }

    public function setRejectedBy(?int $value): static
    {
        $this->rejectedBy = $value;
        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $value): static
    {
        $this->priority = $value;
        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return HtmlDecode($this->paymentStatus);
    }

    public function setPaymentStatus(?string $value): static
    {
        $this->paymentStatus = RemoveXss($value);
        return $this;
    }

    public function getPaymentTransactionId(): ?int
    {
        return $this->paymentTransactionId;
    }

    public function setPaymentTransactionId(?int $value): static
    {
        $this->paymentTransactionId = $value;
        return $this;
    }

    public function getModifiedAt(): ?DateTime
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?DateTime $value): static
    {
        $this->modifiedAt = $value;
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

    public function getBrowserInfo(): ?string
    {
        return HtmlDecode($this->browserInfo);
    }

    public function setBrowserInfo(?string $value): static
    {
        $this->browserInfo = RemoveXss($value);
        return $this;
    }

    public function getDeviceInfo(): ?string
    {
        return HtmlDecode($this->deviceInfo);
    }

    public function setDeviceInfo(?string $value): static
    {
        $this->deviceInfo = RemoveXss($value);
        return $this;
    }
}
