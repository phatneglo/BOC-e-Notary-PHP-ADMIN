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
 * Entity class for "verification_attempts" table
 */
#[Entity]
#[Table(name: "verification_attempts")]
class VerificationAttempt extends AbstractEntity
{
    #[Id]
    #[Column(name: "attempt_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "verification_attempts_attempt_id_seq")]
    private int $attemptId;

    #[Column(name: "verification_id", type: "integer", nullable: true)]
    private ?int $verificationId;

    #[Column(name: "document_number", type: "string", nullable: true)]
    private ?string $documentNumber;

    #[Column(type: "string", nullable: true)]
    private ?string $keycode;

    #[Column(name: "ip_address", type: "string", nullable: true)]
    private ?string $ipAddress;

    #[Column(name: "user_agent", type: "text", nullable: true)]
    private ?string $userAgent;

    #[Column(name: "verification_date", type: "datetime", nullable: true)]
    private ?DateTime $verificationDate;

    #[Column(name: "is_successful", type: "boolean", nullable: true)]
    private ?bool $isSuccessful;

    #[Column(name: "failure_reason", type: "text", nullable: true)]
    private ?string $failureReason;

    #[Column(type: "string", nullable: true)]
    private ?string $location;

    #[Column(name: "device_info", type: "text", nullable: true)]
    private ?string $deviceInfo;

    #[Column(name: "browser_info", type: "text", nullable: true)]
    private ?string $browserInfo;

    public function getAttemptId(): int
    {
        return $this->attemptId;
    }

    public function setAttemptId(int $value): static
    {
        $this->attemptId = $value;
        return $this;
    }

    public function getVerificationId(): ?int
    {
        return $this->verificationId;
    }

    public function setVerificationId(?int $value): static
    {
        $this->verificationId = $value;
        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return HtmlDecode($this->documentNumber);
    }

    public function setDocumentNumber(?string $value): static
    {
        $this->documentNumber = RemoveXss($value);
        return $this;
    }

    public function getKeycode(): ?string
    {
        return HtmlDecode($this->keycode);
    }

    public function setKeycode(?string $value): static
    {
        $this->keycode = RemoveXss($value);
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

    public function getVerificationDate(): ?DateTime
    {
        return $this->verificationDate;
    }

    public function setVerificationDate(?DateTime $value): static
    {
        $this->verificationDate = $value;
        return $this;
    }

    public function getIsSuccessful(): ?bool
    {
        return $this->isSuccessful;
    }

    public function setIsSuccessful(?bool $value): static
    {
        $this->isSuccessful = $value;
        return $this;
    }

    public function getFailureReason(): ?string
    {
        return HtmlDecode($this->failureReason);
    }

    public function setFailureReason(?string $value): static
    {
        $this->failureReason = RemoveXss($value);
        return $this;
    }

    public function getLocation(): ?string
    {
        return HtmlDecode($this->location);
    }

    public function setLocation(?string $value): static
    {
        $this->location = RemoveXss($value);
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

    public function getBrowserInfo(): ?string
    {
        return HtmlDecode($this->browserInfo);
    }

    public function setBrowserInfo(?string $value): static
    {
        $this->browserInfo = RemoveXss($value);
        return $this;
    }
}
