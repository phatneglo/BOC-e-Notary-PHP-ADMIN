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
 * Entity class for "pdf_metadata" table
 */
#[Entity]
#[Table(name: "pdf_metadata")]
class PdfMetadatum extends AbstractEntity
{
    #[Id]
    #[Column(name: "metadata_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "pdf_metadata_metadata_id_seq")]
    private int $metadataId;

    #[Column(name: "document_id", type: "integer", nullable: true)]
    private ?int $documentId;

    #[Column(name: "notarized_id", type: "integer", nullable: true)]
    private ?int $notarizedId;

    #[Column(name: "pdf_type", type: "string")]
    private string $pdfType;

    #[Column(name: "file_path", type: "string")]
    private string $filePath;

    #[Column(name: "file_size", type: "integer", nullable: true)]
    private ?int $fileSize;

    #[Column(name: "page_count", type: "integer", nullable: true)]
    private ?int $pageCount;

    #[Column(name: "generated_at", type: "datetime", nullable: true)]
    private ?DateTime $generatedAt;

    #[Column(name: "generated_by", type: "integer", nullable: true)]
    private ?int $generatedBy;

    #[Column(name: "expires_at", type: "datetime", nullable: true)]
    private ?DateTime $expiresAt;

    #[Column(name: "is_final", type: "boolean", nullable: true)]
    private ?bool $isFinal;

    #[Column(name: "processing_options", type: "json", nullable: true)]
    private mixed $processingOptions;

    public function getMetadataId(): int
    {
        return $this->metadataId;
    }

    public function setMetadataId(int $value): static
    {
        $this->metadataId = $value;
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

    public function getNotarizedId(): ?int
    {
        return $this->notarizedId;
    }

    public function setNotarizedId(?int $value): static
    {
        $this->notarizedId = $value;
        return $this;
    }

    public function getPdfType(): string
    {
        return HtmlDecode($this->pdfType);
    }

    public function setPdfType(string $value): static
    {
        $this->pdfType = RemoveXss($value);
        return $this;
    }

    public function getFilePath(): string
    {
        return HtmlDecode($this->filePath);
    }

    public function setFilePath(string $value): static
    {
        $this->filePath = RemoveXss($value);
        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $value): static
    {
        $this->fileSize = $value;
        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(?int $value): static
    {
        $this->pageCount = $value;
        return $this;
    }

    public function getGeneratedAt(): ?DateTime
    {
        return $this->generatedAt;
    }

    public function setGeneratedAt(?DateTime $value): static
    {
        $this->generatedAt = $value;
        return $this;
    }

    public function getGeneratedBy(): ?int
    {
        return $this->generatedBy;
    }

    public function setGeneratedBy(?int $value): static
    {
        $this->generatedBy = $value;
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

    public function getIsFinal(): ?bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(?bool $value): static
    {
        $this->isFinal = $value;
        return $this;
    }

    public function getProcessingOptions(): mixed
    {
        return HtmlDecode($this->processingOptions);
    }

    public function setProcessingOptions(mixed $value): static
    {
        $this->processingOptions = RemoveXss($value);
        return $this;
    }
}
