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
 * Entity class for "user_level_assignments" table
 */
#[Entity]
#[Table(name: "user_level_assignments")]
class UserLevelAssignment extends AbstractEntity
{
    #[Id]
    #[Column(name: "assignment_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "user_level_assignments_assignment_id_seq")]
    private int $assignmentId;

    #[Column(name: "system_id", type: "integer", nullable: true)]
    private ?int $systemId;

    #[Column(name: "user_level_id", type: "integer", nullable: true)]
    private ?int $userLevelId;

    #[Column(name: "user_id", type: "integer", nullable: true)]
    private ?int $userId;

    #[Column(name: "assigned_by", type: "integer", nullable: true)]
    private ?int $assignedBy;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    public function getAssignmentId(): int
    {
        return $this->assignmentId;
    }

    public function setAssignmentId(int $value): static
    {
        $this->assignmentId = $value;
        return $this;
    }

    public function getSystemId(): ?int
    {
        return $this->systemId;
    }

    public function setSystemId(?int $value): static
    {
        $this->systemId = $value;
        return $this;
    }

    public function getUserLevelId(): ?int
    {
        return $this->userLevelId;
    }

    public function setUserLevelId(?int $value): static
    {
        $this->userLevelId = $value;
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

    public function getAssignedBy(): ?int
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?int $value): static
    {
        $this->assignedBy = $value;
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
