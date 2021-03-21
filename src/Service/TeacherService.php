<?php


namespace App\Service;


use App\Entity\Teachers;
use App\Repository\TeachersRepository;
use Doctrine\ORM\EntityManagerInterface;

class TeacherService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTeachers()
    {
        /** @var TeachersRepository $apperticeRepository */
        $apperticeRepository = $this->entityManager->getRepository(Teachers::class);

        return $apperticeRepository->getTeachers(1);
    }

}