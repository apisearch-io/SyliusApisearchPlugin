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

namespace Apisearch\SyliusApisearchPlugin\Indexing;

use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface PopulateInterface
{
    /**
     * @throws \Exception
     */
    public function populate(OutputInterface $output, int $perPage): void;

    /**
     * @throws \Exception
     */
    public function populateSingle(ProductInterface $product, bool $flush = true): void;

    /**
     * @throws \Exception
     */
    public function removeSingle(ProductInterface $product, bool $flush = true): void;

    /**
     * @throws \Exception
     */
    public function updateSingle(ProductInterface $product, bool $flush = true): void;

    /**
     * @throws Exception
     */
    public function flush(): void;
}
