<?php


namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ApperticeRepository extends EntityRepository
{
    // поиск подходящей группы для нового студента
    public function findGroup(int $apperticeId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ap')
            ->from($this->getClassName(), 'ap');
//            ->join('group', 'g')
//            ->where('ap.apperticeId = :apId')
//            ->andwhere('g.groupId.skillId = ap.apperticeId.skillId')
//            ->groupBy('g.groupId')
//            ->setParameter(':apId', $apperticeId);

        return $qb->getQuery()->getResult();
    }
}