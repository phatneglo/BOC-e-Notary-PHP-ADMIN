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
 * Entity class for "document_templates" table
 */
#[Entity]
#[Table(name: "document_templates")]
class DocumentTemplate extends AbstractEntity
{
    #[Id]
    #[Column(name: "template_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "document_templates_template_id_seq")]
    private int $templateId;

    #[Column(name: "template_name", type: "string")]
    private string $templateName;

    #[Column(name: "template_code", type: "string", unique: true)]
    private string $templateCode;

    #[Column(name: "category_id", type: "integer", nullable: true)]
    private ?int $categoryId;

    #[Column(type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "html_content", type: "text", nullable: true)]
    private ?string $htmlContent;

    #[Column(name: "is_active", type: "boolean", nullable: true)]
    private ?bool $isActive;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "created_by", type: "integer", nullable: true)]
    private ?int $createdBy;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    #[Column(name: "updated_by", type: "integer", nullable: true)]
    private ?int $updatedBy;

    #[Column(type: "integer", nullable: true)]
    private ?int $version;

    #[Column(name: "notary_required", type: "boolean", nullable: true)]
    private ?bool $notaryRequired;

    #[Column(name: "fee_amount", type: "decimal", nullable: true)]
    private ?string $feeAmount;

    #[Column(name: "approval_workflow", type: "json", nullable: true)]
    private mixed $approvalWorkflow;

    #[Column(name: "template_type", type: "string", nullable: true)]
    private ?string $templateType;

    #[Column(name: "header_text", type: "text", nullable: true)]
    private ?string $headerText;

    #[Column(name: "footer_text", type: "text", nullable: true)]
    private ?string $footerText;

    #[Column(name: "preview_image_path", type: "string", nullable: true)]
    private ?string $previewImagePath;

    #[Column(name: "is_system", type: "boolean", nullable: true)]
    private ?bool $isSystem;

    #[Column(name: "owner_id", type: "integer", nullable: true)]
    private ?int $ownerId;

    #[Column(name: "original_template_id", type: "integer", nullable: true)]
    private ?int $originalTemplateId;

    public function __construct()
    {
        $this->version = 1;
    }

    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    public function setTemplateId(int $value): static
    {
        $this->templateId = $value;
        return $this;
    }

    public function getTemplateName(): string
    {
        return HtmlDecode($this->templateName);
    }

    public function setTemplateName(string $value): static
    {
        $this->templateName = RemoveXss($value);
        return $this;
    }

    public function getTemplateCode(): string
    {
        return HtmlDecode($this->templateCode);
    }

    public function setTemplateCode(string $value): static
    {
        $this->templateCode = RemoveXss($value);
        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $value): static
    {
        $this->categoryId = $value;
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

    public function getHtmlContent(): ?string
    {
        return HtmlDecode($this->htmlContent);
    }

    public function setHtmlContent(?string $value): static
    {
        $this->htmlContent = RemoveXss($value);
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $value): static
    {
        $this->isActive = $value;
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

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?int $value): static
    {
        $this->createdBy = $value;
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

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?int $value): static
    {
        $this->updatedBy = $value;
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

    public function getNotaryRequired(): ?bool
    {
        return $this->notaryRequired;
    }

    public function setNotaryRequired(?bool $value): static
    {
        $this->notaryRequired = $value;
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

    public function getApprovalWorkflow(): mixed
    {
        return HtmlDecode($this->approvalWorkflow);
    }

    public function setApprovalWorkflow(mixed $value): static
    {
        $this->approvalWorkflow = RemoveXss($value);
        return $this;
    }

    public function getTemplateType(): ?string
    {
        return HtmlDecode($this->templateType);
    }

    public function setTemplateType(?string $value): static
    {
        $this->templateType = RemoveXss($value);
        return $this;
    }

    public function getHeaderText(): ?string
    {
        return HtmlDecode($this->headerText);
    }

    public function setHeaderText(?string $value): static
    {
        $this->headerText = RemoveXss($value);
        return $this;
    }

    public function getFooterText(): ?string
    {
        return HtmlDecode($this->footerText);
    }

    public function setFooterText(?string $value): static
    {
        $this->footerText = RemoveXss($value);
        return $this;
    }

    public function getPreviewImagePath(): ?string
    {
        return HtmlDecode($this->previewImagePath);
    }

    public function setPreviewImagePath(?string $value): static
    {
        $this->previewImagePath = RemoveXss($value);
        return $this;
    }

    public function getIsSystem(): ?bool
    {
        return $this->isSystem;
    }

    public function setIsSystem(?bool $value): static
    {
        $this->isSystem = $value;
        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function setOwnerId(?int $value): static
    {
        $this->ownerId = $value;
        return $this;
    }

    public function getOriginalTemplateId(): ?int
    {
        return $this->originalTemplateId;
    }

    public function setOriginalTemplateId(?int $value): static
    {
        $this->originalTemplateId = $value;
        return $this;
    }
}
