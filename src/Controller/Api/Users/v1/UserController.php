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
     * @Route("/save-user-group", methods={"GET"})
     */
    public function saveUserGroup(Request $request): Response
    {

        $page = $request->query->get('page');
        $perPage = $request->query->get('perPage');
        $users = $this->userService->saveUserGroup($page ?? 0, $perPage ?? 20);

        [$data, $code] = ($users === null) ? [['success' => false], 400] : [['users' => $users], 200];

        return new JsonResponse($data, $code);
    }
}
