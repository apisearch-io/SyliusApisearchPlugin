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

use Apisearch\App\AppRepository;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;

class Resetting implements ResettingInterface
{
    /** @var ApisearchConfigurationInterface */
    private $configuration;

    /** @var AppRepository */
    private $repository;

    /**
     * Resetting constructor.
     */
    public function __construct(ApisearchConfigurationInterface $configuration, AppRepository $repository)
    {
        $this->configuration = $configuration;
        $this->repository = $repository;
    }

    public function reset(): void
    {
        $this->repository->resetIndex(
            $this->configuration->getIndexUUID()
        );
    }
}
