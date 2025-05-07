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
 * Entity class for "template_fields" table
 */
#[Entity]
#[Table(name: "template_fields")]
class TemplateField extends AbstractEntity
{
    #[Id]
    #[Column(name: "field_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "template_fields_field_id_seq")]
    private int $fieldId;

    #[Column(name: "template_id", type: "integer", nullable: true)]
    private ?int $templateId;

    #[Column(name: "field_name", type: "string")]
    private string $fieldName;

    #[Column(name: "field_label", type: "string")]
    private string $fieldLabel;

    #[Column(name: "field_type", type: "string")]
    private string $fieldType;

    #[Column(name: "field_options", type: "text", nullable: true)]
    private ?string $fieldOptions;

    #[Column(name: "is_required", type: "boolean", nullable: true)]
    private ?bool $isRequired;

    #[Column(type: "text", nullable: true)]
    private ?string $placeholder;

    #[Column(name: "default_value", type: "text", nullable: true)]
    private ?string $defaultValue;

    #[Column(name: "field_order", type: "integer", nullable: true)]
    private ?int $fieldOrder;

    #[Column(name: "validation_rules", type: "text", nullable: true)]
    private ?string $validationRules;

    #[Column(name: "help_text", type: "text", nullable: true)]
    private ?string $helpText;

    #[Column(name: "field_width", type: "string", nullable: true)]
    private ?string $fieldWidth;

    #[Column(name: "is_visible", type: "boolean", nullable: true)]
    private ?bool $isVisible;

    #[Column(name: "section_name", type: "string", nullable: true)]
    private ?string $sectionName;

    #[Column(name: "x_position", type: "integer", nullable: true)]
    private ?int $xPosition;

    #[Column(name: "y_position", type: "integer", nullable: true)]
    private ?int $yPosition;

    #[Column(name: "group_name", type: "string", nullable: true)]
    private ?string $groupName;

    #[Column(name: "conditional_display", type: "json", nullable: true)]
    private mixed $conditionalDisplay;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    public function __construct()
    {
        $this->fieldWidth = "full";
    }

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function setFieldId(int $value): static
    {
        $this->fieldId = $value;
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

    public function getFieldName(): string
    {
        return HtmlDecode($this->fieldName);
    }

    public function setFieldName(string $value): static
    {
        $this->fieldName = RemoveXss($value);
        return $this;
    }

    public function getFieldLabel(): string
    {
        return HtmlDecode($this->fieldLabel);
    }

    public function setFieldLabel(string $value): static
    {
        $this->fieldLabel = RemoveXss($value);
        return $this;
    }

    public function getFieldType(): string
    {
        return HtmlDecode($this->fieldType);
    }

    public function setFieldType(string $value): static
    {
        $this->fieldType = RemoveXss($value);
        return $this;
    }

    public function getFieldOptions(): ?string
    {
        return HtmlDecode($this->fieldOptions);
    }

    public function setFieldOptions(?string $value): static
    {
        $this->fieldOptions = RemoveXss($value);
        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(?bool $value): static
    {
        $this->isRequired = $value;
        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return HtmlDecode($this->placeholder);
    }

    public function setPlaceholder(?string $value): static
    {
        $this->placeholder = RemoveXss($value);
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return HtmlDecode($this->defaultValue);
    }

    public function setDefaultValue(?string $value): static
    {
        $this->defaultValue = RemoveXss($value);
        return $this;
    }

    public function getFieldOrder(): ?int
    {
        return $this->fieldOrder;
    }

    public function setFieldOrder(?int $value): static
    {
        $this->fieldOrder = $value;
        return $this;
    }

    public function getValidationRules(): ?string
    {
        return HtmlDecode($this->validationRules);
    }

    public function setValidationRules(?string $value): static
    {
        $this->validationRules = RemoveXss($value);
        return $this;
    }

    public function getHelpText(): ?string
    {
        return HtmlDecode($this->helpText);
    }

    public function setHelpText(?string $value): static
    {
        $this->helpText = RemoveXss($value);
        return $this;
    }

    public function getFieldWidth(): ?string
    {
        return HtmlDecode($this->fieldWidth);
    }

    public function setFieldWidth(?string $value): static
    {
        $this->fieldWidth = RemoveXss($value);
        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(?bool $value): static
    {
        $this->isVisible = $value;
        return $this;
    }

    public function getSectionName(): ?string
    {
        return HtmlDecode($this->sectionName);
    }

    public function setSectionName(?string $value): static
    {
        $this->sectionName = RemoveXss($value);
        return $this;
    }

    public function getXPosition(): ?int
    {
        return $this->xPosition;
    }

    public function setXPosition(?int $value): static
    {
        $this->xPosition = $value;
        return $this;
    }

    public function getYPosition(): ?int
    {
        return $this->yPosition;
    }

    public function setYPosition(?int $value): static
    {
        $this->yPosition = $value;
        return $this;
    }

    public function getGroupName(): ?string
    {
        return HtmlDecode($this->groupName);
    }

    public function setGroupName(?string $value): static
    {
        $this->groupName = RemoveXss($value);
        return $this;
    }

    public function getConditionalDisplay(): mixed
    {
        return HtmlDecode($this->conditionalDisplay);
    }

    public function setConditionalDisplay(mixed $value): static
    {
        $this->conditionalDisplay = RemoveXss($value);
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
}
