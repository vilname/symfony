<?php

namespace App\Symfony\Forms;

use App\Entity\GroupItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupItemType extends AbstractType
{
    public static function getChoicesData(array $groupItem) 
    {
        $result = [];
        foreach ($groupItem as $item) {
            $result['group'][$item->getGroupId()->getName()] = $item->getGroupId()->getId();
            $result['appertice'][$item->getAppertice()->getName()] = $item->getAppertice()->getId();
            $result['skill'][$item->getSkill()->getSkill()] = $item->getSkill()->getId();
            $result['teacher'][$item->getTeacher()->getName()] = $item->getTeacher()->getId();
        }

        return $result;
    }
}

