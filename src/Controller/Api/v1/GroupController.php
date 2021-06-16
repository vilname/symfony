<?php

namespace App\Controller\Api\v1;


use App\Service\GroupService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/** @Route("/api/v1/group") */
class GroupController
{
    private GroupService $groupService;
    private Environment $twig;

    public function __construct(GroupService $groupService, Environment $twig)
    {
        $this->groupService = $groupService;
        $this->twig = $twig;
    }

    /**
    * @Route("/form", methods={"POST"})
    */
    public function saveFormAction(Request $request): Response
    {
        $form = $this->groupService->getSaveForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $group = $this->groupService->getEntityField($form);
            $groupId = $this->groupService->saveGroup($group);

            [$data, $code] = ($groupId === null) ? [['success' => false], 400] : [['id' => $groupId], 200];

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
        $form = $this->groupService->getSaveForm();
        $content = $this->twig->render('group.twig', [
            'form' => $form->createView()
        ]);

        return new Response($content);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function getGroupAction(Request $request): Response
    {
        $page = $request->query->get('page');
        $perPage = $request->query->get('perPage');

        $group = $this->groupService->getGroup($page ?? 0, $perPage ?? 20);
        $code = empty($group) ? 204 : 200;

        return new JsonResponse(
            ['group_item' => array_map(
                static fn(Group $group) => $group->toArray(), $group)
            ], $code
        );
    }

    /**
     * @Route("/form/{id}", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function updateFormAction(int $id): Response
    {
        $form = $this->groupService->getUpdateForm($id);
        if ($form === null) {
            return new JsonResponse(['message' => "User with ID $id not found"], 404);
        }
        $content = $this->twig->render('group.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }

    /**
    * @Route("/form/{id}", methods={"PATCH"}, requirements={"id":"\d+"})
    */
    public function updateFormeAction(Request $request, int $id)
    {
        $form = $this->groupService->getUpdateForm($id);
        if ($form === null) {
            return new JsonResponse(['message' => "User with ID $id not found"], 404);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group = $this->groupService->getEntityField($form);
            $groupId = $this->groupService->updateGroup($id, $group);

            [$data, $code] = ($groupId === null) ? [['success' => false], 400] : [['id' => $groupId], 200];

            return new JsonResponse($data, $code);
        } else {
            return new JsonResponse($form->getErrors()[0]->getMessage());
        }
    }
}
