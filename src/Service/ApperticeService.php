<?php


namespace App\Service;


use App\Entity\Appertice;
use App\Repository\ApperticeRepository;
use Doctrine\ORM\EntityManagerInterface;

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
}