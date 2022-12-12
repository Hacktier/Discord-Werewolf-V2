<?php

namespace App\Discord;

use Discord\Parts\Channel\Message;
use Discord\Parts\User\User;

interface MessageHandler
{
    /**
     * Delete the message.
     */
    public function delete(): static;

    /**
     * Reply to the message.
     */
    public function reply(string $message): static;

    /**
     * Post a static reply to the channel of the message. The difference between
     * 'reply' and 'staticReply' is, that staticReply does not mention the author
     * of the message.
     * @param callable|null $callable A optional callable that will receive the sent message
     */
    public function staticReply(string $message, callable $callable = null): static;

    /**
     * Send a DM to the author of the message.
     */
    public function sendDm(string $message): static;

    /**
     * Get the author of the given message.
     */
    public function getAuthor(): DiscordUser;

    /**
     * Get the content of the message.
     */
    public function getContent(): string;

    /**
     * Get the id of the message.
     */
    public function getId(): string;

    /**
     * Get the id of the channel.
     */
    public function getChannelId(): string;

    /**
     * Get the original message.
     */
    public function getOriginal(): Message;
}
