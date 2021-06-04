<?php


namespace App\Controller\Api\v1;

use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


/**
 * @Route("/api/v1/token")
 */
class TokenController
{
    private AuthService $authService;
    private Environment $twig;

    public function __construct(AuthService $authService, Environment $twig)
    {
        $this->authService = $authService;
        $this->twig = $twig;
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function getTokenAction(Request $request): Response
    {
        $user = $request->getUser();
        $password = $request->getPassword();

        if (!$user || !$password) {
            return new JsonResponse(['message' => 'Authorization required'], Response::HTTP_UNAUTHORIZED);
        }
        if (!$this->authService->isCredentialsValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid password or username'], Response::HTTP_FORBIDDEN);
        }

        $token = $this->authService->getToken($user);

        return new JsonResponse(['token' => $token]);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getTokenFormAction(Request $request): Response
    {
        $form = $this->authService->getSaveForm();
        $content = $this->twig->render('auth.twig', [
            'form' => $form->createView()
        ]);

        return new Response($content);
    }
}
