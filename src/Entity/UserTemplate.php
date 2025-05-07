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
 * Entity class for "user_templates" table
 */
#[Entity]
#[Table(name: "user_templates")]
class UserTemplate extends AbstractEntity
{
    #[Id]
    #[Column(name: "user_template_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "user_templates_user_template_id_seq")]
    private int $userTemplateId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(name: "template_id", type: "integer", nullable: true)]
    private ?int $templateId;

    #[Column(name: "custom_name", type: "string")]
    private string $customName;

    #[Column(name: "custom_content", type: "text", nullable: true)]
    private ?string $customContent;

    #[Column(name: "is_custom", type: "boolean", nullable: true)]
    private ?bool $isCustom;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    public function getUserTemplateId(): int
    {
        return $this->userTemplateId;
    }

    public function setUserTemplateId(int $value): static
    {
        $this->userTemplateId = $value;
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

    public function getCustomName(): string
    {
        return HtmlDecode($this->customName);
    }

    public function setCustomName(string $value): static
    {
        $this->customName = RemoveXss($value);
        return $this;
    }

    public function getCustomContent(): ?string
    {
        return HtmlDecode($this->customContent);
    }

    public function setCustomContent(?string $value): static
    {
        $this->customContent = RemoveXss($value);
        return $this;
    }

    public function getIsCustom(): ?bool
    {
        return $this->isCustom;
    }

    public function setIsCustom(?bool $value): static
    {
        $this->isCustom = $value;
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
}
