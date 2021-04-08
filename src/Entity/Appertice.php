<?php


namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;



/**
 * @ORM\Table(
 *     name="`appertice`"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ApperticeRepository")
 */

class Appertice
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", inversedBy="apperticeSkill")
     */
    private Skill $apperticeSkill;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GroupItem", mappedBy="apperticeGroupItem")
     */
    private Collection $apperticeGroupItem;

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
     * @return Skill
     */
    public function getApperticeSkill(): Skill
    {
        return $this->apperticeSkill;
    }

    /**
     * @param Skill $apperticeSkill
     */
    public function setApperticeSkill(Skill $apperticeSkill): void
    {
        $this->apperticeSkill = $apperticeSkill;
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}