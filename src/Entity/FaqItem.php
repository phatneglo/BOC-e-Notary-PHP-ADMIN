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
 * Entity class for "faq_items" table
 */
#[Entity]
#[Table(name: "faq_items")]
class FaqItem extends AbstractEntity
{
    #[Id]
    #[Column(name: "faq_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "faq_items_faq_id_seq")]
    private int $faqId;

    #[Column(name: "category_id", type: "integer")]
    private int $categoryId;

    #[Column(type: "text")]
    private string $question;

    #[Column(type: "text")]
    private string $answer;

    #[Column(name: "display_order", type: "integer", nullable: true)]
    private ?int $displayOrder;

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

    #[Column(name: "view_count", type: "integer", nullable: true)]
    private ?int $viewCount;

    #[Column(type: "text", nullable: true)]
    private ?string $tags;

    public function __construct()
    {
        $this->displayOrder = 0;
        $this->viewCount = 0;
    }

    public function getFaqId(): int
    {
        return $this->faqId;
    }

    public function setFaqId(int $value): static
    {
        $this->faqId = $value;
        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $value): static
    {
        $this->categoryId = $value;
        return $this;
    }

    public function getQuestion(): string
    {
        return HtmlDecode($this->question);
    }

    public function setQuestion(string $value): static
    {
        $this->question = RemoveXss($value);
        return $this;
    }

    public function getAnswer(): string
    {
        return HtmlDecode($this->answer);
    }

    public function setAnswer(string $value): static
    {
        $this->answer = RemoveXss($value);
        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $value): static
    {
        $this->displayOrder = $value;
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

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(?int $value): static
    {
        $this->viewCount = $value;
        return $this;
    }

    public function getTags(): ?string
    {
        return HtmlDecode($this->tags);
    }

    public function setTags(?string $value): static
    {
        $this->tags = RemoveXss($value);
        return $this;
    }
}
