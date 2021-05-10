<?php

namespace App\DTO;

use App\Entity\Appertice;
use App\Entity\Group;
use App\Entity\GroupItem;
use App\Entity\Skill;
use App\Entity\Teacher;

class GroupItemDTO
{
    public Group $group;
    public Appertice $appertice;
    public Skill $skill;
    public Teacher $teacher;

    public static function getEntity(GroupItem $groupItem): self
    {
        return new self([
            'appertice' => [
                'choices' => [$groupItem->getAppertice()->getName() => $groupItem->getAppertice()->getId()]
            ],
            // 'groupId' => $groupId,
            // 'skill' => $skill,
            // 'teacher' => $teacher,
        ]);
    }


}

