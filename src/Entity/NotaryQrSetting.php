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
 * Entity class for "notary_qr_settings" table
 */
#[Entity]
#[Table(name: "notary_qr_settings")]
class NotaryQrSetting extends AbstractEntity
{
    #[Id]
    #[Column(name: "settings_id", type: "integer", unique: true)]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[SequenceGenerator(sequenceName: "notary_qr_settings_settings_id_seq")]
    private int $settingsId;

    #[Column(name: "notary_id", type: "integer", unique: true)]
    private int $notaryId;

    #[Column(name: "default_size", type: "integer", nullable: true)]
    private ?int $defaultSize;

    #[Column(name: "foreground_color", type: "string", nullable: true)]
    private ?string $foregroundColor;

    #[Column(name: "background_color", type: "string", nullable: true)]
    private ?string $backgroundColor;

    #[Column(name: "logo_path", type: "string", nullable: true)]
    private ?string $logoPath;

    #[Column(name: "logo_size_percent", type: "integer", nullable: true)]
    private ?int $logoSizePercent;

    #[Column(name: "error_correction", type: "string", nullable: true)]
    private ?string $errorCorrection;

    #[Column(name: "corner_radius_percent", type: "integer", nullable: true)]
    private ?int $cornerRadiusPercent;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    public function __construct()
    {
        $this->defaultSize = 250;
        $this->foregroundColor = "#000000";
        $this->backgroundColor = "#ffffff";
        $this->logoSizePercent = 20;
        $this->errorCorrection = "m";
        $this->cornerRadiusPercent = 0;
    }

    public function getSettingsId(): int
    {
        return $this->settingsId;
    }

    public function setSettingsId(int $value): static
    {
        $this->settingsId = $value;
        return $this;
    }

    public function getNotaryId(): int
    {
        return $this->notaryId;
    }

    public function setNotaryId(int $value): static
    {
        $this->notaryId = $value;
        return $this;
    }

    public function getDefaultSize(): ?int
    {
        return $this->defaultSize;
    }

    public function setDefaultSize(?int $value): static
    {
        $this->defaultSize = $value;
        return $this;
    }

    public function getForegroundColor(): ?string
    {
        return HtmlDecode($this->foregroundColor);
    }

    public function setForegroundColor(?string $value): static
    {
        $this->foregroundColor = RemoveXss($value);
        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return HtmlDecode($this->backgroundColor);
    }

    public function setBackgroundColor(?string $value): static
    {
        $this->backgroundColor = RemoveXss($value);
        return $this;
    }

    public function getLogoPath(): ?string
    {
        return HtmlDecode($this->logoPath);
    }

    public function setLogoPath(?string $value): static
    {
        $this->logoPath = RemoveXss($value);
        return $this;
    }

    public function getLogoSizePercent(): ?int
    {
        return $this->logoSizePercent;
    }

    public function setLogoSizePercent(?int $value): static
    {
        $this->logoSizePercent = $value;
        return $this;
    }

    public function getErrorCorrection(): ?string
    {
        return HtmlDecode($this->errorCorrection);
    }

    public function setErrorCorrection(?string $value): static
    {
        $this->errorCorrection = RemoveXss($value);
        return $this;
    }

    public function getCornerRadiusPercent(): ?int
    {
        return $this->cornerRadiusPercent;
    }

    public function setCornerRadiusPercent(?int $value): static
    {
        $this->cornerRadiusPercent = $value;
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
