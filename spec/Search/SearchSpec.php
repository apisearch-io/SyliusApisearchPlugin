<?php

declare(strict_types=1);

namespace spec\Apisearch\SyliusApisearchPlugin\Search;

use Apisearch\Query\Filter;
use Apisearch\Query\Query;
use Apisearch\Query\SortBy;
use Apisearch\Repository\TransformableRepository;
use Apisearch\Result\Result;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\SyliusApisearchPlugin\Search\Search;
use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Apisearch\SyliusApisearchPlugin\Search\SearchResult;
use Apisearch\SyliusApisearchPlugin\Url\UrlBuilder;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchSpec extends ObjectBehavior
{
    public function let(
        TransformableRepository $repository,
        ApisearchConfigurationInterface $configuration,
        UrlBuilder $urlBuilder,
        LocaleContextInterface $localeContext
    ) {
        $this->beConstructedWith($repository, $configuration, $urlBuilder, $localeContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Search::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(SearchInterface::class);
    }

    function it_get_search_result(
        Request $request,
        LocaleContextInterface $localeContext,
        TaxonInterface $taxon,
        TransformableRepository $repository,
        Result $result,
        ApisearchConfigurationInterface $configuration
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $taxon->getCode()->willReturn('taxon');

        $configuration->getPaginationSize()->willReturn([1, 2, 3]);
        $configuration->getFilters()->willReturn([]);

        $request->get('q', '')->willReturn('jeans');
        $request->get('page', Query::DEFAULT_PAGE)->willReturn(1);
        $request->get('page_size', 1)->willReturn(1);
        $request->get('price_min', null)->willReturn(10);
        $request->get('price_max', null)->willReturn(40);
        $request->get('sort_by', [])->willReturn([]);

        $query = Query::create('jeans', 1, 1);
        $query->filterUniverseBy(
            Element::FIELD_TAXON_CODE,
            [
                'taxon',
            ]
        );

        $query->filterUniverseBy(
            Element::FIELD_LOCALE,
            [
               'en',
            ]
        );

        $query->filterByRange(
            Element::FIELD_PRICE,
            Element::FIELD_PRICE,
            [],
            [
                '1000..4000',
            ],
            FILTER::MUST_ALL
        );

        $query->aggregateBy(
            Element::FIELD_PRICE,
            Element::FIELD_PRICE,
            FILTER::MUST_ALL
        );

        $query->sortBy(SortBy::create()->byFieldValue(Element::FIELD_ID, SortBy::ASC));

        $repository->query($query)->willReturn($result);

        $this->getSearchResult($request, $taxon)->shouldBeAnInstanceOf(SearchResult::class);
    }

    function it_get_current_page(Request $request): void
    {
        $request->get('page', Query::DEFAULT_PAGE)->willReturn(1);

        $this->getCurrentPage($request)->shouldBe(1);
    }

    function it_get_current_size_if_exists_in_configuration(
        Request $request,
        ApisearchConfigurationInterface $configuration
    ): void {
        $configuration->getPaginationSize()->willReturn([1, 2, 3]);

        $request->get('page_size', 1)->willReturn(1);

        $this->getCurrentSize($request)->shouldBe(1);
    }

    function it_get_current_size_if_doesnt_exists_in_configuration(
        Request $request,
        ApisearchConfigurationInterface $configuration
    ): void {
        $configuration->getPaginationSize()->willReturn([1, 2, 3]);

        $request->get('page_size', 1)->willReturn(20);

        $this->getCurrentSize($request)->shouldBe(1);
    }
}
