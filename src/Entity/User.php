<?php

namespace App\Entity;

use App\Entity\Traits\DoctrineEntityCreatedAtTrait;
use App\Entity\Traits\DoctrineEntityUpdatedAtTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
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
     */
    private string $login;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private string $roles;

    /**
     * @ORM\Column(type="string", length=32, nullable=true, unique=true)
     */
    private string $token;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", inversedBy="apperticeSkill")
     *
     * @ORM\JoinTable(
     *     name="user_skill",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")}
     * )
     */
    private $userSkill;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="appertice")
     */
    private Collection $group;

    public function __construct()
    {
        $this->userSkill = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
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
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_APPERTICE';

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
     * @return Collection
     */
    public function getApperticeSkill(): Collection
    {
        return $this->userSkill;
    }

    /**
     * @param Collection $userSkill
     */
    public function setUserSkill(Collection $userSkill): void
    {
        $this->userSkill = $userSkill;
    }

    public function addUserSkill(Skill $userSkill) {
        $this->userSkill->add($userSkill);
    }

    public function removeUserSkill(Skill $userSkill) {
        $this->userSkill->removeElement($userSkill);
    }


    /**
     * @throws JsonException
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
}
