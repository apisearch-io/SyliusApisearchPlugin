<?php

declare(strict_types=1);

namespace spec\Apisearch\SyliusApisearchPlugin\Search\View;

use Apisearch\Result\Result;
use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Apisearch\SyliusApisearchPlugin\Search\SearchResult;
use Apisearch\SyliusApisearchPlugin\Search\View\SearchViewStrategyInterface;
use Apisearch\SyliusApisearchPlugin\Search\View\StaticSearchViewStrategy;
use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

class StaticSearchViewStrategySpec extends ObjectBehavior
{
    function let(SearchInterface $search): void
    {
        $this->beConstructedWith($search);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(StaticSearchViewStrategy::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(SearchViewStrategyInterface::class);
    }

    function it_get_parameters(
        Request $request,
        TaxonInterface $taxon,
        SearchInterface $search,
        SearchResult $searchResult,
        Pagerfanta $pagerfanta,
        Result $result
    ): void {
        $result->getTotalHits()->willReturn(0);
        $searchResult->getResult()->willReturn($result);

        $search->getSearchResult($request, $taxon)->willReturn($searchResult);
        $search->getCurrentSize($request)->willReturn(1);
        $search->getCurrentPage($request)->willReturn(1);

        $this->getParameters($request, $taxon)->shouldBeArray();
    }
}
