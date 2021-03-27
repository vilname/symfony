<?php


namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Skills", inversedBy="appertices")
     */
    private $skills;

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

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }

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

    public function addSkills(Skills $skills): self
    {
        if (!$this->skills->contains($skills)) {
            $this->skills[] = $skills;
        }
        return $this;
    }

    /**
     * @return Collection|Skills[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function removeSkill(Skills $skills): self
    {
        if ($this->skills->contains($skills)) {
            $this->skills->removeElement($skills);
        }
        return $this;
    }

}