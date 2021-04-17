<?php


namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;

trait DoctrineEntityUpdatedAtTrait
{
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * 
     * @Gedmo\Timestampable(on="update")
     */
    private DateTime $updatedAt;

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }
}