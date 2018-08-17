<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Populate;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class Populate implements PopulateInterface
{
    /**
     * @param int $perPage
     */
    public function populate(int $perPage): void
    {
        dump($perPage);
    }
}