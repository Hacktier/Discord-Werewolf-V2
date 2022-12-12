<?php

namespace App\Discord;

use Discord\Parts\User\User;

class DiscordUser
{
    private User $original;

    public function __construct(User $original)
    {
        $this->original = $original;
    }

    public function getId(): string
    {
        return $this->original->id;
    }

    public function getUsername(): string
    {
        return $this->original->username;
    }

    public function original(): User
    {
        return $this->original;
    }
}
