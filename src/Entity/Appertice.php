<?php


namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="`appertice`")
 * @ORM\Entity(repositoryClass="App\Repository\ApperticeRepository")
 */
class Appertice
{
    use DoctrineEntityCreatedAtTrait;
    use DoctrineEntityUpdatedAtTrait;

    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity="ApperticeSkills", mappedBy="appertice_id")
     */
    private Collection $apperticeId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getApperticeId(): Collection
    {
        return $this->apperticeId;
    }

    /**
     * @param Collection $apperticeId
     */
    public function setApperticeId(Collection $apperticeId): void
    {
        $this->apperticeId = $apperticeId;
    }

}