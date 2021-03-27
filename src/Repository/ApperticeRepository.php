<?php


namespace App\Repository;

use App\Entity\GroupItem;
use Doctrine\ORM\EntityRepository;

class ApperticeRepository extends EntityRepository
{
    private $maxApperticeGroup = 1;

    // поиск подходящей группы для нового студента
    public function findGroup(int $apperticeId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ap.skills.skills')
            ->from($this->getClassName(), 'ap');
//            ->join(GroupItem::class, 'gi', 'with', 'ap.apperticeId.skillId = gi.skillId');
//            ->where('ap.id = :id')
//            ->groupBy('gi.apperticeId, gi.groupId')
//            ->having('COUNT(\'*\') < :maxApperticeGroup')
//            ->setParameters(['id' => 1, 'maxApperticeGroup' => $this->maxApperticeGroup]);
//            ->join('group', 'g')
//            ->where('ap.apperticeId = :apId')
//            ->andwhere('g.groupId.skillId = ap.apperticeId.skillId')
//            ->groupBy('g.groupId')
//            ->setParameter(':apId', $apperticeId);

        return $qb->getQuery()->getResult();
    }
}