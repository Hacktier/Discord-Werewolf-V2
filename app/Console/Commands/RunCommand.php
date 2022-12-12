<?php

namespace App\Console\Commands;

use App\Discord\Commands\CommandHandler;
use App\Discord\Commands\EndCommand;
use App\Discord\Commands\InfoCommand;
use App\Discord\Commands\StartNormalCommand;
use App\Discord\Commands\StartRandomCommand;
use App\Discord\DiscordMessage;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Illuminate\Console\Command;


class RunCommand extends Command
{
    protected $signature = 'run';
    protected $description = 'Start and run the discord bot';
    private CommandHandler $commandHandler;

    public function __construct()
    {
        parent::__construct();

        $this->commandHandler = new CommandHandler('!ww', [
            'start' => StartNormalCommand::class,
            'random' => StartRandomCommand::class,
            'end' => EndCommand::class,
            'info' => InfoCommand::class,
        ]);
    }

    /**
     * @throws IntentException
     */
    public function handle()
    {
        $bot = new Discord([
            'token' => config('services.discord.token'),
            'intents' => Intents::getAllIntents(),
            'loadAllMembers' => true

        ]);

        $bot->on('ready', function (Discord $discord) use ($bot) {
            $discord->on('message', function (Message $message) use ($bot) {
                $this->commandHandler->handle(new DiscordMessage($message), $bot);
            });
        });

        $bot->run();
    }
}
