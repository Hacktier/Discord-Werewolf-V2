<?php

namespace App\Discord\Commands;

use App\Discord\MessageHandler;
use App\Models\WerewolfPlayer;
use App\Services\RoleSet;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Illuminate\Support\Collection;
use Exception;


class DiscordCommand
{
    protected Channel $voiceChannel;
    protected Guild $guild;
    protected RoleSet $createRoles;

    public function __construct($bot)
    {
        $this->guild = $bot->guilds->first();
        $this->voiceChannel = $this->guild->channels->find(fn(Channel $channel) => $channel->name === 'Dorf');
        $this->createRoles = new RoleSet();
    }

    protected function assignRoles(MessageHandler $message, Collection $roles)
    {
        echo "Prepare to assign roles.", PHP_EOL;

        $assignedRoles = collect();

        try {
            $loopIndex = 0;

            /**
             * @var int $loopIndex
             * @var Member $voiceMember
             */
            foreach ($this->voiceChannel->members as $voiceMember) {
                $this->guild->members
                    ->fetch($voiceMember->user->id)
                    ->then(function (Member $voiceMember) use ($assignedRoles, &$loopIndex, $roles, $message) {

                        if ($voiceMember->user->id === $message->getAuthor()->getId()) {
                            return;
                        }

                        $voiceMember->addRole($roles[$loopIndex]->getRoleId());

                        $assignedRoles->add(new WerewolfPlayer($voiceMember->user->username, $roles[$loopIndex]->getName()));

                        $voiceMember->user->sendMessage($roles[$loopIndex]->getName());
                        $loopIndex++;
                    });
            }

            /** @var Channel $channelGameMaster */
            $channelGameMaster = $this->guild->channels->find(fn(Channel $channel) => $channel->name === 'spielleiter');

            $message->getOriginal()->author->addRole($this->createRoles->gamemaster()->getRoleId())
                ->then(function () use ($assignedRoles, $channelGameMaster) {
                    $channelGameMaster->sendMessage("\n########## \t **Neues Spiel** \t ##########");

                    /** @var WerewolfPlayer $assignedRole */
                    foreach ($assignedRoles as $assignedRole) {
                        $channelGameMaster->sendMessage($assignedRole->getUsername() . "\t -> \t" . $assignedRole->getRole());
                    }

                });
        } catch (Exception $e) {
            dd($e);
        }

        echo "Roles assigned.", PHP_EOL;
    }

}
