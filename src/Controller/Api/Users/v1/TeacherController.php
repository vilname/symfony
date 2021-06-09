<?php

namespace App\Controller\Api\Users\v1;

use App\DTO\TeacherDTO;
use App\DTO\UserDTO;
use App\Entity\Teacher;
use App\Entity\User;
use App\Service\TeacherService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/users/v1/teacher") */
class TeacherController extends UserController
{
    public function __construct(UserService $userService, Environment $twig)
    {
        parent::__construct($userService, $twig);
    }


//    /**
//     * @Route("", methods={"POST"})
//     *
//     * @throws JsonException
//     */
//    public function saveUserAction(Request $request): Response
//    {
//        $teacherEntity = $this->teacherService->changeDataBeforeSave($request);
//
//        $teacherId = $this->teacherService->saveTeacher($teacherEntity);
//        [$data, $code] = $teacherId === null ?
//            [['success' => false], 400] :
//            [['success' => true, 'userId' => $teacherId], 200];
//
//        return new JsonResponse($data, $code);
//    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $teachers = $this->userService->getUsers($page ?? 0, $perPage ?? 20);
        $code = empty($users) ? 204 : 200;

        return new JsonResponse(['teachers' => array_map(static fn(User $user) => $user->toArray(), $teachers)], $code);
    }
//
//    /**
//     * @Route("", methods={"PATCH"})
//     */
//    public function updateTeacherAction(Request $request): Response
//    {
//        $teacherId = $request->request->get('teacherId');
//        $result = $this->teacherService->updateTeacher($teacherId);
//
//        return new JsonResponse(['success' => $result], $result ? 200 : 404);
//    }




}
