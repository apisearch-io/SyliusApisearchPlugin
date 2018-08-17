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

use Apisearch\Repository\RepositoryBucket;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;

class Resetting implements ResettingInterface
{
    /**
     * @var ApisearchConfigurationInterface
     */
    private $configuration;

    /**
     * @var RepositoryBucket
     */
    private $repositoryBucket;

    /**
     * Resetting constructor.
     *
     * @param ApisearchConfigurationInterface $configuration
     * @param RepositoryBucket $repositoryBucket
     */
    public function __construct(ApisearchConfigurationInterface $configuration, RepositoryBucket $repositoryBucket)
    {
        $this->configuration = $configuration;
        $this->repositoryBucket = $repositoryBucket;
    }

    public function reset(): void
    {
        $this
            ->repositoryBucket->findRepository(
                $this->configuration->getRepository(),
                $this->configuration->getIndex()
            )
            ->resetIndex()
        ;
    }
}
