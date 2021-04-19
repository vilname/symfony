<?php

namespace App\DTO;

use App\Entity\Appertice;
use App\Entity\Group;
use App\Entity\GroupItem;
use App\Entity\Skill;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GroupItemDTO extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public Appertice $appertice;
    public Group $groupId;
    public Skill $skill;
    public Teacher $teacher;

    /**
     * @throws JsonException
     */
    public function __construct()
    {
        // $this->entityManager = $entityManager;
    }

    /**
     * @throws JsonException
     */
    public function getData(Request $request): GroupItem
    {
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        // $this->entityManager = $this->getDoctrine()->getManager();

        $groupItemEntitny = new GroupItem();

        $this->appertice = $this->entityManager->getRepository(Appertice::class)->find($request->request->get('appertice'));
        $this->groupId = $this->entityManager->getRepository(Group::class)->find($request->request->get('group_id'));
        $this->skill = $this->entityManager->getRepository(Skill::class)->find($request->request->get('skill'));
        $this->teacher = $this->entityManager->getRepository(Teacher::class)->find($request->request->get('teacher'));

        $groupItemEntitny->setAppertice($this->appertice);
        $groupItemEntitny->setGroupId($this->groupId);
        $groupItemEntitny->setSkill($this->skill);
        $groupItemEntitny->setTeacher($this->teacher);

        return $groupItemEntitny;
    }
}
