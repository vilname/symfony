<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="appeartice_skills",
 *     indexes={
 *         @ORM\Index(name="appertice_skills__appertice_id__ind", columns={"appertice_id"}),
 *         @ORM\Index(name="appertice_skills__skill_id__ind", columns={"skill_id"})
 *     }
 * )
 * @ORM\Entity
 */
class ApperticeSkills
{

    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Appertice", inversedBy="apperticeId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="appertice_id", referencedColumnName="id")
     * })
     */
    private Appertice $apperticeId;

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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}