<?php


namespace App\Service;


use App\DTO\UserDTO;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private EntityManagerInterface $entityManager;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @return User[]
     */
    public function getUsers(int $page, int $perPage): array
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);

        return $userRepository->getUsers($page, $perPage);
    }

    public function saveUser(User $user, UserDTO $userDTO): ?User
    {
        $user->setLogin($userDTO->login);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $userDTO->password));
        $user->setRoles($userDTO->roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(int $userId, UserDTO $userDTO): bool
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->find($userId);
        if ($user === null) {
            return false;
        }

        return $this->saveUser($user, $userDTO);
    }

    public function findUserByLogin(string $login): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $user */
        $user = $userRepository->findOneBy(['login' => $login]);

        return $user;
    }

    public function updateUserToken(string $login): ?string
    {
        $user = $this->findUserByLogin($login);
        if ($user === null) {
            return false;
        }
        $token = base64_encode(random_bytes(20));
        $user->setToken($token);
        $this->entityManager->flush();

        return $token;
    }

    public function findUserByToken(string $token): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $user */
        $user = $userRepository->findOneBy(['token' => $token]);

        return $user;
    }

    public function subscribe(int $groupId, User $user): bool
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);
        $group = $groupRepository->find($groupId);
        if (!($group instanceof Group)) {
            return false;
        }

        $user->removeGroup($group);
        $user->addGroup($group);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }


    public function addUserItem(Group $group, string $userName, int $count): int
    {
        $createdAppertice = 0;
        for ($i = 0; $i < $count; $i++) {
            $login = "{$userName}_#$i";
            $password = $userName;
            $roles = json_encode(['ROLE_APPERTICE']);
            $data = compact('login', 'password', 'roles');

            $user = $this->saveUser(new User(), new UserDTO($data));
            if ($user !== null) {
                $this->subscribe($group->getId(), $user);
                $createdAppertice++;
            }
        }

        return $createdAppertice;
    }

}
