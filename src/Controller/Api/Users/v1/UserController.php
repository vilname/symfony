<?php


namespace App\Controller\Api\Users\v1;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/v1/user") */
class UserController
{

    protected UserService $userService;
    protected Environment $twig;


    public function __construct(
        UserService $userService,
        Environment $twig
    )
    {
        $this->userService = $userService;
        $this->twig = $twig;

    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $users = $this->userService->getUsers($page ?? 0, $perPage ?? 20);
        $code = empty($users) ? 204 : 200;

        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @throws \JsonException
     */
    public function saveUserAction(Request $request): Response
    {
        $userDTO = new UserDTO(
            [
                'login' => $request->request->get('login'),
                'password' => $request->request->get('password'),
                'roles' => $request->request->get('roles'),
            ]
        );

        $userId = $this->userService->saveUser(new User(), $userDTO);

        [$data, $code] = $userId === null ?
            [['success' => false], 400] :
            [['success' => true, 'userId' => $userId], 200];

        return new JsonResponse($data, $code);
    }

    /**
     * @Route("", methods={"PATCH"})
     */
    public function updateUserAction(Request $request): Response
    {
        $userId = $request->request->get('userId');
        $userDTO = new UserDTO(
            [
                'login' => $request->request->get('login'),
                'password' => $request->request->get('password'),
                'roles' => $request->request->get('roles'),
            ]
        );
        $result = $this->userService->updateUser($userId, $userDTO);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }

    /**
     * @Route("/form", methods={"GET"})
     */
    public function getFormAction(): Response
    {
        $form = $this->userService->getSaveForm();
        $content = $this->twig->render('user.twig', [
            'form' => $form->createView()
        ]);

        return new Response($content);
    }

    /**
     * @Route("/form", methods={"POST"})
     */
    public function saveFormAction(Request $request): Response
    {
        $form = $this->userService->getSaveForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $data = $form->getData();
            $data['roles'] = json_encode([$data['roles']]);
            $groupItemId = $this->userService->saveUser($user, new UserDTO($data));
            [$data, $code] = ($groupItemId === null) ? [['success' => false], 400] : [['id' => $groupItemId], 200];

            return new JsonResponse($data, $code);
        } else {
            return new JsonResponse($form->getErrors()[0]->getMessage());
        }
    }

    /**
     * @Route("/search-user-group", methods={"GET"})
     */
    public function findUserGroup(): Response
    {
        $form = $this->userService->getSearchUserGroup();

        $content = $this->twig->render('find-group.twig', [
            'form' => $form->createView(),
            'title' => 'Поиск группы для пользователя'
        ]);

        return new Response($content);
    }

    /**
     * @Route("/search-user-group", methods={"POST"})
     */
    public function findUserGroupResult(Request $request): Response
    {
        $groups = $this->userService->findFreePlaceAppertice($request->request->get('id'));

        return new JsonResponse($groups);

    }

    /**
     * @Route("/user-list", methods={"GET"})
     */
    public function getUserList(): Response
    {
        $this->userService->getGraphQl();
        $content = $this->twig->render('user-list.twig');

        return new Response($content);

    }

    /**
     * @Route("/search-teacher-group", methods={"GET"})
     */
    public function getTeacherGroup(Request $request)
    {
        $form = $this->userService->freeTeacher($request);

        $content = $this->twig->render('find-group.twig', [
            'form' => $form->createView(),
            'title' => 'Свободные учителя'
        ]);

        return new Response($content);
    }
}
