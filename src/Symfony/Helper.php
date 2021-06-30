<?php


namespace App\Symfony;


class Helper
{
    public static function getChoicesData(array $fields)
    {
        $result = [];
        foreach ($fields as $item) {
            $result[$item->getSkill()] = $item->getId();
        }

        return $result;
    }

    public static function getChoicesDataUsers(array $fields)
    {
        $result = [];
        foreach ($fields as $item) {
            $result[$item->getLogin()] = $item->getId();
        }

        return $result;
    }
}