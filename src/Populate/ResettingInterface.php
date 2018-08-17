<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Populate;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
interface ResettingInterface
{
    public function reset(): void;
}