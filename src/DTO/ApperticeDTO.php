<?php

namespace App\DTO;

use App\Entity\Appertice;
use Symfony\Component\Validator\Constraints as Assert;

class ApperticeDTO
{
    /**
    * @Assert\NotBlank()
    * @Assert\Length(max=32)
    */
    public string $name;

    public int $apperticeSkill;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->apperticeSkill = $data['apperticeSkill'] ?? 0;
    }

    public static function fromEntity(Appertice $appertice): self
    {
        return new self([
            'name' => $appertice->getName(),
        ]);
    }
}

