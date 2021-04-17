<?php


namespace App\Service;


use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TeacherService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findTeachers()
    {
        /** @var TeacherRepository $teacherRepository */
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);

        return $teacherRepository->findTeachers();
    }

    public function saveTeacher(Teacher $teacher)
    {
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        return $teacher->getId();
    }

    /**
     * @return Teacher[]
     */
    public function getTeacher(int $page, int $perPage): array
    {
        /** @var TeacherRepository $teacherRepository */
        $teacherRepositury = $this->entityManager->getRepository(Teacher::class);
        return $teacherRepositury->getTeacher($page, $perPage);
    }

    public function changeDataBeforeSave(Request $request)
    {
        $teacherEntitny = new Teacher();
        $teacherEntitny->setName($request->request->get('name'));

        return $teacherEntitny;
    }

    public function findTeacherById(int $teacherId): ?Teacher
    {
        $teacherRepositury = $this->entityManager->getRepository(Teacher::class);
        return $teacherRepositury->find($teacherId);
    }

    public function deleteTeacher(Teacher $teacher): bool
    {
        $this->entityManager->remove($teacher);
        $this->entityManager->flush();

        return true;
    }

    public function updateTeacher(int $teacherId): bool
    {
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);
        $teacher = $teacherRepository->find($teacherId);
        if ($teacher === null) {
            return false;
        }

        return $this->saveTeacher($teacher);
    }
}