<?php

namespace App\DTO;

use App\Entity\Appertice;
use App\Entity\Group;
use App\Entity\GroupItem;
use App\Entity\Skill;
use App\Entity\Teacher;

class GroupItemDTO
{
    public Appertice $appertice;
    public Group $groupId;
    public Skill $skill;
    public Teacher $teacher;

    public static function formEntity(GroupItem $groupItem): self
    {
        return new self([
            'appertice' => array_map(
                static function (Appertice $appertice) {
                    return ['id' => $appertice->getId(), 'name' => $appertice->getName()];
                },
                $groupItem->getAppertice()->getValues()
            ),
            // 'groupId' => $groupId,
            // 'skill' => $skill,
            // 'teacher' => $teacher,
        ]);
    }
}

