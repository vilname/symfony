<?php

namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiResource;
use JMS\Serializer\Annotation as JMS;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource
 * @ApiFilter(SearchFilter::class, properties={"roles":"partial"})
 */
class User implements JsonSerializable, UserInterface, HasMetaTimestampsInterface
{
    use DoctrineEntityCreatedAtTrait;
    use DoctrineEntityUpdatedAtTrait;

    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private ?int $id = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     * @JMS\Groups({"elastica"})
     */
    private string $login;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=1024)
     * @JMS\Groups({"elastica"})
     */
    private string $roles;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $maxCountGroup = null;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="userLink")
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, inversedBy="users")
     * @JMS\Groups({"elastica"})
     */
    private $skills;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string[]
     *
     * @throws \JsonException
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true, 512, JSON_THROW_ON_ERROR);

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     *
     * @throws \JsonException
     */
    public function setRoles(array $roles): void
    {
        $this->roles = json_encode($roles, JSON_THROW_ON_ERROR);
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->login;
    }

    /**
     * @return int|null
     */
    public function getMaxCountGroup(): ?int
    {
        return $this->maxCountGroup;
    }

    /**
     * @param int|null $maxCountGroup
     */
    public function setMaxCountGroup(?int $maxCountGroup): void
    {
        $this->maxCountGroup = $maxCountGroup;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'roles' => $this->getRoles()
        ];
    }

    /**
     * @throws \JsonException
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'roles' => $this->getRoles(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function toFeed(): array
    {
        return [
            'id' => $this->id,
            'login' => isset($this->login) ? $this->login : null,
            'password' => $this->password,
            'roles' => $this->getRoles(),
            'createdAt' => isset($this->createdAt) ? $this->createdAt->format('Y-m-d h:i:s') : '',
        ];
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUserLink($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUserLink($this);
        }

        return $this;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = DateTime::createFromFormat('U', (string)time());
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @param array $data
     * @return User
     */
    public static function setMap(array $data)
    {
        $o = new self();
        $o->setid($data['id']);
        $o->setLogin($data['login']);

        return $o;
    }
}
