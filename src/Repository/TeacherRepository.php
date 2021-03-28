<?php


namespace App\Repository;


use App\Entity\GroupItem;
use Doctrine\ORM\EntityRepository;

class TeacherRepository extends EntityRepository
{
    // поиск преподавателя для группы
    public function getTeachers(int $page): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.name')
            ->from($this->getClassName(), 't')
            ->join(GroupItem::class, 'gi', 'with', 't.id = gi.teacherGroupItem')
            ->groupBy('t.name, t.groupCount')
            ->having('COUNT(\'*\') < t.groupCount');

        return $qb->getQuery()->getResult();

    }

}
