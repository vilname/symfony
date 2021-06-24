<?php


namespace App\Consumer\FindTeacherGroup\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class Message
{
    public static function createFromQueue(string $messageBody): self{
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);

        $result = new self();
//        $result->page = $message['page'];
//        $result->perPage = $message['perPage'];

        return $result;
    }
}