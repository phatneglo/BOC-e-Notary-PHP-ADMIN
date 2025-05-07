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
 * Entity class for "notarized_documents" table
 */
#[Entity]
#[Table(name: "notarized_documents")]
class NotarizedDocument extends AbstractEntity
{
    #[Id]
    #[Column(name: "notarized_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "notarized_documents_notarized_id_seq")]
    private int $notarizedId;

    #[Column(name: "request_id", type: "integer", nullable: true)]
    private ?int $requestId;

    #[Column(name: "document_id", type: "integer", nullable: true)]
    private ?int $documentId;

    #[Column(name: "notary_id", type: "integer", nullable: true)]
    private ?int $notaryId;

    #[Column(name: "document_number", type: "string")]
    private string $documentNumber;

    #[Column(name: "page_number", type: "integer", nullable: true)]
    private ?int $pageNumber;

    #[Column(name: "book_number", type: "string", nullable: true)]
    private ?string $bookNumber;

    #[Column(name: "series_of", type: "string", nullable: true)]
    private ?string $seriesOf;

    #[Column(name: "doc_keycode", type: "string", unique: true)]
    private string $docKeycode;

    #[Column(name: "notary_location", type: "string", nullable: true)]
    private ?string $notaryLocation;

    #[Column(name: "notarization_date", type: "datetime")]
    private DateTime $notarizationDate;

    #[Column(name: "digital_signature", type: "text", nullable: true)]
    private ?string $digitalSignature;

    #[Column(name: "digital_seal", type: "text", nullable: true)]
    private ?string $digitalSeal;

    #[Column(name: "certificate_text", type: "text", nullable: true)]
    private ?string $certificateText;

    #[Column(name: "certificate_type", type: "string", nullable: true)]
    private ?string $certificateType;

    #[Column(name: "qr_code_path", type: "string", nullable: true)]
    private ?string $qrCodePath;

    #[Column(name: "notarized_document_path", type: "string", nullable: true)]
    private ?string $notarizedDocumentPath;

    #[Column(name: "expires_at", type: "datetime", nullable: true)]
    private ?DateTime $expiresAt;

    #[Column(type: "boolean", nullable: true)]
    private ?bool $revoked;

    #[Column(name: "revoked_at", type: "datetime", nullable: true)]
    private ?DateTime $revokedAt;

    #[Column(name: "revoked_by", type: "integer", nullable: true)]
    private ?int $revokedBy;

    #[Column(name: "revocation_reason", type: "text", nullable: true)]
    private ?string $revocationReason;

    public function getNotarizedId(): int
    {
        return $this->notarizedId;
    }

    public function setNotarizedId(int $value): static
    {
        $this->notarizedId = $value;
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

    public function getDocumentId(): ?int
    {
        return $this->documentId;
    }

    public function setDocumentId(?int $value): static
    {
        $this->documentId = $value;
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

    public function getDocumentNumber(): string
    {
        return HtmlDecode($this->documentNumber);
    }

    public function setDocumentNumber(string $value): static
    {
        $this->documentNumber = RemoveXss($value);
        return $this;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(?int $value): static
    {
        $this->pageNumber = $value;
        return $this;
    }

    public function getBookNumber(): ?string
    {
        return HtmlDecode($this->bookNumber);
    }

    public function setBookNumber(?string $value): static
    {
        $this->bookNumber = RemoveXss($value);
        return $this;
    }

    public function getSeriesOf(): ?string
    {
        return HtmlDecode($this->seriesOf);
    }

    public function setSeriesOf(?string $value): static
    {
        $this->seriesOf = RemoveXss($value);
        return $this;
    }

    public function getDocKeycode(): string
    {
        return HtmlDecode($this->docKeycode);
    }

    public function setDocKeycode(string $value): static
    {
        $this->docKeycode = RemoveXss($value);
        return $this;
    }

    public function getNotaryLocation(): ?string
    {
        return HtmlDecode($this->notaryLocation);
    }

    public function setNotaryLocation(?string $value): static
    {
        $this->notaryLocation = RemoveXss($value);
        return $this;
    }

    public function getNotarizationDate(): DateTime
    {
        return $this->notarizationDate;
    }

    public function setNotarizationDate(DateTime $value): static
    {
        $this->notarizationDate = $value;
        return $this;
    }

    public function getDigitalSignature(): ?string
    {
        return HtmlDecode($this->digitalSignature);
    }

    public function setDigitalSignature(?string $value): static
    {
        $this->digitalSignature = RemoveXss($value);
        return $this;
    }

    public function getDigitalSeal(): ?string
    {
        return HtmlDecode($this->digitalSeal);
    }

    public function setDigitalSeal(?string $value): static
    {
        $this->digitalSeal = RemoveXss($value);
        return $this;
    }

    public function getCertificateText(): ?string
    {
        return HtmlDecode($this->certificateText);
    }

    public function setCertificateText(?string $value): static
    {
        $this->certificateText = RemoveXss($value);
        return $this;
    }

    public function getCertificateType(): ?string
    {
        return HtmlDecode($this->certificateType);
    }

    public function setCertificateType(?string $value): static
    {
        $this->certificateType = RemoveXss($value);
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

    public function getNotarizedDocumentPath(): ?string
    {
        return HtmlDecode($this->notarizedDocumentPath);
    }

    public function setNotarizedDocumentPath(?string $value): static
    {
        $this->notarizedDocumentPath = RemoveXss($value);
        return $this;
    }

    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTime $value): static
    {
        $this->expiresAt = $value;
        return $this;
    }

    public function getRevoked(): ?bool
    {
        return $this->revoked;
    }

    public function setRevoked(?bool $value): static
    {
        $this->revoked = $value;
        return $this;
    }

    public function getRevokedAt(): ?DateTime
    {
        return $this->revokedAt;
    }

    public function setRevokedAt(?DateTime $value): static
    {
        $this->revokedAt = $value;
        return $this;
    }

    public function getRevokedBy(): ?int
    {
        return $this->revokedBy;
    }

    public function setRevokedBy(?int $value): static
    {
        $this->revokedBy = $value;
        return $this;
    }

    public function getRevocationReason(): ?string
    {
        return HtmlDecode($this->revocationReason);
    }

    public function setRevocationReason(?string $value): static
    {
        $this->revocationReason = RemoveXss($value);
        return $this;
    }
}
