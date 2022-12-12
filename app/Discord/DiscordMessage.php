<?php

namespace App\Discord;

use Discord\Parts\Channel\Message;
use Discord\Parts\User\User;
use Exception;
use JetBrains\PhpStorm\Pure;

class DiscordMessage implements MessageHandler
{
    private Message $original;

    /**
     * DiscordMessage constructor.
     *
     * @param Message $original The original discord message
     */
    public function __construct(Message $original)
    {
        $this->original = $original;
    }

    /**
     * Delete the message.
     *
     * @throws Exception
     */
    public function delete(): static
    {
        $this->original->channel->messages->delete($this->original);

        return $this;
    }

    /**
     * Reply to the message.
     */
    public function reply(string $message): static
    {
        $this->original->reply($message);

        return $this;
    }

    /**
     * Send a DM to the author of the message.
     */
    public function sendDm(string $message): static
    {
        $this->original->author->user->sendMessage($message);

        return $this;
    }

    /**
     * Get the author of the given message
     */
    #[Pure] public function getAuthor(): DiscordUser
    {
        return new DiscordUser($this->original->author->user);
    }

    /**
     * Get the content of the message.
     */
    public function getContent(): string
    {
        return $this->original->content;
    }

    /**
     * Get the id of the message.
     */
    public function getId(): string
    {
        return $this->original->id;
    }

    /**
     * Get the id of the channel.
     */
    public function getChannelId(): string
    {
        return $this->original->channel_id;
    }

    /**
     * Post a static reply to the channel of the message. The difference between
     * 'reply' and 'staticReply' is, that staticReply does not mention the author
     * of the message.
     * @throws Exception
     */
    public function staticReply(string $message, callable $callable = null): static
    {
        $this->original->channel->sendMessage($message)->then(function ($message) use ($callable) {
            if ($callable) {
                $callable(new DiscordMessage($message));
            }
        });

        return $this;
    }

    public function getOriginal(): Message
    {
        return $this->original;
    }
}
