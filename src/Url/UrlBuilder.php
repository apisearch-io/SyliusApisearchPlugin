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
use Apisearch\Result\Result;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class UrlBuilder
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UrlBuilder constructor.
     *
     * @param RequestStack $requestStack
     * @param RouterInterface $router
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
    }

    /**
     * @param Result $result
     * @param Aggregation $aggregation
     * @param Counter $counter
     *
     * @return string
     */
    public function guessFilterValue(
        Result $result,
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

    /**
     * @param Result $result
     * @param string $filterName
     * @param string $value
     *
     * @return string
     */
    public function addFilterValue(
        Result $result,
        string $filterName,
        string $value
    ): string {
        $urlParameters = \array_merge(
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

    /**
     * @param Result $result
     * @param string $filterName
     * @param string|null $value
     *
     * @return string
     */
    public function removeFilterValue(
        Result $result,
        string $filterName,
        string $value = null
    ): string {
        $urlParameters = \array_merge(
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

    /**
     * @param Result $result
     *
     * @return string
     */
    public function removeQuery(Result $result): string
    {
        $urlParameters = \array_merge(
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

    /**
     * @param Result $result
     *
     * @return string
     */
    public function removePriceRangeFilter(Result $result): string
    {
        $urlParameters = \array_merge(
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

    /**
     * @param Result $result
     * @param string $field
     * @param string $mode
     *
     * @return string|null
     */
    public function addSortBy(Result $result, string $field, string $mode): ? string
    {
        $urlParameters = \array_merge(
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

    /**
     * @param Result $result
     * @param int $size
     *
     * @return string
     */
    public function changePageSize(Result $result, int $size): string
    {
        $urlParameters = \array_merge(
            $this->generateQueryUrlParameters($result),
            [
                'slug' => $this->request->attributes->get('slug'),
                'page_size' => $size
            ]
        );

        return $this->router->generate(
            $this->request->get('_route'),
            $urlParameters
        );
    }

    /**
     * @param Result $result
     * @param string|null $filterName
     *
     * @return array
     */
    private function generateQueryUrlParameters(
        Result $result,
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

        $price = ['min' => -1, 'max' => -1];
        if (isset($urlParameters['price']) && \count($urlParameters['price']) > 0) {
            $priceUrl = \array_values($urlParameters['price'])[0];
            [$price['min'], $price['max']] = \explode('..', $priceUrl);
            $price = array_map(function ($row) {
                return $row <= 0
                    ? -1
                    : (int) \round($row / 100, 2)
                ;
            }, $price);

            unset($urlParameters['price']);
        }

        $urlParameters = \array_merge(
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
