<?php

/*
 * This file is part of the Apisearch Bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Twig;

use Apisearch\SyliusApisearchPlugin\Url\UrlBuilder;
use Twig_Extension;
use Twig_SimpleFilter;

class UrlBuilderExtension extends Twig_Extension
{
    /**
     * @var UrlBuilder
     */
    protected $urlBuilder;

    /**
     * QueryExtension constructor.
     *
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(UrlBuilder $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return Twig_SimpleFilter[] An array of filters
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('add_filter_value', [$this->urlBuilder, 'addFilterValue']),
            new Twig_SimpleFilter('remove_filter_value', [$this->urlBuilder, 'removeFilterValue']),
            new Twig_SimpleFilter('remove_price_range_filter', [$this->urlBuilder, 'removePriceRangeFilter']),
            new Twig_SimpleFilter('add_sort_by', [$this->urlBuilder, 'addSortBy']),
            new Twig_SimpleFilter('remove_query', [$this->urlBuilder, 'removeQuery']),
        ];
    }
}
