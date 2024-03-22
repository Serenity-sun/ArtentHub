<?php

namespace Artent\item;

use pocketmine\item\Item;

final readonly class CustomItem
{

    public function __construct(
        private string $customName,
        private Item   $object,
        private int    $index
    ) {
        $this->object->setCustomName($this->customName);
    }

    /**
     * @return string
     */
    public function getCustomName(): string
    {
        return $this->customName;
    }

    /**
     * @return Item
     */
    public function getObject(): Item
    {
        return $this->object;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }
}