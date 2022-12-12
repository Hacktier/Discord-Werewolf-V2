<?php

namespace App\Discord\Commands;

use App\Discord\MessageHandler;
use Discord\Discord;

class StartRandomCommand extends DiscordCommand
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
        if ($this->voiceChannel->members->count() < 5) {
            $this->message->reply("Sorry Dude, zum spielen brauchst du noch 4 Freunde!");
            return;
        }

        $this->message->delete();

        echo "Start random Werewolf Game", PHP_EOL;

        $roles = $this->createRoles->getRandom($this->voiceChannel->members->count());
        $this->assignRoles($this->message, $roles);
    }
}
