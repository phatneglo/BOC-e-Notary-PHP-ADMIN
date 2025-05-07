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
 * Entity class for "support_requests" table
 */
#[Entity]
#[Table(name: "support_requests")]
class SupportRequest extends AbstractEntity
{
    #[Id]
    #[Column(name: "request_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "support_requests_request_id_seq")]
    private int $requestId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "string")]
    private string $email;

    #[Column(type: "string")]
    private string $subject;

    #[Column(type: "text")]
    private string $message;

    #[Column(name: "request_type", type: "string")]
    private string $requestType;

    #[Column(name: "reference_number", type: "string", unique: true)]
    private string $referenceNumber;

    #[Column(type: "string")]
    private string $status;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "assigned_to", type: "integer", nullable: true)]
    private ?int $assignedTo;

    #[Column(name: "resolved_at", type: "datetime", nullable: true)]
    private ?DateTime $resolvedAt;

    #[Column(type: "text", nullable: true)]
    private ?string $response;

    #[Column(name: "ip_address", type: "string", nullable: true)]
    private ?string $ipAddress;

    #[Column(name: "user_agent", type: "text", nullable: true)]
    private ?string $userAgent;

    public function __construct()
    {
        $this->status = "pending";
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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $value): static
    {
        $this->userId = $value;
        return $this;
    }

    public function getName(): string
    {
        return HtmlDecode($this->name);
    }

    public function setName(string $value): static
    {
        $this->name = RemoveXss($value);
        return $this;
    }

    public function getEmail(): string
    {
        return HtmlDecode($this->email);
    }

    public function setEmail(string $value): static
    {
        $this->email = RemoveXss($value);
        return $this;
    }

    public function getSubject(): string
    {
        return HtmlDecode($this->subject);
    }

    public function setSubject(string $value): static
    {
        $this->subject = RemoveXss($value);
        return $this;
    }

    public function getMessage(): string
    {
        return HtmlDecode($this->message);
    }

    public function setMessage(string $value): static
    {
        $this->message = RemoveXss($value);
        return $this;
    }

    public function getRequestType(): string
    {
        return HtmlDecode($this->requestType);
    }

    public function setRequestType(string $value): static
    {
        $this->requestType = RemoveXss($value);
        return $this;
    }

    public function getReferenceNumber(): string
    {
        return HtmlDecode($this->referenceNumber);
    }

    public function setReferenceNumber(string $value): static
    {
        $this->referenceNumber = RemoveXss($value);
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

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $value): static
    {
        $this->createdAt = $value;
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

    public function getAssignedTo(): ?int
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?int $value): static
    {
        $this->assignedTo = $value;
        return $this;
    }

    public function getResolvedAt(): ?DateTime
    {
        return $this->resolvedAt;
    }

    public function setResolvedAt(?DateTime $value): static
    {
        $this->resolvedAt = $value;
        return $this;
    }

    public function getResponse(): ?string
    {
        return HtmlDecode($this->response);
    }

    public function setResponse(?string $value): static
    {
        $this->response = RemoveXss($value);
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
