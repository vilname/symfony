<?php


namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Table(
 *     name="`appertice`"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ApperticeRepository")
 */
class Appertice implements HasMetaTimestampsInterface
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", inversedBy="apperticeSkill")
     *
     * @ORM\JoinTable(
     *     name="appertice_skill",
     *     joinColumns={@ORM\JoinColumn(name="appertice_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")}
     * )
     */
    private $apperticeSkill;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="appertice")
     */
    private Collection $group;

    public function __construct()
    {
        $this->apperticeSkill = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getApperticeSkill(): Collection
    {
        return $this->apperticeSkill;
    }

    /**
     * @param Collection $apperticeSkill
     */
    public function setApperticeSkill(Collection $apperticeSkill): void
    {
        $this->apperticeSkill = $apperticeSkill;
    }

    public function addApperticeSkill(Skill $apperticeSkill) {
        $this->apperticeSkill->add($apperticeSkill);
    }

    public function removeApperticeSkill(Skill $apperticeSkill) {
        $this->apperticeSkill->removeElement($apperticeSkill);
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}
