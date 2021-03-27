<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`skills`")
 * @ORM\Entity(repositoryClass="App\Repository\SkillsRepository")
 */
class Skills
{

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Appertice", mappedBy="apperticeId")
     */
    private $appertices;

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

    public function __construct()
    {
        $this->appertices = new ArrayCollection();
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
        return $this->appertices;
    }
    public function addAppertice(Appertice $appertices): self
    {
        if (!$this->appertices->contains($appertices)) {
            $this->appertices[] = $appertices;
            $appertices->addSkills($this);
        }
        return $this;
    }
    public function removeAppertice(Appertice $appertices): self
    {
        if ($this->appertices->contains($appertices)) {
            $this->appertices->removeElement($appertices);
            $appertices->removeSkill($this);
        }
        return $this;
    }
}