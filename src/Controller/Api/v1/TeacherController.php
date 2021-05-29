<?php

namespace App\Controller\Api\v1;

use App\Entity\Teacher;
use App\Service\TeacherService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/v1/teacher") */
class TeacherController
{
    private TeacherService $teacherService;
    private Environment $twig;

    public function __construct(TeacherService $teacherService, Environment $twig)
    {
        $this->teacherService = $teacherService;
        $this->twig = $twig;
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @throws JsonException
     */
    public function saveUserAction(Request $request): Response
    {
        $teacherEntity = $this->teacherService->changeDataBeforeSave($request);

        $teacherId = $this->teacherService->saveTeacher($teacherEntity);
        [$data, $code] = $teacherId === null ?
            [['success' => false], 400] :
            [['success' => true, 'userId' => $teacherId], 200];

        return new JsonResponse($data, $code);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $teachers = $this->teacherService->getTeacher($page ?? 0, $perPage ?? 20);
        $code = empty($users) ? 204 : 200;

        return new JsonResponse(['teachers' => array_map(static fn(Teacher $teacher) => $teacher->toArray(), $teachers)], $code);
    }

    /**
     * @Route("", methods={"PATCH"})
     */
    public function updateTeacherAction(Request $request): Response
    {
        $teacherId = $request->request->get('teacherId');
        $result = $this->teacherService->updateTeacher($teacherId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }

    /**
     * @Route("/form", methods={"GET"})
     */
    public function getFormAction(): Response
    {
        $form = $this->teacherService->getSaveForm();
        $content = $this->twig->render('teacher.twig', [
            'form' => $form->createView()
        ]);

        return new Response($content);
    }

}
