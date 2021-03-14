<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="`group_item`")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
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
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="groupId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private Group $groupId;

    /**
     * @ORM\ManyToOne(targetEntity="Appertice", inversedBy="apperticeId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="appertice_id", referencedColumnName="id")
     * })
     */
    private Appertice $apperticeId;

    /**
     * @ORM\ManyToOne(targetEntity="Teachers", inversedBy="teacherId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private Teachers $teacherId;

    /**
     * @ORM\ManyToOne(targetEntity="Skills", inversedBy="skillId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    private Skills $skillId;

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
     * @return Appertice
     */
    public function getApperticeId(): Appertice
    {
        return $this->apperticeId;
    }

    /**
     * @param Appertice $apperticeId
     */
    public function setApperticeId(Appertice $apperticeId): void
    {
        $this->apperticeId = $apperticeId;
    }

    /**
     * @return Teachers
     */
    public function getTeacherId(): Teachers
    {
        return $this->teacherId;
    }

    /**
     * @param Teachers $teacherId
     */
    public function setTeacherId(Teachers $teacherId): void
    {
        $this->teacherId = $teacherId;
    }

    /**
     * @return Skills
     */
    public function getSkillId(): Skills
    {
        return $this->skillId;
    }

    /**
     * @param Skills $skillId
     */
    public function setSkillId(Skills $skillId): void
    {
        $this->skillId = $skillId;
    }

    /**
     * @return Group
     */
    public function getGroupId(): Group
    {
        return $this->groupId;
    }

    /**
     * @param Group $groupId
     */
    public function setGroupId(Group $groupId): void
    {
        $this->groupId = $groupId;
    }
}