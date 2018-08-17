<?php

/*
 * This file is part of the Sylius Apisearch Plugin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Populate;

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
