<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Populate;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
interface PopulateInterface
{
    /**
     * @param int $perPage
     */
    public function populate(int $perPage): void;
}