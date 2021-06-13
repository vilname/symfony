<?php


namespace App\Consumer\AddUserSkill;


use App\Consumer\AddUserSkill\Input\Message;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer implements ConsumerInterface
{
    private const CACHE_TAG = 'user_skill';

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private UserService $userService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserService $userService
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * @throws \JsonException
     */
    public function execute(AMQPMessage $msg): int
    {
        dump($msg->getBody());

        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (\JsonException $e) {
            return $this->reject($e->getMessage());
        }


        $this->userService->saveUserRundomSkill($message->getUserName(), $message->getCount());

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