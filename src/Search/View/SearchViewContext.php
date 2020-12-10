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

namespace Apisearch\SyliusApisearchPlugin\Search\View;

use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\SyliusApisearchPlugin\Exception\VersionUnavailableException;
use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchViewContext
{
    /** @var SearchViewStrategyInterface */
    private $strategy;

    public function __construct(ApisearchConfigurationInterface $configuration, SearchInterface $search)
    {
        $version = $configuration->getVersion();

        switch ($version) {
            case Element::VERSION_STATIC:
                $this->strategy = new StaticSearchViewStrategy($search);

                break;
            case Element::VERSION_DYNAMIC:
                $this->strategy = new DynamicSearchViewStrategy();

                break;
            default:
                throw new VersionUnavailableException($version);
        }
    }

    public function getParameters(Request $request, TaxonInterface $taxon): array
    {
        return $this->strategy->getParameters($request, $taxon);
    }
}
