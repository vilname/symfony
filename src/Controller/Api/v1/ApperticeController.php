<?php

namespace App\Controller\Api\v1;

use App\DTO\ApperticeDTO;
use App\Entity\Appertice;
use App\Service\ApperticeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/v1/appertice") */
class ApperticeController
{
    private ApperticeService $apperticeService;
    private Environment $twig;

    public function __construct(ApperticeService $apperticeService, Environment $twig)
    {
        $this->apperticeService = $apperticeService;
        $this->twig = $twig;
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @throws JsonException
     */
    public function saveApperticeAction(Request $request): Response
    {
        $apperticeEntity = new Appertice();

        $apperticeEntity->setName($request->request->get('name'));
        $apperticeId = $this->apperticeService->saveAppertice($apperticeEntity);

        [$data, $code] = $apperticeId === null ?
            [['success' => false], 400] :
            [['success' => true], 200];

        return new JsonResponse($data, $code);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getApperticeAction(Request $request): Response
    {
        $page = $request->query->get('page');
        $perPage = $request->query->get('perPage');

        $appertices = $this->apperticeService->getAppertices($page ?? 0, $perPage ?? 20);
        $code = empty($appertices) ? 204 : 200;

        return new JsonResponse(
            ['appertice' => array_map(
                static fn(Appertice $appertice) => $appertice->toArray(), $appertices)
            ], $code
        );
    }

    /**
     * @Route("", methods={"PATCH"})
     */
    public function updateApperticeAction(Request $request): Response
    {
        $apperticeId = $request->request->get('id');
        $apperticeManager = new Appertice();
        $apperticeManager->setName($request->request->get('name'));
        $result = $this->apperticeService->updateAppertice($apperticeId, $apperticeManager);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }

    /**
    * @Route("/form", methods={"GET"})
    */
    public function getFormAction(): Response
    {
        $form = $this->apperticeService->getSaveForm();
        $content = $this->twig->render('appertice.twig', [
            'form' => $form->createView()
        ]);

        return new Response($content);
    }

    /**
    * @Route("/form", methods={"POST"})
    */
    public function saveFormAction(Request $request): Response
    {
        $form = $this->apperticeService->getSaveForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appertice = new Appertice();
            $groupItemId = $this->apperticeService->saveAppertice($appertice, new ApperticeDTO($form->getData()));

            [$data, $code] = ($groupItemId === null) ? [['success' => false], 400] : [['id' => $groupItemId], 200];

            return new JsonResponse($data, $code);
        } else {
            return new JsonResponse($form->getErrors()[0]->getMessage());
        }
    }

    /**
    * @Route("/form/{id}", methods={"GET"}, requirements={"id":"\d+"})
    */
    public function updateFormAction(int $id): Response
    {
        $form = $this->apperticeService->getUpdateForm($id);
        if ($form === null) {
            return new JsonResponse(['message' => "User with ID $id not found"], 404);
        }
        $content = $this->twig->render('appertice.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }

    /**
    * @Route("/form/{id}", methods={"PATCH"}, requirements={"id":"\d+"})
    */
    public function updateFormeAction(Request $request, int $id)
    {
        $form = $this->apperticeService->getUpdateForm($id);
        if ($form === null) {
            return new JsonResponse(['message' => "User with ID $id not found"], 404);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appertice = $this->apperticeService->getEntity($id);

            $groupItemId = $this->apperticeService->saveAppertice($appertice, $form->getData());

            [$data, $code] = ($groupItemId === null) ? [['success' => false], 400] : [['id' => $groupItemId], 200];

            return new JsonResponse($data, $code);
        } else {
            return new JsonResponse($form->getErrors()[0]->getMessage());
        }
    }
}
