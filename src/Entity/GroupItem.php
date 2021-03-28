<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(
 *     name="`group_item`"
 * )
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
     * @ORM\ManyToOne(targetEntity="Skill", inversedBy="skillGroupItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_group_item", referencedColumnName="id")
     * })
     */
    private Skill $skillGroupItem;

    /**
     * @ORM\ManyToOne(targetEntity="Appertice", inversedBy="apperticeGroupItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="appertice_group_item", referencedColumnName="id")
     * })
     */
    private Appertice $apperticeGroupItem;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="teacherGroupItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_group_item", referencedColumnName="id")
     * })
     */
    private Teacher $teacherGroupItem;

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
}