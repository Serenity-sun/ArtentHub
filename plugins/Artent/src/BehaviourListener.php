<?php

namespace Artent;

use Artent\config\Config;
use Artent\item\AdminHotBar;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class BehaviourListener implements Listener
{
    /**
     * @param PlayerLoginEvent $event
     * @return void
     */
    public function playerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $player->setGamemode(GameMode::ADVENTURE);
        $player->setAllowFlight(true);
        $player->setMaxHealth(20);
        $player->setHealth(20);
        $player->teleport($player->getWorld()->getSpawnLocation()->up());
        $player->getHungerManager()->addFood(20);
        $player->getHungerManager()->setEnabled(false);

        $this->setInventoryItems($player);
    }

    /**
     * @param Player $player
     * @return void
     */
    private function setInventoryItems(Player $player): void
    {
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        foreach ((new AdminHotBar())->getItems() as $customItem) {
            $player->getInventory()->setItem(
                $customItem->getIndex(),
                $customItem->getObject()
            );
        }
    }

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
    public function playerMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $bedwarsConfig = Config::getInstance()->getBedwars();

        $portalIntersected = $player->getBoundingBox()->intersectsWith(new AxisAlignedBB(
            $bedwarsConfig->transferPortal->minX,
            $bedwarsConfig->transferPortal->minY,
            $bedwarsConfig->transferPortal->minZ,
            $bedwarsConfig->transferPortal->maxX,
            $bedwarsConfig->transferPortal->maxY,
            $bedwarsConfig->transferPortal->maxZ
        ));

        if ($portalIntersected) {
            $player->transfer(
                $bedwarsConfig->transferAddress->ip,
                $bedwarsConfig->transferAddress->port
            );
        }
    }
}