<?php

namespace App\Discord\Commands;

use App\Discord\MessageHandler;
use App\Models\WerewolfRole;
use Discord\Discord;
use Discord\Parts\User\Member;

class EndCommand extends DiscordCommand
{
    private MessageHandler $message;
    private Discord $bot;

    public function __construct(MessageHandler $message, Discord $bot)
    {
        parent::__construct($bot);

        $this->message = $message;
        $this->bot = $bot;
    }

    public function handle()
    {
        /** @var Member $member */
        foreach ($this->guild->members as $member) {

            /** @var WerewolfRole $role */
            foreach ($this->createRoles->getAll() as $role) {
                $member->removeRole($role->getRoleId());
            }
        }

        $this->message->delete();

        echo "Removed Roles", PHP_EOL;
    }
}
