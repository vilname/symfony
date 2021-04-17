<?php


namespace App\Service;


use App\Entity\Skill;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SkillService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveSkill(Skill $skill)
    {
        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $skill->getId();
    }

    /**
     * @return Skill[]
     */
    public function getSkill(int $page, int $perPage): array
    {
        /** @var SkillRepository $skillRepository */
        $skillRepositury = $this->entityManager->getRepository(Skill::class);
        return $skillRepositury->getSkill($page, $perPage);
    }

    public function changeDataBeforeSave(Request $request)
    {
        $skillEntitny = new Skill();
        $skillEntitny->setSkill($request->request->get('skill'));

        return $skillEntitny;
    }

    public function findSkillById(int $skillId): ?Skill
    {
        $skillRepositury = $this->entityManager->getRepository(Skill::class);
        return $skillRepositury->find($skillId);
    }

    public function deleteSkill(Skill $teacher): bool
    {
        $this->entityManager->remove($teacher);
        $this->entityManager->flush();

        return true;
    }

    public function updateSkill(int $skillId): bool
    {
        $skillRepositury = $this->entityManager->getRepository(Skill::class);
        $skill = $skillRepositury->find($skillId);
        if ($skill === null) {
            return false;
        }

        return $this->saveSkill($skill);
    }
}