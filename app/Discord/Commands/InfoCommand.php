<?php

namespace App\Discord\Commands;

use App\Discord\MessageHandler;
use Discord\Discord;

class InfoCommand extends DiscordCommand
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
        $this->message->reply(
            "```Werwolf Bot Anleitung\n
            \n!ww info       ->  Informationen zum Bot
            \n!ww start      ->  Startet das normales Spiel
            \n!ww random     ->  Startet ein Spiel mit einer zufälligen Anzahl von Werwölfen
            \n!ww end        ->  Beendet das Spiel und entfernt alle Rollen```"
        );
    }
}
