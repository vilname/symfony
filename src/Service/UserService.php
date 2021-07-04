<?php


namespace App\Service;


use App\DTO\AddUserGroupDTO;
use App\DTO\AddUserSkillDTO;
use App\DTO\UserDTO;
use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Symfony\Forms\UserType;
use App\Symfony\Helper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserService
{
    private const CACHE_TAG = 'user';

    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private TagAwareCacheInterface $cache;
    private FormFactoryInterface $formFactory;
    private SkillService $skillService;
    private GroupService $groupService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder,
        TagAwareCacheInterface $cache,
        FormFactoryInterface $formFactory,
        SkillService $skillService,
        GroupService $groupService
    )
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->cache = $cache;
        $this->formFactory = $formFactory;
        $this->skillService = $skillService;
        $this->groupService = $groupService;
    }

    /**
     * @return User[]
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getUsers(int $page, int $perPage, $roles = null): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $this->cache->get(
            "user_{$page}_{$perPage}",
            function (ItemInterface $item) use ($userRepository, $page, $perPage, $roles) {
                $users = $userRepository->getUsers($page, $perPage, $roles);
                $usersSerialized = array_map(static fn(User $user) => $user->toArray(), $users);
                $item->set($usersSerialized);
                $item->tag(self::CACHE_TAG);

                return $usersSerialized;
            }
        );

        return $userRepository->getUsers($page, $perPage, $roles);
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

    public function getSaveForm(): FormInterface
    {
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        $skill = $skillRepository->findAll();

        return $this->formFactory->createBuilder(FormType::class)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'placeholder' => 'Выберите вариант',
                'choices' => [
                    'Админ' => "ROLE_ADMIN",
                    'Ученик' => "ROLE_APPERTICE",
                    'Учитель' => "ROLE_TEACHER",
                ]
            ])
            ->add('teacherSkill', CollectionType::class, [
                'entry_type' => UserType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add('skillSelect', ChoiceType::class, [
                'placeholder' => 'Выберите вариант',
                'multiple' => true,
                'choices' => Helper::getChoicesData($skill),
                'required' => false
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function getSearchUserGroup(): FormInterface
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findUserNotGroup('ROLE_APPERTICE');

        return $this->formFactory->createBuilder(FormType::class)
            ->add('users', ChoiceType::class, [
                'placeholder' => 'Выберите вариант',
                'choices' => Helper::getChoicesDataUsers($users),
                'required' => false
            ])
            ->getForm();
    }

    public function saveUser(User $user, UserDTO $userDTO): ?User
    {
        $user->setLogin($userDTO->login);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $userDTO->password));
        $user->setRoles($userDTO->roles);

        $this->entityManager->getEventManager();

        if ($userDTO->skillSelect) {
            $skills = $this->skillService->getEntity($userDTO->skillSelect);
            foreach ($skills as $skill) {
                $user->addSkill($skill);
            }
        }

        if (count($userDTO->newUserSkill)) {
            $skill = new Skill();
            foreach ($userDTO->newUserSkill as $item) {
                $skill->setSkill($item['skill']);
                $idSkill = $this->skillService->saveSkill($skill);

                $user->addSkill($this->skillService->getEntity($idSkill));
            }
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->cache->invalidateTags([self::CACHE_TAG]);

        return $user;
    }

    /**
     * @param string $userName
     * @throws \JsonException
     */
    public function saveUserRundomSkill(string $userName, int $count)
    {
        $userRepository = $this->entityManager->getRepository(Skill::class);
        $skills = $userRepository->findAll();

        for ($item = 0; $item <= $count; $item++) {
            $skillSelect = [];
            $randItemSkill = rand(2, 7);
            for ($i = 0; $i <= $randItemSkill; $i++) {
                $randKeySkill = rand(0, count($skills) - 1);
                $skillSelect[$randKeySkill] = $skills[$randKeySkill];
            }

            $userModel = new User();
            $userDTO = new UserDTO([
                'login' => $userName . '-' . $item,
                'password' => $userName,
                'roles' => 'ROLE_APPERTICE',
                'skillSelect' => $skillSelect
            ]);

            $ids[] = $this->saveUser($userModel, $userDTO);
        }

        return $ids;

    }

    /**
     * @param string $userName
     * @param int $count
     * @return string
     */
    public function getUserMessages(string $userName, int $count): string
    {
        return (new AddUserSkillDTO($userName, $count))->toAMQPMessage();
    }

    /**
     * @param string $page
     * @param string $perPage
     * @return string
     */
    public function getUserGroupMessages(string $page, string $perPage): string
    {
        return (new AddUserGroupDTO($page, $perPage))->toAMQPMessage();
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param string $role
     * @return User[]
     */
    public function saveUserGroup(int $page, int $perPage, $role = 'ROLE_APPERTICE')
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $groupRepository = $this->entityManager->getRepository(Group::class);

        /** @var Group[] $group */
        $groups = $groupRepository->findAll();


        foreach ($groups as $group) {
            $group->countUserGroup = count($group->getUserLink()->getValues());
            /** @var Group[] $group */
            $groupsResult[$group->getId()] = $group;
        }

        unset($groups);

        /** @var User[] $users */
        $users = $userRepository->getUsers($page, $perPage, $role);
        $skills = [];
        $userDTO = [];



        foreach ($users as $user) {
            if ($user->getSkills()->getValues()) {
                foreach ($user->getSkills()->getValues() as $skill) {
                    $skills[] = $skill->getId();
                }
            }

            $user->groupsUsers = $userRepository->findUsersGroup($skills);

            $user->saveGroup = false;
            foreach ($user->groupsUsers as $userGroup) {
                $currentGroup = $groupsResult[$userGroup['group_id']];
                // проверяю осталось ли в группе свободные места

//                dump($currentGroup->countUserGroup);
//                dump($currentGroup->getMaxCountAppertice());
//                dump("===========");

                if ($currentGroup->countUserGroup
                    <= $currentGroup->getMaxCountAppertice()
                    && !$user->saveGroup
                ) {
                    $user->saveGroup = true;
                    $currentGroup->addUserLink($user);
                    $this->groupService->saveGroup($currentGroup);
                    $groupsResult[$userGroup['group_id']]->countUserGroup++;
                }



            }

            // если пользователю не нашлось места
            if (!$user->saveGroup) {
                $user->message = 'Пользователю не хватило места';
                $userDTO = UserDTO::fromEntity($user);
            }
        }


        return $userDTO;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function findFreePlaceAppertice($userId): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $groupRepository = $this->entityManager->getRepository(Group::class);
        /** @var User $user */
        $user = $userRepository->findOneBy(['id' => $userId]);

        try {

            foreach ($user->getSkills()->getValues() as $skill) {
                $skills[] = $skill->getId();
            }

            foreach ($userRepository->findUsersGroup($skills) as $group) {
                $groups[] = $group['group_id'];
                $result['best_groups'][$group['group_id']] = $group;
            }
        } catch (EntityNotFoundException $e) {

        }


        /** @var Group[] $groupItem */
        $groupItem = $groupRepository->findBy(['id' => $groups]);

        $saveGroup = false;
        $result['best_free_groups'] = ['message' => 'Свободных мест в группах нет'];
        foreach ($groupItem as $group) {

            $result['best_groups'][$group->getId()]['group_name'] = $group->getName();

            if (count($group->getUserLink()->getValues()) < $group->getMaxCountAppertice() && !$saveGroup) {
                $result['best_free_groups'] = [
                    'group_name' => $group->getName()
                ];
                $saveGroup = true;
            }
        }

        $cnt = array_column($result['best_groups'], 'cnt');
        array_multisort($cnt, SORT_DESC, $result['best_groups']);

        if (in_array('ROLE_TEACHER', $user->getRoles())) {
            unset($result['best_free_groups']);
        }

        return $result;

    }

    public function getGraphQl()
    {

        $script = 'query {
            users(roles: "ROLE_APPERTICE", first: 3){
            edges {
              node {
                id
                _id
                login
              }
            }
          }
        }
        ';

        $ch = curl_init('http://localhost:7777/api-platform/graphql');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $script);

