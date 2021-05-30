<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TeacherDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     */
    public string $name;

    /**
     * @Assert\NotBlank()
     */
    public int $groupCount;

    /**
     * @Assert\NotBlank()
     */
    public array $teacherSkill;

    public int $skillSelect;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->groupCount = $data['groupCount'] ?? 0;
        $this->teacherSkill = $data['teacherSkill'] ?? [];
        $this->skillSelect = $data['skillSelect'] ?? 0;
    }
}