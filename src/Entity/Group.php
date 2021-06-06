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
     * @ORM\ManyToMany(targetEntity=Skill::class, inversedBy="groupSkill")
     * @ORM\JoinTable(
     *     name="group_skill",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")}
     * )
     */
    private $skill;

    /**
     * @ORM\ManyToMany(targetEntity=Teacher::class, inversedBy="groupTeacher")
     * @ORM\JoinTable(
     *     name="group_teacher",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="teacher_id", referencedColumnName="id")}
     * )
     */
    private $teacher;

    /**
     * @ORM\ManyToMany(targetEntity=Appertice::class, inversedBy="group")
     * @ORM\JoinTable(
     *     name="group_appertice",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="appertice_id", referencedColumnName="id")}
     * )
     */
    private $appertice;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="groups")
     */
    private $userLink;

    public function __construct()
    {
        $this->appertice = new ArrayCollection();
        $this->skill = new ArrayCollection();
        $this->teacher = new ArrayCollection();
        $this->userLink = new ArrayCollection();
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

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection|Appertice[]
     */
    public function getAppertice(): Collection
    {
        return $this->appertice;
    }


    public function addAppertice(Appertice $appertice): self
    {
        if (!$this->appertice->contains($appertice)) {
            $this->appertice[] = $appertice;
        }

        return $this;
    }

    public function removeAppertice(Appertice $appertice): self
    {
        $this->appertice->removeElement($appertice);

        return $this;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skill->contains($skill)) {
            $this->skill[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skill->removeElement($skill);

        return $this;
    }

    public function addTeacher(Teacher $teacher): self
    {
        if (!$this->teacher->contains($teacher)) {
            $this->teacher[] = $teacher;
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): self
    {
        $this->teacher->removeElement($teacher);

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserLink(): Collection
    {
        return $this->userLink;
    }

    public function addUserLink(User $userLink): self
    {
        if (!$this->userLink->contains($userLink)) {
            $this->userLink[] = $userLink;
        }

        return $this;
    }

    public function removeUserLink(User $userLink): self
    {
        $this->userLink->removeElement($userLink);

        return $this;
    }
}