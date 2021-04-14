<?php


namespace App\Repository;



use Doctrine\ORM\EntityRepository;

class SkillRepository extends EntityRepository
{
    public function getSkill(int $page, int $perPage): array{
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}
