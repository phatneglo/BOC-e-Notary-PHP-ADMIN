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
 * Entity class for "payment_transactions" table
 */
#[Entity]
#[Table(name: "payment_transactions")]
class PaymentTransaction extends AbstractEntity
{
    #[Id]
    #[Column(name: "transaction_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "payment_transactions_transaction_id_seq")]
    private int $transactionId;

    #[Column(name: "request_id", type: "integer", nullable: true)]
    private ?int $requestId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(name: "payment_method_id", type: "integer", nullable: true)]
    private ?int $paymentMethodId;

    #[Column(name: "transaction_reference", type: "string", unique: true)]
    private string $transactionReference;

    #[Column(type: "decimal")]
    private string $amount;

    #[Column(type: "string", nullable: true)]
    private ?string $currency;

    #[Column(type: "string", nullable: true)]
    private ?string $status;

    #[Column(name: "payment_date", type: "datetime", nullable: true)]
    private ?DateTime $paymentDate;

    #[Column(name: "gateway_reference", type: "string", nullable: true)]
    private ?string $gatewayReference;

    #[Column(name: "gateway_response", type: "text", nullable: true)]
    private ?string $gatewayResponse;

    #[Column(name: "fee_amount", type: "decimal", nullable: true)]
    private ?string $feeAmount;

    #[Column(name: "total_amount", type: "decimal", nullable: true)]
    private ?string $totalAmount;

    #[Column(name: "payment_receipt_url", type: "string", nullable: true)]
    private ?string $paymentReceiptUrl;

    #[Column(name: "qr_code_path", type: "string", nullable: true)]
    private ?string $qrCodePath;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "ip_address", type: "string", nullable: true)]
    private ?string $ipAddress;

    #[Column(name: "user_agent", type: "text", nullable: true)]
    private ?string $userAgent;

    #[Column(type: "text", nullable: true)]
    private ?string $notes;

    public function __construct()
    {
        $this->currency = "php";
        $this->status = "pending";
        $this->feeAmount = "0";
        $this->totalAmount = (amount + fee_amount);
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function setTransactionId(int $value): static
    {
        $this->transactionId = $value;
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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $value): static
    {
        $this->userId = $value;
        return $this;
    }

    public function getPaymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(?int $value): static
    {
        $this->paymentMethodId = $value;
        return $this;
    }

    public function getTransactionReference(): string
    {
        return HtmlDecode($this->transactionReference);
    }

    public function setTransactionReference(string $value): static
    {
        $this->transactionReference = RemoveXss($value);
        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $value): static
    {
        $this->amount = $value;
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

    public function getStatus(): ?string
    {
        return HtmlDecode($this->status);
    }

    public function setStatus(?string $value): static
    {
        $this->status = RemoveXss($value);
        return $this;
    }

    public function getPaymentDate(): ?DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?DateTime $value): static
    {
        $this->paymentDate = $value;
        return $this;
    }

    public function getGatewayReference(): ?string
    {
        return HtmlDecode($this->gatewayReference);
    }

    public function setGatewayReference(?string $value): static
    {
        $this->gatewayReference = RemoveXss($value);
        return $this;
    }

    public function getGatewayResponse(): ?string
    {
        return HtmlDecode($this->gatewayResponse);
    }

    public function setGatewayResponse(?string $value): static
    {
        $this->gatewayResponse = RemoveXss($value);
        return $this;
    }

    public function getFeeAmount(): ?string
    {
        return $this->feeAmount;
    }

    public function setFeeAmount(?string $value): static
    {
        $this->feeAmount = $value;
        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?string $value): static
    {
        $this->totalAmount = $value;
        return $this;
    }

    public function getPaymentReceiptUrl(): ?string
    {
        return HtmlDecode($this->paymentReceiptUrl);
    }

    public function setPaymentReceiptUrl(?string $value): static
    {
        $this->paymentReceiptUrl = RemoveXss($value);
        return $this;
    }

    public function getQrCodePath(): ?string
    {
        return HtmlDecode($this->qrCodePath);
    }

    public function setQrCodePath(?string $value): static
    {
        $this->qrCodePath = RemoveXss($value);
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

    public function getNotes(): ?string
    {
        return HtmlDecode($this->notes);
    }

    public function setNotes(?string $value): static
    {
        $this->notes = RemoveXss($value);
        return $this;
    }
}
