<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="`teachers_slills`",
 *     indexes={
 *         @ORM\Index(name="teacher__teacher_id__ind", columns={"teacher_id"}),
 *         @ORM\Index(name="teacher_skills__skill_id__ind", columns={"skill_id"})
 *     }
 * )
 * @ORM\Entity
 */
class TeachersSkills
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

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
}