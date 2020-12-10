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

use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Pagerfanta\Adapter\NullAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

class StaticSearchViewStrategy implements SearchViewStrategyInterface
{
    /** @var SearchInterface */
    private $search;

    public function __construct(SearchInterface $search)
    {
        $this->search = $search;
    }

    public function getParameters(Request $request, TaxonInterface $taxon): array
    {
        $result = $this->search->getSearchResult($request, $taxon);

        $pagerAdapter = new NullAdapter($result->getResult()->getTotalHits());
        $pagerfanta = new Pagerfanta($pagerAdapter);

        $pagerfanta->setMaxPerPage(
            $this->search->getCurrentSize($request)
        );

        $pagerfanta->setCurrentPage(
            $this->search->getCurrentPage($request)
        );

        return [
            'result' => $result,
            'pager' => $pagerfanta,
        ];
    }
}
