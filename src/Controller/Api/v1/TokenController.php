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
        $form = $this->authService->getSaveForm();
        $form->handleRequest($request);
        $formData = $form->getData();

        $user = $request->getUser() ? $request->getUser() : $formData['login'];
        $password = $request->getPassword() ? $request->getPassword() : $formData['password'];
        if (!$user || !$password) {
            return new JsonResponse(['message' => 'Authorization required'], Response::HTTP_UNAUTHORIZED);
        }
        if (!$this->authService->isCredentialsValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid password or username'], Response::HTTP_FORBIDDEN);
        }

        $token = $this->authService->getToken($user);

        $cookie = new Cookie(
            'Bearer',
            'Bearer '.$token,
            strtotime("+1 day"), '/', null, true, true, false, 'strict');
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->sendHeaders();

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
