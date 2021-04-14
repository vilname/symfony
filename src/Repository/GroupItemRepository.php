<?php

namespace App\Repository;

use App\Entity\GroupItem;
use Doctrine\ORM\EntityRepository;
// use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupIt|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupIt|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupIt[]    findAll()
 * @method GroupIt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupItemRepository extends EntityRepository
{

    // /**
    //  * @return GroupIt[] Returns an array of GroupIt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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
