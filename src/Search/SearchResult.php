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

namespace Apisearch\SyliusApisearchPlugin\Search;

use Apisearch\Query\Query;
use Apisearch\Result\Result;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;

class SearchResult
{
    /** @var Result */
    private $result;

    /** @var Query */
    private $query;

    /** @var ApisearchConfigurationInterface */
    private $configuration;

    /**
     * SearchResult constructor.
     */
    public function __construct(Result $result, Query $query, ApisearchConfigurationInterface $configuration)
    {
        $this->result = $result;
        $this->query = $query;
        $this->configuration = $configuration;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    public function getConfiguration(): ApisearchConfigurationInterface
    {
        return $this->configuration;
    }
}
