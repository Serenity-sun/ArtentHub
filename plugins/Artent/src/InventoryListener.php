<?php

namespace Artent;

use Artent\item\AdminHotBar;
use Artent\item\PlayerHotBar;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;

class InventoryListener implements Listener
{
    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function playerInteract(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        if ($item->isNull()) {
            return;
        }

        $hotbar = new AdminHotBar();
        if (!$this->isHotBarItem($item, $hotbar)) {
            return;
        }

        $this->matchItem($item, $hotbar);
    }

    private function matchItem(Item $item, PlayerHotBar $hotBar): void
    {
    }

    /**
     * @param Item $item
     * @param PlayerHotBar $hotBar
     * @return bool
     */
    private function isHotBarItem(Item $item, PlayerHotBar $hotBar): bool
    {
        $itemTypeId = $item->getTypeId();
        $itemCustomName = $item->getCustomName();

        foreach ($hotBar->getItems() as $customItem) {
            $customItemType = $customItem->getObject()->getTypeId();
            $customItemName = $customItem->getCustomName();

            if ($itemTypeId === $customItemType && $itemCustomName === $customItemName) {
                return true;
            }
        }
        return false;
    }
}