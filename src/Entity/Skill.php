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
    private string $skills;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Appertice", mappedBy="apperticeSkill")
     */
    private Collection $apperticeSkill;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Teacher", mappedBy="teacher")
     */
    private Collection $skillTeacher;

    /**
     * @ORM\OneToMany(targetEntity=GroupItem::class, mappedBy="skill")
     */
    private $groupItem;

    public function __construct()
    {
        $this->skillAppertice = new ArrayCollection();
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
     * @return Collection|Appertice[]
     */
    public function getArticles(): Collection
    {
        return $this->skillAppertice;
    }
    // public function addSkillAppertice(Appertice $skillAppertice): self
    // {
    //     if (!$this->skillAppertice->contains($skillAppertice)) {
    //         $this->skillAppertice[] = $skillAppertice;
    //         $skillAppertice->addSkills($this);
    //     }
    //     return $this;
    // }
    // public function removeSkillAppertice(Appertice $skillAppertice): self
    // {
    //     if ($this->skillAppertice->contains($skillAppertice)) {
    //         $this->skillAppertice->removeElement($skillAppertice);
    //         $skillAppertice->removeSkill($this);
    //     }
    //     return $this;
    // }

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
            $groupItem->setSkill($this);
        }

        return $this;
    }

    public function removeGroupItem(GroupItem $groupItem): self
    {
        if ($this->groupItem->removeElement($groupItem)) {
            // set the owning side to null (unless already changed)
            if ($groupItem->getSkill() === $this) {
                $groupItem->setSkill(null);
            }
        }

        return $this;
    }

}