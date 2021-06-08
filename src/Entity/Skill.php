<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`skill`")
 * @ORM\Entity(repositoryClass="App\Repository\SkillRepository")
 */
class Skill
{
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
    private string $skill;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Appertice", mappedBy="apperticeSkill")
     */
    private Collection $apperticeSkill;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Teacher", mappedBy="teacher")
     */
    private Collection $skillTeacher;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="skill")
     */
    private $group;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="skills")
     */
    private $users;

    public function __construct()
    {
        $this->skillAppertice = new ArrayCollection();
        $this->group = new ArrayCollection();
        $this->users = new ArrayCollection();
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
    public function getSkill(): string
    {
        return $this->skill;
    }

    /**
    * @param string $skill
    */
    public function setSkill(string $skill): void
    {
        $this->skill = $skill;
    }

    /**
     * @return Collection|Appertice[]
     */
    public function getArticles(): Collection
    {
        return $this->skillAppertice;
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
            $group->setSkill($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->group->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getSkill() === $this) {
                $group->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSkill($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSkill($this);
        }

        return $this;
    }

}