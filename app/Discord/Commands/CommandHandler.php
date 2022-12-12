<?php

namespace App\Discord\Commands;

use App\Discord\MessageHandler;
use Discord\Discord;
use Illuminate\Support\Collection;

class CommandHandler
{
    private string $prefix;
    private Collection $commands;

    public function __construct(string $prefix, array $commands)
    {
        $this->prefix = $prefix;
        $this->commands = collect($commands);
    }

    public function handle(MessageHandler $message, Discord $bot)
    {
        $command = $this->commands->first(function ($cmd, $key) use ($message) {
            return str_starts_with($message->getContent(), "{$this->prefix} {$key}");
        });

        return $command ? (new $command($message, $bot))->handle() : null;
    }
}
