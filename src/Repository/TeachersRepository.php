<?php


namespace App\Repository;

use App\Entity\Teachers;

class TeachersRepository extends EntityRepository
{
    // поиск преподавателя для группы
    public function getTeachers(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('g.id, count(g.groupId.skillId)')
            ->from($this->getClassName(), 't')
            ->join('group', 'g')
            ->where('t.teacherId.skillId = g.groupId.skillId')
            ->groupBy('g.groupId');

        return $qb->getQuery()->getResult();
    }
}