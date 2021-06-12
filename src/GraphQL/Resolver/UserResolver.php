<?php


namespace App\GraphQL\Resolver;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Resource\ResourceInterface;

class UserResolver implements ResourceInterface
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolve(int $id)
    {
        $user = $this->entityManager->getRepository('App:User')->find($id);

        return $user;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
    }
}