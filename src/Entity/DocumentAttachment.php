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
 * Entity class for "document_attachments" table
 */
#[Entity]
#[Table(name: "document_attachments")]
class DocumentAttachment extends AbstractEntity
{
    #[Id]
    #[Column(name: "attachment_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "document_attachments_attachment_id_seq")]
    private int $attachmentId;

    #[Column(name: "document_id", type: "integer", nullable: true)]
    private ?int $documentId;

    #[Column(name: "file_name", type: "string")]
    private string $fileName;

    #[Column(name: "file_path", type: "string")]
    private string $filePath;

    #[Column(name: "file_type", type: "string", nullable: true)]
    private ?string $fileType;

    #[Column(name: "file_size", type: "integer", nullable: true)]
    private ?int $fileSize;

    #[Column(name: "uploaded_at", type: "datetime", nullable: true)]
    private ?DateTime $uploadedAt;

    #[Column(name: "uploaded_by", type: "integer", nullable: true)]
    private ?int $uploadedBy;

    #[Column(type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "is_supporting", type: "boolean", nullable: true)]
    private ?bool $isSupporting;

    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }

    public function setAttachmentId(int $value): static
    {
        $this->attachmentId = $value;
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

    public function getFileName(): string
    {
        return HtmlDecode($this->fileName);
    }

    public function setFileName(string $value): static
    {
        $this->fileName = RemoveXss($value);
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

    public function getFileType(): ?string
    {
        return HtmlDecode($this->fileType);
    }

    public function setFileType(?string $value): static
    {
        $this->fileType = RemoveXss($value);
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

    public function getUploadedAt(): ?DateTime
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(?DateTime $value): static
    {
        $this->uploadedAt = $value;
        return $this;
    }

    public function getUploadedBy(): ?int
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?int $value): static
    {
        $this->uploadedBy = $value;
        return $this;
    }

    public function getDescription(): ?string
    {
        return HtmlDecode($this->description);
    }

    public function setDescription(?string $value): static
    {
        $this->description = RemoveXss($value);
        return $this;
    }

    public function getIsSupporting(): ?bool
    {
        return $this->isSupporting;
    }

    public function setIsSupporting(?bool $value): static
    {
        $this->isSupporting = $value;
        return $this;
    }
}
