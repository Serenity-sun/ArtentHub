<?php

namespace Artent\config;

use Artent\PluginLoader;
use pocketmine\utils\Config as BaseConfig;
use pocketmine\utils\SingletonTrait;

class Config
{
    use SingletonTrait;

    private Bedwars $bedwars;

    private function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $bedwars = $this->getBaseConfig()->get('bedwars');

        $this->bedwars = new Bedwars(
            new TransferPortal(
                $bedwars['transferPortal']['minX'],
                $bedwars['transferPortal']['minY'],
                $bedwars['transferPortal']['minZ'],
                $bedwars['transferPortal']['maxX'],
                $bedwars['transferPortal']['maxY'],
                $bedwars['transferPortal']['maxZ']
            ),
            new TransferAddress(
                $bedwars['transferAddress']['ip'],
                $bedwars['transferAddress']['port']
            )
        );
    }

    /**
     * @return BaseConfig
     */
    private function getBaseConfig(): BaseConfig
    {
        return new BaseConfig(
            PluginLoader::getPluginInstance()->getResourceFolder() . 'config.yml',
            BaseConfig::YAML
        );
    }

    /**
     * @return Bedwars
     */
    public function getBedwars(): Bedwars
    {
        return $this->bedwars;
    }
}