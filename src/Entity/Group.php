<?php


namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`group`")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $skillCount;


    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private bool $active;

    /**
     * @ORM\OneToMany(targetEntity=GroupItem::class, mappedBy="groupId", orphanRemoval=true)
     */
    private $groupItem;

    public function __construct()
    {
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
     * @return int
     */
    public function getSkillCount(): int
    {
        return $this->skillCount;
    }

    /**
     * @param int $skillCount
     */
    public function setSkillCount(int $skillCount): void
    {
        $this->skillCount = $skillCount;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
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
            $groupItem->setGroupId($this);
        }

        return $this;
    }

    public function removeGroupItem(GroupItem $groupItem): self
    {
        if ($this->groupItem->removeElement($groupItem)) {
            // set the owning side to null (unless already changed)
            if ($groupItem->getGroupId() === $this) {
                $groupItem->setGroupId(null);
            }
        }

        return $this;
    }
}