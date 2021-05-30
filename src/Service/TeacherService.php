<?php


namespace App\Service;


use App\DTO\TeacherDTO;
use App\Entity\Skill;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use App\Symfony\Forms\TeacherType;
use App\Symfony\Forms\UserOrganizationType;
use App\Symfony\Helper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class TeacherService
{
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    /**
     * @var SkillService
     */
    private SkillService $skillService;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, SkillService $skillService)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->skillService = $skillService;
    }

    public function findTeachers()
    {
        /** @var TeacherRepository $teacherRepository */
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);

        return $teacherRepository->findTeachers();
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

//    public function updateTeacher(int $teacherId): bool
//    {
//        $teacherRepository = $this->entityManager->getRepository(Teacher::class);
//        $teacher = $teacherRepository->find($teacherId);
//        if ($teacher === null) {
//            return false;
//        }
//
//        return $this->saveTeacher($teacher);
//    }

    public function getSaveForm(): FormInterface
    {
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        $skill = $skillRepository->findAll();

        return $this->formFactory->createBuilder(FormType::class)
            ->add('name', TextType::class)
            ->add('groupCount', IntegerType::class)
            ->add('teacherSkill', CollectionType::class, [
                'entry_type' => TeacherType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add('skillSelect', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => Helper::getChoicesData($skill)
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function saveTeacher(Teacher $teacher, TeacherDTO $teacherDTO): ?int
    {
        $teacher->setName($teacherDTO->name);
        $teacher->setGroupCount($teacherDTO->groupCount);
        $teacher->setSkillCount(5);

        $skillRepository = $this->entityManager->getRepository(Skill::class);
        $skillRepository->findOneBy(['id' => $teacherDTO->skillSelect]);
        $this->entityManager->getEventManager();

        if ($teacherDTO->skillSelect) {
            $teacher->addTeacherSkill($this->skillService->getEntity($teacherDTO->skillSelect));
        }

        if (count($teacherDTO->teacherSkill)) {
            $skill = new Skill();
            foreach ($teacherDTO->teacherSkill as $item) {
                $skill->setSkill($item['skill']);
                $idSkill = $this->skillService->saveSkill($skill);

                $teacher->addTeacherSkill($this->skillService->getEntity($idSkill));
            }
        }

        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        return $teacher->getId();

//        return 1;
    }

    public function getEntity(int $id)
    {
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);
        return $teacherRepository->findOneBy(['id' => $id]);
    }
}