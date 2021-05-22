<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\ORM\EntityRepository;

// use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Persistence\ManagerRegistry;


class GroupRepository extends EntityRepository
{
    /**
     * @return Group[]
     */
    public function getGroup(int $page, int $perPage)
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
