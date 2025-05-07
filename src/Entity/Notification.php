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
 * Entity class for "notifications" table
 */
#[Entity]
#[Table(name: "notifications")]
class Notification extends AbstractEntity
{
    #[Id]
    #[Column(type: "string", unique: true)]
    private string $id;

    #[Column(type: "datetimetz", nullable: true)]
    private ?DateTime $timestamp;

    #[Column(type: "string", nullable: true)]
    private ?string $type;

    #[Column(type: "string", nullable: true)]
    private ?string $target;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(type: "string", nullable: true)]
    private ?string $subject;

    #[Column(type: "text", nullable: true)]
    private ?string $body;

    #[Column(type: "string", nullable: true)]
    private ?string $link;

    #[Column(name: "from_system", type: "string", nullable: true)]
    private ?string $fromSystem;

    #[Column(name: "is_read", type: "boolean", nullable: true)]
    private ?bool $isRead;

    #[Column(name: "created_at", type: "datetimetz", nullable: true)]
    private ?DateTime $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTimestamp(): ?DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(?DateTime $value): static
    {
        $this->timestamp = $value;
        return $this;
    }

    public function getType(): ?string
    {
        return HtmlDecode($this->type);
    }

    public function setType(?string $value): static
    {
        $this->type = RemoveXss($value);
        return $this;
    }

    public function getTarget(): ?string
    {
        return HtmlDecode($this->target);
    }

    public function setTarget(?string $value): static
    {
        $this->target = RemoveXss($value);
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

    public function getSubject(): ?string
    {
        return HtmlDecode($this->subject);
    }

    public function setSubject(?string $value): static
    {
        $this->subject = RemoveXss($value);
        return $this;
    }

    public function getBody(): ?string
    {
        return HtmlDecode($this->body);
    }

    public function setBody(?string $value): static
    {
        $this->body = RemoveXss($value);
        return $this;
    }

    public function getLink(): ?string
    {
        return HtmlDecode($this->link);
    }

    public function setLink(?string $value): static
    {
        $this->link = RemoveXss($value);
        return $this;
    }

    public function getFromSystem(): ?string
    {
        return HtmlDecode($this->fromSystem);
    }

    public function setFromSystem(?string $value): static
    {
        $this->fromSystem = RemoveXss($value);
        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $value): static
    {
        $this->isRead = $value;
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
