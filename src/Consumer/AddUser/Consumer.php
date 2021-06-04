<?php


namespace App\Consumer\AddUser;


use App\Consumer\AddUser\Input\Message;
use App\Entity\Group;
use App\Service\GroupService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Consumer implements ConsumerInterface
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private UserService $userService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (\JsonException $e) {
            return $this->reject($e->getMessage());
        }

        $groupRepository = $this->entityManager->getRepository(Group::class);
        $group = $groupRepository->find($message->getGroupId());
        if (!($group instanceof Group)) {
            return $this->reject(sprintf('Группа с Id %s не была найдена', $message->getGroupId()));
        }

        $this->userService->addUserItem($group, $message->getUserName(), $message->getCount());

        $this->entityManager->clear();
        $this->entityManager->getConnection()->close();

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect messsage: $error";

        return self::MSG_REJECT;
    }
}
