<?php

namespace Artent\config;

readonly class Bedwars
{
    public function __construct(
        public TransferPortal $transferPortal,
        public TransferAddress $transferAddress
    ) {}
}