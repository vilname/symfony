<?php

namespace App\DTO;

use App\Entity\Appertice;
use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\Teacher;

class GroupItemDTO
{
    public Appertice $appertice;
    public Group $groupId;
    public Skill $skill;
    public Teacher $teacher;

    // public static function getEntity($appertice, $groupId, $skill, $teacher): self
    // {
    //     return new self([
    //         'appertice' => $appertice,
    //         'groupId' => $groupId,
    //         'skill' => $skill,
    //         'teacher' => $teacher,
    //     ]);
    // }
}

