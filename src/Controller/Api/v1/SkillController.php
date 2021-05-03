<?php

namespace App\Controller\Api\v1;

use App\Entity\Skill;
use App\Service\SkillService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/skill") */
class SkillController
{
    private SkillService $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @throws JsonException
     */
    public function saveUserAction(Request $request): Response
    {
        $skillEntitny = $this->skillService->changeDataBeforeSave($request);
        $skillId = $this->teacherService->saveTeacher($skillEntitny);
        [$data, $code] = $skillId === null ?
            [['success' => false], 400] :
            [['success' => true, 'userId' => $skillId], 200];

        return new JsonResponse($data, $code);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $skills = $this->skillService->getTeacher($page ?? 0, $perPage ?? 20);
        $code = empty($users) ? 204 : 200;

        return new JsonResponse(['skills' => array_map(static fn(Skill $skill) => $skill->toArray(), $skills)], $code);
    }

    /**
     * @Route("", methods={"PATCH"})
     */
    public function updateUserAction(Request $request): Response
    {
        $skillId = $request->request->get('skillId');
        $result = $this->skillService->updateSkill($skillId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }

}
