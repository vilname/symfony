<?php


namespace App\Repository;


use App\Entity\GroupItem;
use Doctrine\ORM\EntityRepository;

class TeacherRepository extends EntityRepository
{
    // поиск преподавателя для группы
    public function findTeachers(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.name')
            ->from($this->getClassName(), 't')
            ->join(GroupItem::class, 'gi', 'with', 't.id = gi.teacherGroupItem')
            ->groupBy('t.name, t.groupCount')
            ->having('COUNT(\'*\') < t.groupCount');

        return $qb->getQuery()->getResult();
    }

    public function getTeacher(int $page, int $perPage): array{
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}
