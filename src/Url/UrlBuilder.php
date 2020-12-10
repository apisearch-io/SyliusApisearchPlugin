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

namespace Apisearch\SyliusApisearchPlugin\Url;

use Apisearch\Query\Filter;
use Apisearch\Query\SortBy;
use Apisearch\Result\Aggregation;
use Apisearch\Result\Counter;
use Apisearch\SyliusApisearchPlugin\Search\SearchResult;
use function array_merge;
use function array_values;
use function count;
use function explode;
use function round;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class UrlBuilder
{
    /** @var Request */
    private $request;

    /** @var RouterInterface */
    private $router;

    /**
     * UrlBuilder constructor.
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
    }

    public function guessFilterValue(
        SearchResult $result,
        Aggregation $aggregation,
        Counter $counter
    ): string {
        return $counter->isUsed()
            ? $this->removeFilterValue(
                $result,
                $aggregation->getName(),
                $counter->getId()
            )
            : $this->addFilterValue(
                $result,
                $aggregation->getName(),
                $counter->getId()
            );
    }

    public function addFilterValue(
        SearchResult $result,
        string $filterName,
        string $value
    ): string {
        $urlParameters = array_merge(
            $this->generateQueryUrlParameters($result, $filterName),
            [
                'slug' => $this->request->attributes->get('slug'),
            ]
        );

        if (
            !isset($urlParameters[$filterName]) ||
            !in_array($value, $urlParameters[$filterName])
        ) {
            $urlParameters[$filterName][] = $value;
        }

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    public function removeFilterValue(
        SearchResult $result,
        string $filterName,
        string $value = null
    ): string {
        $urlParameters = array_merge(
            $this->generateQueryUrlParameters($result, $filterName),
            [
                'slug' => $this->request->attributes->get('slug'),
            ]
        );

        if (
            null === $value ||
            !isset($urlParameters[$filterName])
        ) {
            unset($urlParameters[$filterName]);
        } elseif (false !== ($key = array_search($value, $urlParameters[$filterName]))) {
            unset($urlParameters[$filterName][$key]);
        }

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    public function removeQuery(SearchResult $result): string
    {
        $urlParameters = array_merge(
            $this->generateQueryUrlParameters($result),
            [
                'slug' => $this->request->attributes->get('slug'),
            ]
        );

        if (isset($urlParameters['q'])) {
            unset($urlParameters['q']);
        }

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    public function removePriceRangeFilter(SearchResult $result): string
    {
        $urlParameters = array_merge(
            $this->generateQueryUrlParameters($result),
            [
                'slug' => $this->request->attributes->get('slug'),
            ]
        );

        if (isset($urlParameters['price'])) {
            unset($urlParameters['price']);
        }

        if (isset($urlParameters['price_min'])) {
            unset($urlParameters['price_min']);
        }

        if (isset($urlParameters['price_max'])) {
            unset($urlParameters['price_max']);
        }

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    public function addSortBy(SearchResult $result, string $field, string $mode): ? string
    {
        $urlParameters = array_merge(
            $this->generateQueryUrlParameters($result),
            [
                'slug' => $this->request->attributes->get('slug'),
            ]
        );

        if (
            isset($urlParameters['sort_by'][$field]) &&
            $urlParameters['sort_by'][$field] == $mode
        ) {
            return null;
        }

        if (
            !isset($urlParameters['sort_by']) &&
            SortBy::SCORE === [$field => $mode]
        ) {
            return null;
        }

        unset($urlParameters['sort_by']);

        if (SortBy::SCORE !== [$field => $mode]) {
            $urlParameters['sort_by'][$field] = $mode;
        }

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    public function changePageSize(SearchResult $result, int $size): string
    {
        $urlParameters = array_merge(
            $this->generateQueryUrlParameters($result),
            [
                'slug' => $this->request->attributes->get('slug'),
                'page_size' => $size,
            ]
        );

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    private function generateQueryUrlParameters(
        SearchResult $result,
        string $filterName = null
    ): array {
        $query = $result->getQuery();
        $queryFilters = $query->getFilters();
        $urlParameters = [];
        foreach ($queryFilters as $currentFilterName => $filter) {
            /*
             * Special case for elements with LEVEL.
             */
            $urlParameters[$currentFilterName] = (
                null !== $filterName &&
                $currentFilterName === $filterName &&
                Filter::MUST_ALL_WITH_LEVELS === $filter->getApplicationType()
            )
                ? []
                : $filter->getValues();
        }

        unset($urlParameters['_query']);

        $price = ['min' => null, 'max' => null];
        if (isset($urlParameters['price']) && count($urlParameters['price']) > 0) {
            $priceUrl = array_values($urlParameters['price'])[0];
            [$price['min'], $price['max']] = explode('..', $priceUrl);
            $price = array_map(function ($row) {
                return $row <= 0
                    ? null
                    : (int) round($row / 100, 2)
                ;
            }, $price);

            unset($urlParameters['price']);
        }

        $urlParameters = array_merge(
            $urlParameters,
            [
                'price_min' => $price['min'],
                'price_max' => $price['max'],
            ]
        );

        $queryFilter = $query->getFilter('_query');
        $queryString = $queryFilter instanceof Filter
            ? $queryFilter->getValues()[0]
            : '';

        if ($query->getSize() > 0) {
            $urlParameters['page_size'] = $query->getSize();
        }

        if (!empty($queryString)) {
            $urlParameters['q'] = $queryString;
        }

        $sort = $query->getSortBy();
        if (SortBy::SCORE !== $sort) {
            $urlParameters['sort_by'] = $sort instanceof SortBy
                ? []
                : $sort
            ;
        }

        return $urlParameters;
    }
}
