<?php


namespace App\Service;

use App\DTO\ApperticeDTO;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Appertice;
use App\Entity\Skill;
use App\Repository\ApperticeRepository;
use App\Symfony\Forms\ApperticeType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ApperticeService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
    * @var FormInterface
    */
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function findPlace()
    {
        /** @var ApperticeRepository $apperticeRepository */
        $apperticeRepository = $this->entityManager->getRepository(Appertice::class);

        return $apperticeRepository->findGroup(1);
    }

    public function getAppertices(int $page, int $perPage): array
    {
        $apperticeRepository = $this->entityManager->getRepository(Appertice::class);

        return $apperticeRepository->getAppertices($page, $perPage);
    }

    public function saveAppertice(Appertice $appertice, ApperticeDTO $apperticeDTO): ?int
    {
        $appertice->setName($apperticeDTO->name);
        $appertice->setCreatedAt(new DateTime());
        $appertice->setUpdatedAt(new DateTime());
        if ($apperticeDTO->apperticeSkill) {
            $apperticeRepository = $this->entityManager->getRepository(Skill::class);
            $appertice->addApperticeSkill($apperticeRepository->find($apperticeDTO->apperticeSkill));
        }

        $this->entityManager->persist($appertice);
        $this->entityManager->flush();

        return $appertice->getId();
    }

    public function updateAppertice(int $apperticeId, Appertice $apperticeItem) 
    {
        $apperticeRepository = $this->entityManager->getRepository(Appertice::class);
        $appertice = $apperticeRepository->find($apperticeId);
        
        if ($appertice === null) {
            return false;
        }
        $appertice->setName($apperticeItem->getName());

        return $this->saveAppertice($appertice);
    }

    public function findApperticeById(int $apperticeId): ?Appertice
    {
        $apperticeRepository = $this->entityManager->getRepository(Appertice::class);


        return $apperticeRepository->find($apperticeId);
    }

    public function deleteAppertice(Appertice $appertice): bool
    {
        $this->entityManager->remove($appertice);
        $this->entityManager->flush();

        return true;
    }

    public function getSaveForm(): FormInterface
    {
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        $skill = $skillRepository->findAll();

        return $this->formFactory->createBuilder(FormType::class)
            ->add('name', TextType::class)
            ->add('apperticeSkill', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => ApperticeType::getChoicesData($skill)
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function getUpdateForm(int $id): FormInterface
    {
        $appertice = $this->getEntity($id);

        $skillRepository = $this->entityManager->getRepository(Skill::class);
        $skillElement = $skillRepository->find($appertice->getApperticeSkill()->getValues()[0]->getId());
        $skill = $skillRepository->findAll();

        if ($skillElement === null) {
            return null;
        }

        return $this->formFactory->createBuilder(FormType::class, ApperticeDTO::fromEntity($appertice))
            ->add('name', TextType::class)
            ->add('apperticeSkill', ChoiceType::class, [
                'placeholder'  =>  'Выберите вариант',
                'choices' => ApperticeType::getChoicesData($skill),
                'choice_attr' => [
                    $skillElement->getSkill() => ['selected' => true]
                ],
            ])
            ->add('submit', SubmitType::class)
            ->setMethod('PATCH')
            ->getForm();
    }

    public function getEntity(int $id)
    {
        $apperticeRepository = $this->entityManager->getRepository(Appertice::class);
        return $apperticeRepository->find($id);
    }
}
