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

use Apisearch\Query\Filter;
use Apisearch\Query\Query;
use Apisearch\Query\SortBy;
use Apisearch\Repository\TransformableRepository;
use Apisearch\Result\Result;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\SyliusApisearchPlugin\Url\UrlBuilder;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;

class Search implements SearchInterface
{
    /**
     * @var TransformableRepository
     */
    private $repository;

    /**
     * @var ApisearchConfigurationInterface
     */
    private $configuration;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * Search constructor.
     *
     * @param TransformableRepository $repository
     * @param ApisearchConfigurationInterface $configuration
     * @param UrlBuilder $urlBuilder
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        TransformableRepository $repository,
        ApisearchConfigurationInterface $configuration,
        UrlBuilder $urlBuilder,
        LocaleContextInterface $localeContext
    ) {
        $this->repository = $repository;
        $this->configuration = $configuration;
        $this->urlBuilder = $urlBuilder;
        $this->localeContext = $localeContext;
    }

    /**
     * @param Request $request
     * @param TaxonInterface $taxon
     *
     * @return Result
     */
    public function getResult(Request $request, TaxonInterface $taxon): Result
    {
        $requestQuery = $request->query;

        $query = Query::create(
            $request->get('q', ''),
            $this->getCurrentPage($request),
            $this->getCurrentSize($request)
        );

        $query->filterUniverseBy(
            Element::FIELD_TAXON_CODE,
            [
                $taxon->getCode(),
            ]
        );

        $query->filterUniverseBy(
            Element::FIELD_LOCALE,
            [
                $this->localeContext->getLocaleCode(),
            ]
        );

        $query->filterByRange(
            Element::FIELD_PRICE,
            Element::FIELD_PRICE,
            [],
            [
                \sprintf(
                    '%d..%d',
                    $this->createPriceRequest($request, 'min'),
                    $this->createPriceRequest($request, 'max')
                ),
            ],
            FILTER::MUST_ALL
        );

        $query->aggregateBy(
            Element::FIELD_PRICE,
            Element::FIELD_PRICE,
            FILTER::MUST_ALL
        );

        foreach ($this->configuration->getFilters() as $filter) {
            $query->filterBy(
                $filter['name'],
                $filter['field'],
                $requestQuery->get($filter['field'], []),
                $filter['precision'],
                $filter['aggregate'],
                $filter['aggregation_sort']
            );

            $query->aggregateBy(
                $filter['name'],
                $filter['field'],
                $filter['precision'],
                $filter['aggregation_sort']
            );
        }

        $this->getSort($request, $query);

        return $this->repository->query($query);
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function getCurrentPage(Request $request): int
    {
        return (int) $request->get('page', Query::DEFAULT_PAGE);
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function getCurrentSize(Request $request): int
    {
        $availablePaginationSize = $this->configuration->getPaginationSize();
        $currentSize = (int) $request->get('page_size', $availablePaginationSize[0]);

        return \in_array($currentSize, $availablePaginationSize)
            ? $currentSize
            : $availablePaginationSize[0]
        ;
    }

    /**
     * @param Request $request
     * @param string $suffix
     *
     * @return int
     */
    private function createPriceRequest(Request $request, string $suffix): int
    {
        $price = (int) $request->get(
            \sprintf(
                '%s_%s',
                Element::FIELD_PRICE,
                $suffix
            ),
            -1
        );

        return $price > 0
            ? (int) \round($price * 100, 2)
            : -1
        ;
    }

    /**
     * @param Request $request
     * @param Query $query
     */
    private function getSort(Request $request, Query $query): void
    {
        $sort = SortBy::create();

        $sortBy = $request->get('sort_by', []);
        if (\count($sortBy) <= 0) {
            $sort->byFieldValue(Element::FIELD_ID, SortBy::ASC);
            $query->sortBy($sort);

            return;
        }

        $field = \array_keys($sortBy)[0];
        $direction = \array_values($sortBy)[0];
        $direction = $direction == SortBy::ASC
                ? SortBy::ASC
                : SortBy::DESC
            ;

        $sort->byFieldValue($field, $direction);
        $query->sortBy($sort);
    }
}
