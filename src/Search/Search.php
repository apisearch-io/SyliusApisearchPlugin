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
use Apisearch\Repository\TransformableRepository;
use Apisearch\Result\Result;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\Url\UrlBuilder;
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
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Search constructor.
     *
     * @param TransformableRepository $repository
     * @param ApisearchConfigurationInterface $configuration
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        TransformableRepository $repository,
        ApisearchConfigurationInterface $configuration,
        UrlBuilder $urlBuilder
    ) {
        $this->repository = $repository;
        $this->configuration = $configuration;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param Request $request
     * @param TaxonInterface $taxon
     *
     * @return Result
     */
    public function getResult(Request $request, TaxonInterface $taxon): Result
    {
        $this->urlBuilder->setRoutesDictionary([
            'main' => 'sylius_shop_product_index',
        ]);

        $requestQuery = $request->query;

        $query = Query::create(
            $request->get('search', ''),
            $this->getCurrentPage($request),
            $this->getCurrentSize($request)
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
                    (int) $this->createPriceRequest($request, 'min'),
                    (int) $this->createPriceRequest($request, 'max')
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
        return (int) $request->get('page_size', Query::DEFAULT_SIZE);
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
