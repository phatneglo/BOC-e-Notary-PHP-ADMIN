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
 * Entity class for "document_status_view" table
 */
#[Entity]
#[Table(name: "document_status_view")]
class DocumentStatusView extends AbstractEntity
{
    #[Column(name: "document_id", type: "integer", nullable: true)]
    private ?int $documentId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(name: "template_id", type: "integer", nullable: true)]
    private ?int $templateId;

    #[Column(name: "document_title", type: "string", nullable: true)]
    private ?string $documentTitle;

    #[Column(name: "document_reference", type: "string", nullable: true)]
    private ?string $documentReference;

    #[Column(type: "string", nullable: true)]
    private ?string $status;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "submitted_at", type: "datetime", nullable: true)]
    private ?DateTime $submittedAt;

    #[Column(name: "company_name", type: "string", nullable: true)]
    private ?string $companyName;

    #[Column(name: "customs_entry_number", type: "string", nullable: true)]
    private ?string $customsEntryNumber;

    #[Column(name: "date_of_entry", type: "date", nullable: true)]
    private ?DateTime $dateOfEntry;

    #[Column(name: "document_html", type: "text", nullable: true)]
    private ?string $documentHtml;

    #[Column(name: "document_data", type: "json", nullable: true)]
    private mixed $documentData;

    #[Column(name: "is_deleted", type: "boolean", nullable: true)]
    private ?bool $isDeleted;

    #[Column(name: "deletion_date", type: "datetime", nullable: true)]
    private ?DateTime $deletionDate;

    #[Column(name: "deleted_by", type: "integer", nullable: true)]
    private ?int $deletedBy;

    #[Column(name: "parent_document_id", type: "integer", nullable: true)]
    private ?int $parentDocumentId;

    #[Column(type: "integer", nullable: true)]
    private ?int $version;

    #[Column(type: "text", nullable: true)]
    private ?string $notes;

    #[Column(name: "status_id", type: "integer", nullable: true)]
    private ?int $statusId;

    #[Column(name: "status_code", type: "string", nullable: true)]
    private ?string $statusCode;

    #[Column(name: "status_name", type: "string", nullable: true)]
    private ?string $statusName;

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

    public function getTemplateId(): ?int
    {
        return $this->templateId;
    }

    public function setTemplateId(?int $value): static
    {
        $this->templateId = $value;
        return $this;
    }

    public function getDocumentTitle(): ?string
    {
        return HtmlDecode($this->documentTitle);
    }

    public function setDocumentTitle(?string $value): static
    {
        $this->documentTitle = RemoveXss($value);
        return $this;
    }

    public function getDocumentReference(): ?string
    {
        return HtmlDecode($this->documentReference);
    }

    public function setDocumentReference(?string $value): static
    {
        $this->documentReference = RemoveXss($value);
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

    public function getSubmittedAt(): ?DateTime
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?DateTime $value): static
    {
        $this->submittedAt = $value;
        return $this;
    }

    public function getCompanyName(): ?string
    {
        return HtmlDecode($this->companyName);
    }

    public function setCompanyName(?string $value): static
    {
        $this->companyName = RemoveXss($value);
        return $this;
    }

    public function getCustomsEntryNumber(): ?string
    {
        return HtmlDecode($this->customsEntryNumber);
    }

    public function setCustomsEntryNumber(?string $value): static
    {
        $this->customsEntryNumber = RemoveXss($value);
        return $this;
    }

    public function getDateOfEntry(): ?DateTime
    {
        return $this->dateOfEntry;
    }

    public function setDateOfEntry(?DateTime $value): static
    {
        $this->dateOfEntry = $value;
        return $this;
    }

    public function getDocumentHtml(): ?string
    {
        return HtmlDecode($this->documentHtml);
    }

    public function setDocumentHtml(?string $value): static
    {
        $this->documentHtml = RemoveXss($value);
        return $this;
    }

    public function getDocumentData(): mixed
    {
        return HtmlDecode($this->documentData);
    }

    public function setDocumentData(mixed $value): static
    {
        $this->documentData = RemoveXss($value);
        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $value): static
    {
        $this->isDeleted = $value;
        return $this;
    }

    public function getDeletionDate(): ?DateTime
    {
        return $this->deletionDate;
    }

    public function setDeletionDate(?DateTime $value): static
    {
        $this->deletionDate = $value;
        return $this;
    }

    public function getDeletedBy(): ?int
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?int $value): static
    {
        $this->deletedBy = $value;
        return $this;
    }

    public function getParentDocumentId(): ?int
    {
        return $this->parentDocumentId;
    }

    public function setParentDocumentId(?int $value): static
    {
        $this->parentDocumentId = $value;
        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $value): static
    {
        $this->version = $value;
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

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function setStatusId(?int $value): static
    {
        $this->statusId = $value;
        return $this;
    }

    public function getStatusCode(): ?string
    {
        return HtmlDecode($this->statusCode);
    }

    public function setStatusCode(?string $value): static
    {
        $this->statusCode = RemoveXss($value);
        return $this;
    }

    public function getStatusName(): ?string
    {
        return HtmlDecode($this->statusName);
    }

    public function setStatusName(?string $value): static
    {
        $this->statusName = RemoveXss($value);
        return $this;
    }
}
