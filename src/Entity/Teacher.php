<?php


namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="`teachers`"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TeacherRepository")
 */
class Teacher
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
    private int $groupCount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private int $skillCount;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", inversedBy="skillTeacher")
     */
    private Collection $teacher;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="teacher")
     */
    private $group;

    public function __construct()
    {
        $this->group = new ArrayCollection();
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
    public function getGroupCount(): int
    {
        return $this->groupCount;
    }

    /**
     * @param int $groupCount
     */
    public function setGroupCount(int $groupCount): void
    {
        $this->groupCount = $groupCount;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'groupCount' => $this->groupCount
        ];
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroup(): Collection
    {
        return $this->group;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->group->contains($group)) {
            $this->group[] = $group;
            $group->setTeacher($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->group->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getTeacher() === $this) {
                $group->setTeacher(null);
            }
        }

        return $this;
    }
}