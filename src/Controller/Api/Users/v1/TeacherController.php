<?php

namespace App\Controller\Api\Users\v1;


use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/users/v1/teacher") */
class TeacherController extends UserController
{
    public function __construct(UserService $userService, Environment $twig, LoggerInterface $elasticsearchLogger)
    {
        parent::__construct($userService, $twig, $elasticsearchLogger);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $teachers = $this->userService->getUsers($page ?? 0, $perPage ?? 20, 'ROLE_TEACHER');
        $code = empty($teachers) ? 204 : 200;

        return new JsonResponse(['teachers' => array_map(static fn(User $user) => $user->toArray(), $teachers)], $code);
    }

    /**
     * @Route("/form", methods={"POST"})
     */
    public function saveFormAction(Request $request): Response
    {
        $form = $this->userService->getSaveForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $user = new User();
            $userDTO = new UserDTO($formData);
            $userId = $this->userService->saveUser($user, $userDTO);
            $this->logger->info("User #{$userDTO->login} is saved");
            [$data, $code] = ($userId === null) ? [['success' => false], 400] : [['id' => $userId], 200];

            return new JsonResponse($data, $code);
        } else {
            return new JsonResponse($form->getErrors()[0]->getMessage());
        }
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

}
