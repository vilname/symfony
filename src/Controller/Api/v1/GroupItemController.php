<?php

namespace App\Controller\Api\v1;

use App\DTO\GroupItemDTO;
use App\Entity\GroupItem;
use App\Service\GroupItemService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/group_item") */
class GroupItemController
{
	private GroupItemService $groupItemService;

	public function __construct(GroupItemService $groupItemService)
	{
		$this->groupItemService = $groupItemService;
	}

	/**
	 * @Route("", methods={"POST"})
	 * 
	 * @throws JsonException
	 */
	public function saveGroupItemAction(Request $request): Response
	{
			$groupItemEntitny = (new GroupItemDTO($request))->getData();
			$apperticeId = $this->groupItemService->saveGroupItem($groupItemEntitny);

			[$data, $code] = $apperticeId === null ?
			[['success' => false], 400] :
			[['success' => true], 200];

			return new JsonResponse($data, $code);
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
}
