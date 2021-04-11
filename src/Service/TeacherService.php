<?php


namespace App\Service;


use App\Entity\Teacher;
use App\Repository\TeacherRepository;
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
        /** @var TeacherRepository $apperticeRepository */
        $apperticeRepository = $this->entityManager->getRepository(Teacher::class);

        return $apperticeRepository->getTeachers(1);
    }

    public function saveTeacher(Teacher $teacher)
    {
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        return $teacher->getId();
    }
}