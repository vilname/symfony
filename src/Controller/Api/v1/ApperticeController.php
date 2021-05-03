<?php

namespace App\Controller\Api\v1;

use App\Entity\Appertice;
use App\Service\ApperticeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/appertice") */
class ApperticeController
{
    private ApperticeService $apperticeService;

    public function __construct(ApperticeService $apperticeService)
    {
        $this->apperticeService = $apperticeService;
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
}
