<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Search;

use Apisearch\Query\Filter;
use Apisearch\Query\Query;
use Apisearch\Repository\TransformableRepository;
use Apisearch\Result\Result;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Sylius\Component\Core\Model\TaxonInterface;
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
     * Search constructor.
     *
     * @param TransformableRepository $repository
     * @param ApisearchConfigurationInterface $configuration
     */
    public function __construct(
        TransformableRepository $repository,
        ApisearchConfigurationInterface $configuration
    ) {
        $this->repository = $repository;
        $this->configuration = $configuration;
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
            $request->get('search', ''),
            (int) $request->get('page', Query::DEFAULT_PAGE),
            (int) $request->get('page_size', Query::DEFAULT_SIZE)
        );

        $query->filterUniverseBy(
            Element::FIELD_TAXON_CODE,
            [
                $taxon->getCode(),
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

        return $this->repository->query($query);
    }

    /**
     * @param Request $request
     * @param string $suffix
     *
     * @return int
     */
    private function createPriceRequest(Request $request, string $suffix): int
    {
        return (int) $request->get(
            \sprintf(
                '%s_%s',
                Element::FIELD_PRICE,
                $suffix
            ),
            -1
        );
    }
}
