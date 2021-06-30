<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUsers(int $page, int $perPage, $roles = null): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from($this->getClassName(), 'u')
            ->where('u.roles like :roles')->setParameter('roles', "%$roles%")
            ->orderBy('u.id', 'ASC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->enableResultCache(null, "user_{$page}_{$perPage}")->getResult();
    }


    public function findUsersGroup(array $skills)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = sprintf('SELECT group_id, count(gs.skill_id) as cnt FROM public.group_skill gs
            WHERE gs.skill_id IN (%s) GROUP BY gs.group_id ORDER BY cnt DESC' , implode(',', $skills));

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }


    public function findUserNotGroup($role)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM \"user\" WHERE id NOT IN (SELECT user_id FROM public.group_user) AND roles LIKE '%$role%'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = [];
        while ($field = $stmt->fetch()) {
            $result[] = User::setMap($field);
        }

        return $result;
    }

}
