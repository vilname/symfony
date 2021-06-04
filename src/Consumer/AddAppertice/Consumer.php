<?php


namespace App\Consumer\AddAppertice;


use App\Consumer\AddAppertice\Input\Message;
use App\Entity\Group;
use App\Service\GroupService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Consumer implements ConsumerInterface
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private GroupService $groupService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, GroupService $groupService)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->groupService = $groupService;
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

        $this->groupService->addApperticeItem($group, $message->groupName(), $message->getCount());

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
