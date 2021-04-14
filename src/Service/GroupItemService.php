<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Appertice;
use App\Entity\Group;
use App\Entity\GroupItem;
use App\Entity\Skill;
use App\Entity\Teacher;

// use App\Repository\GroupItemRepository;

class GroupItemService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // public function getAppertices(int $page, int $perPage): array
    // {
    //     $apperticeRepository = $this->entityManager->getRepository(Appertice::class);

    //     return $apperticeRepository->getAppertices($page, $perPage);
    // }

    public function saveGroupItem(Request $request): ?int
    {
        $groupItemManager = new GroupItem();

        $appertice = $this->entityManager->getRepository(Appertice::class)->find($request->request->get('appertice'));
        $groupId = $this->entityManager->getRepository(Group::class)->find($request->request->get('group_id'));
        $skill = $this->entityManager->getRepository(Skill::class)->find($request->request->get('skill'));
        $teacher = $this->entityManager->getRepository(Teacher::class)->find($request->request->get('teacher'));

        $groupItemManager->setAppertice($appertice);
        $groupItemManager->setGroupId($groupId);
        $groupItemManager->setSkill($skill);
        $groupItemManager->setTeacher($teacher);

        $this->entityManager->persist($groupItemManager);
        $this->entityManager->flush();

        return $groupItemManager->getId();
    }

    // public function updateAppertice(int $apperticeId, Appertice $apperticeItem) 
    // {
    //     $apperticeRepository = $this->entityManager->getRepository(Appertice::class);
    //     $appertice = $apperticeRepository->find($apperticeId);
        
    //     if ($appertice === null) {
    //         return false;
    //     }
    //     $appertice->setName($apperticeItem->getName());

    //     return $this->saveAppertice($appertice);
    // }

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
