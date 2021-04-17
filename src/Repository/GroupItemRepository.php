<?php

namespace App\Repository;

use App\Entity\GroupItem;
use Doctrine\ORM\EntityRepository;
// use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Persistence\ManagerRegistry;


class GroupItemRepository extends EntityRepository
{
    /**
     * @return GroupItem[]
     */
    public function getGroupItems(int $page, int $perPage)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('gi')
            ->from($this->getClassName(), 'gi')
            ->orderBy('gi.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);


        return $qb->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?GroupIt
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
