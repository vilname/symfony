<?php
namespace App\Symfony\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ApperticeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('id', HiddenType::class);
    }

    public static function getChoicesData(array $skill) 
    {
        $result = [];
        foreach ($skill as $item) {
            $result[$item->getSkill()] = $item->getId();
        }

        return $result;
    }
}

