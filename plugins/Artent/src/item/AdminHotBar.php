<?php

namespace Artent\item;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;

class AdminHotBar extends PlayerHotBar
{
    /**
     * @return CustomItem[]
     */
    public function getItems(): array
    {
        $playerItems = parent::getItems();

        return array_merge($playerItems, [
            new CustomItem('§r§c|§f режим редактирования карт §c|§r', VanillaItems::IRON_SHOVEL(), 8),
            new CustomItem('§r§c|§f портал §c|§r', VanillaBlocks::NETHER_PORTAL()->asItem(), 1)
        ]);
    }
}