// Или предать массив строкой:
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);

        dump($html);

        curl_close($ch);

        die();

//        curl "http://localhost:7777/api-platform/graphql" ^
//    -H "Connection: keep-alive" ^
//    -H "X-KL-Ajax-Request: Ajax_Request" ^
//    -H "Accept: application/json" ^
//    -H "sec-ch-ua-mobile: ?0" ^
//    -H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36" ^
//    -H "sec-ch-ua: ^\^" Not;A Brand^\^";v=^\^"99^\^", ^\^"Google Chrome^\^";v=^\^"91^\^", ^\^"Chromium^\^";v=^\^"91^\^"" ^
//    -H "Content-Type: application/json" ^
//    -H "Origin: http://localhost:7777" ^
//    -H "Sec-Fetch-Site: same-origin" ^
//    -H "Sec-Fetch-Mode: cors" ^
//    -H "Sec-Fetch-Dest: empty" ^
//    -H "Referer: http://localhost:7777/api-platform/graphql?query=^%^7B^%^0A^%^09users(roles^%^3A^%^20^%^22ROLE_APPERTICE^%^22^%^2C^%^20first^%^3A^%^203)^%^7B^%^0A^%^20^%^20^%^20^%^20edges^%^20^%^7B^%^0A^%^20^%^20^%^20^%^20^%^20^%^20node^%^20^%^7B^%^0A^%^20^%^20^%^20^%^20^%^20^%^20^%^20^%^20id^%^0A^%^20^%^20^%^20^%^20^%^20^%^20^%^20^%^20_id^%^0A^%^20^%^20^%^20^%^20^%^20^%^20^%^20^%^20login^%^0A^%^20^%^20^%^20^%^20^%^20^%^20^%^7D^%^0A^%^20^%^20^%^20^%^20^%^7D^%^0A^%^20^%^20^%^7D^%^0A^%^7D^%^0A^%^0A^%^0A" ^
//    -H "Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7" ^
//    -H "Cookie: sc=Gl0dwoK6fSWUeu58gPADjNRDNjoKIxHn; PHPSESSID=20c5fd7d6ab069d3c2b8dd74499adbbf" ^
//    --data-raw "^{^\^"query^\^":^\^"^{^\^\n^\^\tusers(roles: ^\^\^\^"ROLE_APPERTICE^\^\^\^", first: 3)^{^\^\n    edges ^{^\^\n      node ^{^\^\n        id^\^\n        _id^\^\n        login^\^\n      ^}^\^\n    ^}^\^\n  ^}^\^\n^}^\^\n^\^\n^\^\n^\^",^\^"variables^\^":null^}" ^
//    --compressed
    }

    public function freeTeacher(Request $request): FormInterface
    {
        $role = $request->request->get('role');
        $role = $role ?: 'ROLE_TEACHER';

        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->freeTeacher($role);

        return $this->formFactory->createBuilder(FormType::class)
            ->add('users', ChoiceType::class, [
                'placeholder' => 'Выберите вариант',
                'choices' => Helper::getChoicesDataUsers($users),
                'required' => false
            ])
            ->getForm();
    }

}
