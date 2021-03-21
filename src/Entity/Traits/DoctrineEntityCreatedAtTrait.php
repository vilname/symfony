<?php


namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

trait DoctrineEntityCreatedAtTrait
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}