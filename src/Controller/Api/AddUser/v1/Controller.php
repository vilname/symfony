<?php


namespace App\Controller\Api\AddUser\v1;

use App\Entity\User;
use App\Service\AsyncService;
use App\Service\GroupService;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    use ControllerTrait;

    private GroupService $groupService;

    private UserService $userService;

    private AsyncService $asyncService;

    public function __construct(
        GroupService $groupService,
        UserService $userService,
        AsyncService $asyncService,
        ViewHandlerInterface $viewHandler
    )
    {
        $this->groupService = $groupService;
        $this->userService = $userService;
        $this->asyncService = $asyncService;
        $this->viewhandler = $viewHandler;
    }

//    /**
//     * @Rest\Post("/api/v1/add-user")
//     *
//     * @RequestParam(name="groupId", requirements="\d+")
//     * @RequestParam(name="userName")
//     * @RequestParam(name="count", requirements="\d+")
//     * @RequestParam(name="async", requirements="0|1")
//     */
//    public function addUserAction(int $groupId, string $userName, int $count, int $async): Response
//    {
//        $group = $this->groupService->findUserById($groupId);
//        if ($group !== null) {
//            if ($async === 0) {
//                $createdAppertice = $this->userService->addUserItem($group, $userName, $count);
//                $view = $this->view(['created' => $createdAppertice], 200);
//            } else {
//                $message = $this->groupService->getApperticesMessages($group, $userName, $count);
//                $result = $this->asyncService->publishMultipleToExchange(AsyncService::ADD_USER, $message);
//                $view = $this->view(['success' => $result], $result ? 200 : 500);
//            }
//        } else {
//            $view = $this->view(['success' => false], 404);
//        }
//
//        return $this->handleView($view);
//    }

    /**
     * @Rest\Post("/api/v1/add-user-skill")
     *
     * @RequestParam(name="userName")
     * @RequestParam(name="count")
     * @RequestParam(name="async", requirements="0|1")
     */
    public function addUsersSkillAction(string $userName, int $count, int $async): Response
    {
        /** @var User $users */
//        $users = $this->userService->saveUserRundomSkill($userName, $count);

//        foreach ($users as $user) {
            if ($userName !== null) {
                if ($async === 0) {
//                $createdAppertice = $this->userService->addUserItem($group, $userName);
//                $view = $this->view(['created' => $createdAppertice], 200);
                } else {
                    $message = $this->userService->getUserMessages(4, $userName);
                    $result = $this->asyncService->publishToExchange(AsyncService::ADD_USER_SKILL, $message);



                    $lastView = $this->view(['success' => $result], $result ? 200 : 500);
                }
            } else {
                $lastView = $this->view(['success' => false], 404);
            }
//        }



        return $this->handleView($lastView);
    }
}
