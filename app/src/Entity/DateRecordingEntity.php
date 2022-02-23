<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class DateRecordingEntity implements EntityInterface
{
    /**
     * @ORM\Column(name="created_at", type="datetime_immutable", nullable=true)
     */
    protected ?DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=true)
     */
    protected ?DateTimeImmutable $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function setCreated(): self
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdated(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
