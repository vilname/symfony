<?php


namespace App\Service;

use App\DTO\AddUserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Entity\Group;
use App\Entity\Skill;
use App\Symfony\Forms\GroupType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use DateTime;

class GroupService
{
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private UserService $userService;
    private SkillService $skillService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        UserService $userService,
        SkillService $skillService
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->skillService = $skillService;
    }

    public function getGroup(int $page, int $perPage): array
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);

        return $groupRepository->getGroup($page, $perPage);
    }

    public function getEntityField(Form $form)
    {
        $groupEntitny = new Group();
        $formData = $form->getData();

        $groupEntitny->setName($formData['groupName']);
        if ($formData['skill']) {
            $skills = $this->skillService->getEntity($formData['skill']);
        }

        foreach ($skills as $skill) {
            $groupEntitny->addSkill($skill);
        }

        return $groupEntitny;
    }

    public function saveGroup(Group $groupManager): ?int
    {
        $groupManager->setSkillCount(8);
        $groupManager->setActive(true);

        $this->entityManager->persist($groupManager);
        $this->entityManager->flush();

        return $groupManager->getId();
    }

    public function getSaveForm(): FormInterface
    {
        // return $this->formFactory->createBuilder(FormType::class, GroupDTO::formEntity($group))
        return $this->formFactory->createBuilder(FormType::class)
            ->add('groupName', TextType::class)
            ->add('skill', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'multiple' => true,
                'choices' => $this->getEntitySkillAllData(Skill::class)
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function updateGroup(int $id, Group $group)
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);

        $groupElement = $groupRepository->find($id);

        if ($group === null) {
            return false;
        }
        $groupElement->setGroupId($group->getGroupId());
        $groupElement->setAppertice($group->getAppertice());
        $groupElement->setSkill($group->getSkill());
        $groupElement->setTeacher($group->getTeacher());

        return $this->saveGroup($groupElement);
    }

    public function findGroupById(int $groupId): ?Group
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);


        return $groupRepository->find($groupId);
    }

    public function deleteGroup(Group $group): bool
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return true;
    }

    public function getUpdateForm(int $groupId): ?FormInterface
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);
        $groupElement = $groupRepository->find($groupId);
        $group = $groupRepository->findAll();

        if ($groupElement === null) {
            return null;
        }

        $chouces = GroupType::getChoicesData($group);

        return $this->formFactory->createBuilder(FormType::class)
            ->add('groupId', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['group'],
                'choice_attr' => [
                    $groupElement->getGroupId()->getName() => ['selected' => true]
                ]
            ])
            ->add('appertice', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['appertice'],
                'choice_attr' => [
                    $groupElement->getAppertice()->getName() => ['selected' => true]
                ],
                'require' => false
            ])
            ->add('skill', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['skill'],
                'choice_attr' => [
                    $groupElement->getSkill()->getSkill() => ['selected' => true]
                ],
                'require' => false
            ])
            ->add('teacher', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['teacher'],
                'choice_attr' => [
                    $groupElement->getTeacher()->getName() => ['selected' => true]
                ],
                'require' => false
            ])
            ->add('submit', SubmitType::class)
            ->setMethod('PATCH')
            ->getForm();
    }

//    public function getEntityAllData ($className) {
//        $repository = $this->entityManager->getRepository($className);
//        $result = $repository->findAll();
//
//        foreach ($result as $item) {
//            $data[$item->getName()] = $item->getId();
//        }
//
//        return $data;
//    }

    public function getEntitySkillAllData ($className) {
        $repository = $this->entityManager->getRepository($className);
        $result = $repository->findAll();

        foreach ($result as $item) {
            $data[$item->getSkill()] = $item->getId();
        }

        return $data;
    }

    public function findUserById(int $groupId): ?Group
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);
        $group = $groupRepository->find($groupId);

        return $group;
    }

    /**
     * @return string[]
     */
    public function getApperticesMessages(Group $group, string $userName, int $count): array
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = (new AddUserDTO($group->getId(), "$userName #$i", 1))->toAMQPMessage();
        }

        return $result;
    }

}
