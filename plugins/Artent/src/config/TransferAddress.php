<?php

namespace Artent\config;

readonly class TransferAddress
{
    public function __construct(
        public string $ip,
        public int $port
    ) {}
}