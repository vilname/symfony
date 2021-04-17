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
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", inversedBy="apperticeSkill")
     *
     * @ORM\JoinTable(
     *     name="appertice_skill",
     *     joinColumns={@ORM\JoinColumn(name="appertice_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")}
     * )
     */
    private Collection $apperticeSkill;

    /**
     * @ORM\OneToMany(targetEntity=GroupItem::class, mappedBy="appertice")
     */
    private $groupItem;

    public function __construct()
    {
        $this->apperticeSkill = new ArrayCollection();
        // $this->apperticeGroupItem = new ArrayCollection();
        $this->groupItem = new ArrayCollection();
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
     * @return Skill
     */
    public function getApperticeSkill(): Skill
    {
        return $this->apperticeSkill;
    }

    /**
     * @param Skill $apperticeSkill
     */
    public function setApperticeSkill(Skill $apperticeSkill): void
    {
        $this->apperticeSkill = $apperticeSkill;
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

    /**
     * @return Collection|GroupItem[]
     */
    public function getGroupItem(): Collection
    {
        return $this->groupItem;
    }

    public function addGroupItem(GroupItem $groupItem): self
    {
        if (!$this->groupItem->contains($groupItem)) {
            $this->groupItem[] = $groupItem;
            $groupItem->setAppertice($this);
        }

        return $this;
    }

    public function removeGroupItem(GroupItem $groupItem): self
    {
        if ($this->groupItem->removeElement($groupItem)) {
            // set the owning side to null (unless already changed)
            if ($groupItem->getAppertice() === $this) {
                $groupItem->setAppertice(null);
            }
        }

        return $this;
    }
}
