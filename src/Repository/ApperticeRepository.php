<?php


namespace App\Repository;

use App\Entity\GroupItem;
use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;

class ApperticeRepository extends EntityRepository
{

    // поиск подходящей группы по навыкам студента
    public function findGroup(int $apperticeId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('g.id')
            ->from($this->getClassName(), 'ap')
            ->join('ap.apperticeSkill', 's')
            ->join('App\Entity\GroupItem', 'gi', 'with', 'gi.skillGroupItem = s.id')
            ->join('gi.groupId', 'g')
            ->where('ap.id = :id')
            ->andWhere('g.active = true')
            ->setParameter('id', $apperticeId)
            ->groupBy('g.id');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Appertice[]
     */
    public function getAppertices(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('ap')
            ->from($this->getClassName(), 'ap')
            ->orderBy('ap.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);


        return $qb->getQuery()->getResult();
    }
}