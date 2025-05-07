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
 * Entity class for "systems" table
 */
#[Entity]
#[Table(name: "systems")]
class System extends AbstractEntity
{
    #[Id]
    #[Column(name: "system_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "systems_system_id_seq")]
    private int $systemId;

    #[Column(name: "system_name", type: "string")]
    private string $systemName;

    #[Column(name: "system_code", type: "string", unique: true)]
    private string $systemCode;

    #[Column(type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "level_permissions", type: "json", nullable: true)]
    private mixed $levelPermissions;

    public function getSystemId(): int
    {
        return $this->systemId;
    }

    public function setSystemId(int $value): static
    {
        $this->systemId = $value;
        return $this;
    }

    public function getSystemName(): string
    {
        return HtmlDecode($this->systemName);
    }

    public function setSystemName(string $value): static
    {
        $this->systemName = RemoveXss($value);
        return $this;
    }

    public function getSystemCode(): string
    {
        return HtmlDecode($this->systemCode);
    }

    public function setSystemCode(string $value): static
    {
        $this->systemCode = RemoveXss($value);
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

    public function getLevelPermissions(): mixed
    {
        return HtmlDecode($this->levelPermissions);
    }

    public function setLevelPermissions(mixed $value): static
    {
        $this->levelPermissions = RemoveXss($value);
        return $this;
    }
}
