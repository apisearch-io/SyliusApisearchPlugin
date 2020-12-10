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

namespace Apisearch\SyliusApisearchPlugin\Repository;

use Sylius\Component\Core\Model\ProductInterface;

interface ProductRepositoryInterface
{
    public function findOneByCodeAndLocale(string $code, string $locale): ?ProductInterface;

    public function findEnabledByLocale(string $locale, int $limit, int $offset): array;

    public function countEnabledByLocale(string $locale): int;
}
