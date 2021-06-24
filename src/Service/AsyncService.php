<?php


namespace App\Service;


use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class AsyncService
{
    public const ADD_USER_GROUP = 'add_user_group';
    public const ADD_USER_SKILL = 'add_user_skill';

    /** @var ProducerInterface[] */
    private array $producers;

    public function __construct()
    {
        $this->producers = [];
    }

    public function registerProducer(string $producerName, ProducerInterface $producer): void
    {
        $this->producers[$producerName] = $producer;
    }

    public function publishMultipleToExchange(
        string $productName,
        array $messages,
        ?string $routingKey = null,
        ?array $additionalProperties = null
    ): int
    {
        $sentCount = 0;
        if (isset($this->producers[$productName])) {
            foreach ($messages as $message) {
                $this->producers[$productName]->publish($message, $routingKey ?? '', $additionalProperties ?? []);
                $sentCount++;
            }

            return $sentCount;
        }

        return $sentCount;
    }

    public function publishToExchange(
        string $productName,
        string $message,
        ?string $routingKey = null,
        ?array $additionalProperties = null
    ): int
    {
        if (isset($this->producers[$productName])) {
            $this->producers[$productName]->publish($message, $routingKey ?? '', $additionalProperties ?? []);
            return true;
        }

        return false;
    }

}
