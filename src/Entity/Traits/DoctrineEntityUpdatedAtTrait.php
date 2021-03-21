<?php


namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

trait DoctrineEntityUpdatedAtTrait
{
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}