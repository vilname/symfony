<?php


namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(
 *     name="`group_item`",
 *     indexes={
 *         @ORM\Index(name="group_item__group_id__ind", columns={"group_id"}),
 *         @ORM\Index(name="group_item__appertice__ind", columns={"appertice"}),
 *         @ORM\Index(name="group_item__skill__ind", columns={"skill"}),
 *         @ORM\Index(name="group_item__teacher__ind", columns={"teacher"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\GroupItemRepository")
 */
class GroupItem
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;


    /**
     * @ORM\ManyToOne(targetEntity=Appertice::class, inversedBy="groupItem")
     * @ORM\JoinColumn(name="appertice", nullable=true)
     */
    private $appertice;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="groupItem")
     * @ORM\JoinColumn(name="group_id", nullable=false)
     */
    private $groupId;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class, inversedBy="groupItem")
     * @ORM\JoinColumn(name="skill", nullable=true)
     */
    private $skill;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="groupItem")
     * @ORM\JoinColumn(name="teacher", nullable=true)
     */
    private $teacher;

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
     * @return Collection
     */
    public function getAppertice(): Collection
    {
        return $this->appertice;
    }

    /**
     * @param Collection $appertice
     */
    public function setAppertice(Collection $appertice): void
    {
        $this->appertice = $appertice;
    }

    public function addAppertice(Appertice $appertice) {
        $this->appertice->add($appertice);
    }

    public function removeAppertice(Appertice $appertice) {
        $this->appertice->removeElement($appertice);
    }

    public function getGroupId(): ?Group
    {
        return $this->groupId;
    }

    public function setGroupId(?Group $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
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
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id
        ];
    }

}
