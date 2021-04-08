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

	public function saveApperticeAction(Request $request): Response
	{

	}

	/**
     * @Route("", methods={"GET"})
     */
	public function getApperticeAction(Request $request): Response
	{
		$page = $request->query->get('page');
		$perPage = $request->query->get('perPage');

		$appertices = $this->apperticeService->getAppertices($page ?? 0, $perPage ?? 20);
		$code = empty($users) ? 204 : 200;

		return new JsonResponse(
			['users' => array_map(
				static fn(Appertice $appertice) => $appertice->toArray(), $appertices)], $code
			);
	}

	/**
     * @Route("/{id}", methods={"DELETE"}, requirements={"id":"\d+"})
     */
	public function deleteUserAction(int $id): Response
	{

	}

	/**
     * @Route("", methods={"PATCH"})
     */
	public function updateUserAction(Request $request): Response
	{

	}
}
