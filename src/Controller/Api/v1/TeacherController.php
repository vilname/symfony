<?php

namespace App\Controller\Api\v1;

use App\Entity\Teacher;
use App\Service\TeacherService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/teacher") */
class TeacherController 
{
    private TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @throws JsonException
     */
    public function saveUserAction(Request $request): Response
    {
        $teacherManager = new Teacher();
        $teacherManager->setName($request->request->get('name'));
        $teacherManager->setName($request->request->get('group_count'));

        $teacherId = $this->teacherService->saveTeacher($teacherManager);
        [$data, $code] = $teacherId === null ?
            [['success' => false], 400] :
            [['success' => true, 'userId' => $teacherId], 200];

        return new JsonResponse($data, $code);
    }

    // /**
    //  * @Route("", methods={"GET"})
    //  */
    // public function getUsersAction(Request $request): Response
    // {
    //     $perPage = $request->query->get('perPage');
    //     $page = $request->query->get('page');
    //     $users = $this->userService->getUsers($page ?? 0, $perPage ?? 20);
    //     $code = empty($users) ? 204 : 200;

    //     return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    // }

    // /**
    //  * @Route("/{id}", methods={"DELETE"}, requirements={"id":"\d+"})
    //  */
    // public function deleteUserAction(int $id): Response
    // {
    //     $user = $this->userService->findUserById($id);
    //     if (!$this->authorizationChecker->isGranted(UserVoter::DELETE, $user)) {
    //         return new JsonResponse('Access denied', 403);
    //     }
    //     $result = $this->userService->deleteUserById($id);

    //     return new JsonResponse(['success' => $result], $result ? 200 : 404);
    // }

    // /**
    //  * @Route("", methods={"PATCH"})
    //  */
    // public function updateUserAction(Request $request): Response
    // {
    //     $userId = $request->request->get('userId');
    //     $userDTO = new UserDTO(
    //         [
    //             'login' => $request->request->get('login'),
    //             'password' => $request->request->get('password'),
    //             'roles' => $request->request->get('roles'),
    //         ]
    //     );
    //     $result = $this->userService->updateUser($userId, $userDTO);

    //     return new JsonResponse(['success' => $result], $result ? 200 : 404);
    // }

}
