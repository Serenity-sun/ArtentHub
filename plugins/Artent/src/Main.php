<?php

namespace Artent;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public function onEnable(): void
    {
        $this->getLogger()->info("Artent enabled!");
    }
}