<?php

namespace App\Models;

class WerewolfPlayer
{
    public string $username;
    private string $role;

    public function __construct(string $name, string $roleID)
    {
        $this->username = $name;
        $this->role = $roleID;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
