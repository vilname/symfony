<?php

namespace App\Controller\Api\v1;

use App\DTO\GroupItemDTO;
use App\Entity\GroupItem;
use App\Service\GroupItemService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/v1/group_item") */
class GroupItemController
{
    private GroupItemService $groupItemService;
    private Environment $twig;

    public function __construct(GroupItemService $groupItemService, Environment $twig)
    {
        $this->groupItemService = $groupItemService;
        $this->twig = $twig;
    }

    // /**
    //  * @Route("", methods={"POST"})
    //  *
    //  * @throws JsonException
    //  */
    // public function saveGroupItemAction(Request $request, GroupItemDTO $groupItemDTO): Response
    // {
    //     // $this->appertice = $this->entityManager->getRepository(Appertice::class)->find($request->request->get('appertice'));
    //     // $this->groupId = $this->entityManager->getRepository(Group::class)->find($request->request->get('group_id'));
    //     // $this->skill = $this->entityManager->getRepository(Skill::class)->find($request->request->get('skill'));
    //     // $this->teacher = $this->entityManager->getRepository(Teacher::class)->find($request->request->get('teacher'));

    //     $groupItemId = $this->groupItemService->getEntityField($request);

    //     [$data, $code] = $groupItemId === null ?
    //         [['success' => false], 400] :
    //         [['success' => true], 200];

    //     return new JsonResponse($data, $code);
    // }

    /**
    * @Route("/form", methods={"POST"})
    */
    public function saveFormAction(Request $request): Response
    {
        $form = $this->groupItemService->getSaveForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupItem = $this->groupItemService->getEntityField($form);
            $groupItemId = $this->groupItemService->saveGroupItem($groupItem);

            [$data, $code] = ($groupItemId === null) ? [['success' => false], 400] : [['id' => $groupItemId], 200];

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
        $form = $this->groupItemService->getSaveForm();
        $content = $this->twig->render('group_item.twig', [
            'form' => $form->createView()
        ]);

        return new Response($content);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getGroupItemAction(Request $request): Response
    {
        $page = $request->query->get('page');
        $perPage = $request->query->get('perPage');

        $groupItems = $this->groupItemService->getGroupItems($page ?? 0, $perPage ?? 20);
        $code = empty($groupItems) ? 204 : 200;

        return new JsonResponse(
            ['group_item' => array_map(
                static fn(GroupItem $groupItem) => $groupItem->toArray(), $groupItems)
            ], $code
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteGroupItemAction(int $id): Response
    {

        $groupItem = $this->groupItemService->findGroupItemById($id);
        if ($groupItem === null) {
            return false;
        }

        $result = $this->groupItemService->deleteGroupItem($groupItem);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }

    /**
     * @Route("", methods={"PATCH"})
     */
    public function updateUserAction(Request $request): Response
    {
        $result = $this->groupItemService->updateGroupItem($request);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }

    /**
     * @Route("/form/{id}", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function updateFormAction(int $id): Response
    {
        $form = $this->groupItemService->getUpdateForm($id);
        if ($form === null) {
            return new JsonResponse(['message' => "User with ID $id not found"], 404);
        }
        $content = $this->twig->render('group_item.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }

    /**
    * @Route("/form/{id}", methods={"PATCH"}, requirements={"id":"\d+"})
    */
    public function updateFormeAction(Request $request, int $id)
    {
        $form = $this->groupItemService->getUpdateForm($id);
        if ($form === null) {
            return new JsonResponse(['message' => "User with ID $id not found"], 404);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupItem = $this->groupItemService->getEntityField($form);
            $groupItemId = $this->groupItemService->updateGroupItem($id, $groupItem);

            [$data, $code] = ($groupItemId === null) ? [['success' => false], 400] : [['id' => $groupItemId], 200];

            return new JsonResponse($data, $code);
        } else {
            return new JsonResponse($form->getErrors()[0]->getMessage());
        }
    }
}
