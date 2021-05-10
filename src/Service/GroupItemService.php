<?php


namespace App\Service;

use App\DTO\GroupItemDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Entity\Appertice;
use App\Entity\Group;
use App\Entity\GroupItem;
use App\Entity\Skill;
use App\Entity\Teacher;
use App\Symfony\Forms\GroupItemType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;

class GroupItemService
{
    private EntityManagerInterface $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function getGroupItems(int $page, int $perPage): array
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);

        return $groupItemRepository->getGroupItems($page, $perPage);
    }

    public function getEntityField(Form $form)
    {
        $groupItemEntitny = new GroupItem();

        $formData = $form->getData();

        $this->appertice = $this->entityManager->getRepository(Appertice::class)->find($formData['appertice']);
        $this->groupId = $this->entityManager->getRepository(Group::class)->find($formData['groupId']);
        $this->skill = $this->entityManager->getRepository(Skill::class)->find($formData['skill']);
        $this->teacher = $this->entityManager->getRepository(Teacher::class)->find($formData['teacher']);

        $groupItemEntitny->setAppertice($this->appertice);
        $groupItemEntitny->setGroupId($this->groupId);
        $groupItemEntitny->setSkill($this->skill);
        $groupItemEntitny->setTeacher($this->teacher);

        return $groupItemEntitny;
    }

    public function saveGroupItem(GroupItem $groupItemManager): ?int
    {
        $this->entityManager->persist($groupItemManager);
        $this->entityManager->flush();

        return $groupItemManager->getId();
    }

    public function getSaveForm(): FormInterface
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);
        $groupItem = $groupItemRepository->findAll();
        if ($groupItem === null) {
            return null;
        }

        $chouces = GroupItemType::getChoicesData($groupItem);
        
        // return $this->formFactory->createBuilder(FormType::class, GroupItemDTO::formEntity($groupItem))
        return $this->formFactory->createBuilder(FormType::class)
            ->add('groupId', ChoiceType::class, [
                'choices' => $chouces['group']
            ])
            ->add('appertice', ChoiceType::class, [
                'choices' => $chouces['appertice']
            ])
            ->add('skill', ChoiceType::class, [
                'choices' => $chouces['skill']
            ])
            ->add('teacher', ChoiceType::class, [
                'choices' => $chouces['teacher']
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function updateGroupItem(int $id, GroupItem $groupItem)
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);

        $groupItemElement = $groupItemRepository->find($id);

        if ($groupItem === null) {
            return false;
        }
        $groupItemElement->setGroupId($groupItem->getGroupId());
        $groupItemElement->setAppertice($groupItem->getAppertice());
        $groupItemElement->setSkill($groupItem->getSkill());
        $groupItemElement->setTeacher($groupItem->getTeacher());

        return $this->saveGroupItem($groupItemElement);
    }

    public function findGroupItemById(int $groupItemId): ?GroupItem
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);


        return $groupItemRepository->find($groupItemId);
    }

    public function deleteGroupItem(GroupItem $groupItem): bool
    {
        $this->entityManager->remove($groupItem);
        $this->entityManager->flush();

        return true;
    }

    public function getUpdateForm(int $groupItemId): ?FormInterface
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);
        $groupItemElement = $groupItemRepository->find($groupItemId);
        $groupItem = $groupItemRepository->findAll();

        if ($groupItemElement === null) {
            return null;
        }

        $chouces = GroupItemType::getChoicesData($groupItem);

        // return $this->formFactory->createBuilder(FormType::class, GroupItemDTO::getEntity($groupItem))
        return $this->formFactory->createBuilder(FormType::class)
            ->add('groupId', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['group'],
                'choice_attr' => [
                    $groupItemElement->getGroupId()->getName() => ['selected' => true]
                ]
            ])
            ->add('appertice', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['appertice'],
                'choice_attr' => [
                    $groupItemElement->getAppertice()->getName() => ['selected' => true]
                ]
            ])
            ->add('skill', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['skill'],
                'choice_attr' => [
                    $groupItemElement->getSkill()->getSkill() => ['selected' => true]
                ]
            ])
            ->add('teacher', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => $chouces['teacher'],
                'choice_attr' => [
                    $groupItemElement->getTeacher()->getName() => ['selected' => true]
                ]
            ])
            ->add('submit', SubmitType::class)
            ->setMethod('PATCH')
            ->getForm();
    }
}
