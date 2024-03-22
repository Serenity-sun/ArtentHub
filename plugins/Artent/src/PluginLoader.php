<?php

namespace Artent;

use pocketmine\plugin\PluginBase;

class PluginLoader extends PluginBase
{
    private static PluginLoader $pluginInstance;

    public function onEnable(): void
    {
        self::$pluginInstance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new BehaviourListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new InventoryListener(), $this);
    }

    /**
     * @return self
     */
    public static function getPluginInstance(): self
    {
        return self::$pluginInstance;
    }
}