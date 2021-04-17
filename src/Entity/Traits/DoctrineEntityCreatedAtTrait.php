<?php


namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;

trait DoctrineEntityCreatedAtTrait
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * 
     * @Gedmo\Timestampable(on="create")
     */
    private DateTime $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }
}