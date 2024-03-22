<?php

namespace Artent\config;

readonly class TransferPortal
{
    public function __construct(
        public int $minX,
        public int $minY,
        public int $minZ,
        public int $maxX,
        public int $maxY,
        public int $maxZ
    ) {}
}