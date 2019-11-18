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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlBuilderExtension extends AbstractExtension
{
    /** @var UrlBuilder */
    protected $urlBuilder;

    /**
     * QueryExtension constructor.
     */
    public function __construct(UrlBuilder $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[] An array of filters
     */
    public function getFilters()
    {
        return [
            new TwigFilter('add_filter_value', [$this->urlBuilder, 'addFilterValue']),
            new TwigFilter('remove_filter_value', [$this->urlBuilder, 'removeFilterValue']),
            new TwigFilter('remove_price_range_filter', [$this->urlBuilder, 'removePriceRangeFilter']),
            new TwigFilter('add_sort_by', [$this->urlBuilder, 'addSortBy']),
            new TwigFilter('remove_query', [$this->urlBuilder, 'removeQuery']),
            new TwigFilter('change_page_size', [$this->urlBuilder, 'changePageSize']),
        ];
    }
}
