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
}