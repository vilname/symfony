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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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

    public function getEntityField(Request $request)
    {
        $groupItemEntitny = new GroupItem();

        $this->appertice = $this->entityManager->getRepository(Appertice::class)->find($request->request->get('appertice'));
        $this->groupId = $this->entityManager->getRepository(Group::class)->find($request->request->get('group_id'));
        $this->skill = $this->entityManager->getRepository(Skill::class)->find($request->request->get('skill'));
        $this->teacher = $this->entityManager->getRepository(Teacher::class)->find($request->request->get('teacher'));

        $groupItemEntitny->setAppertice($this->appertice);
        $groupItemEntitny->setGroupId($this->groupId);
        $groupItemEntitny->setSkill($this->skill);
        $groupItemEntitny->setTeacher($this->teacher);

        return $this->saveGroupItem($groupItemEntitny);
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

        
        // return $this->formFactory->createBuilder(FormType::class, GroupItemDTO::formEntity($groupItem))
        return $this->formFactory->createBuilder(FormType::class)
            ->add('id', IntegerType::class)
            ->add('appertice', CollectionType::class, [
                // 'required' => false,
                'entry_type' => GroupItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                ])
            // ->add('groupId', CollectionType::class)
            // ->add('skill', CollectionType::class, ['required' => false])
            // ->add('teacher', CollectionType::class, ['required' => false])
            ->getForm();
    }

    public function updateGroupItem(Request $request)
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);
        $appertice = $this->entityManager->getRepository(Appertice::class)
            ->find($request->request->get('appertice_id'));

        $groupItem = $groupItemRepository->find($request->request->get('id'));

        if ($groupItem === null) {
            return false;
        }
        $groupItem->setAppertice($appertice);

        return $this->saveGroupItem($groupItem);
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
}
