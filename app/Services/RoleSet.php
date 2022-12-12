<?php

namespace App\Services;

use App\Models\WerewolfRole;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class RoleSet
{
    public function getNormal(int $playerCount): Collection
    {
        $roles = new Collection();
        $playerCount--;

        for ($i = 0; $i < floor($playerCount / 3) - 1; $i++) {
            $random = rand(0,100);

            if ($random < 25) {
                $roles->add($this->babyWerewolf());
                continue;
            }
            if($random < 50){
                $roles->add($this->wolfseher());
                continue;
            }

            $roles->add($this->werewolf());
        }

        $this->getRandom($playerCount - $roles->count(), false)->each(fn(WerewolfRole $item) => $roles->add($item));

        return $roles->shuffle();
    }

    public function getRandom(int $playerCount, bool $randomWerewolf = true): Collection
    {
        $allRoles = $this->getOtherRoles();

        if ($randomWerewolf) {
            $allRoles->add($this->werewolf());
            $allRoles->add($this->babyWerewolf());
            $allRoles->add($this->wolfseher());
        }

        $roles = new Collection();
        $random = rand(0,100);

        dump($random);

        if ($random < 25) {
            $roles->add($this->babyWerewolf());
        }
        if($random > 24 && $random < 50){
            $roles->add($this->wolfseher());
        }
        if ($random > 49) {
            $roles->add($this->werewolf());
        }

        while ($roles->count() < $playerCount) {
            /** @var WerewolfRole $randomRole */
            $randomRole = $allRoles->random();

            $roles->add($randomRole);

            if ($randomRole->getName() !== "Dorfbewohner") {
                $allRoles->filter(function (WerewolfRole $value, int $key) use ($allRoles, $randomRole) {
                    if ($value->getName() === $randomRole->getName()) {
                        $allRoles->forget($key);
                    }
                });
            }
        }

        return $roles->shuffle();
    }

    public function getAll(): Collection
    {
        $roles = $this->getOtherRoles();
        $roles->add($this->gamemaster());
        $roles->add($this->dead());
        $roles->add($this->werewolf());
        $roles->add($this->wolfseher());
        $roles->add($this->babyWerewolf());

        return $roles;
    }

    private function getOtherRoles(): Collection
    {
        return new Collection([
            new WerewolfRole("Jäger", '746731251389431960'),
            new WerewolfRole("Blinzelmädchen", '746731441433608252'),
            new WerewolfRole("Hexe", '746731417068765196'),
            new WerewolfRole("Seherin", '746731253935636582'),
            new WerewolfRole("Amor", '746731370876895262'),
            new WerewolfRole("Dorfbewohner", '746402912372129863'),
            new WerewolfRole("Doktor", '901110012573806684'),
            new WerewolfRole("Leibwächter", '901110176264884355'),
            new WerewolfRole("Schütze", '901110223358529587'),
            new WerewolfRole("Dorfmatratze", '901110327121436672'),
            new WerewolfRole("Priester", '901110375444017294'),
//            new WerewolfRole("Auraseher", '901110418641154139'),
            new WerewolfRole("Beschwörer", '901110480825884792'),
            new WerewolfRole("Verfluchter", '901110625328058469'),
            new WerewolfRole("Narr", '901152767475871784'),
        ]);
    }

    #[Pure] public function gamemaster(): WerewolfRole
    {
        return new WerewolfRole("Spielleiter", '746734565690835014');
    }

    #[Pure] public function dead(): WerewolfRole
    {
        return new WerewolfRole("Toter", '746750718119247969');
    }

    #[Pure] private function werewolf(): WerewolfRole
    {
        return new WerewolfRole("Werwolf", '746402015944638525');
    }

    #[Pure] private function babyWerewolf(): WerewolfRole
    {
        return new WerewolfRole("Baby Werwolf", '901110656701452368');
    }

    #[Pure] private function wolfseher(): WerewolfRole
    {
        return new WerewolfRole("Wolfseher", '901110706215194704');
    }
}
