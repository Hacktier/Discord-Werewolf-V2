<?php

namespace App\Models;

class WerewolfRole
{
    private string $name;
    private int $roleId;

    public function __construct(string $name, int $roleID)
    {
        $this->name = $name;
        $this->roleId = $roleID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }
}
