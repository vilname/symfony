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
}