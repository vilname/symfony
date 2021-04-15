<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Appertice;
use App\Entity\GroupItem;

class GroupItemService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getGroupItems(int $page, int $perPage): array
    {
        $groupItemRepository = $this->entityManager->getRepository(GroupItem::class);

        return $groupItemRepository->getGroupItems($page, $perPage);
    }

    public function saveGroupItem(GroupItem $groupItemManager): ?int
    {
        $this->entityManager->persist($groupItemManager);
        $this->entityManager->flush();

        return $groupItemManager->getId();
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
