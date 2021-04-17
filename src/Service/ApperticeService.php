<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Appertice;
use App\Repository\ApperticeRepository;

class ApperticeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

    public function saveAppertice(Appertice $appertice): ?int
    {
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
}
