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

namespace Apisearch\SyliusApisearchPlugin\Configuration;

use Apisearch\Model\IndexUUID;
use Apisearch\SyliusApisearchPlugin\Element;
use function array_filter;
use function array_values;
use function count;
use Exception;
use function in_array;

class ApisearchConfiguration implements ApisearchConfigurationInterface
{
    /** @var string */
    private $version;

    /** @var string */
    private $index;

    /** @var bool */
    private $showPriceFilter;

    /** @var bool */
    private $showTextSearch;

    /** @var bool */
    private $enableAutocomplete;

    /** @var array */
    private $filters;

    /** @var array */
    private $paginationSize;

    /**
     * ApisearchConfiguration constructor.
     */
    public function __construct(
        string $version,
        bool $showPriceFilter,
        bool $showTextSearch,
        bool $enableAutocomplete,
        array $filters,
        array $paginationSize
    ) {
        $this->version = $version;
        $this->index = Element::INDEX_NAME;
        $this->showPriceFilter = $showPriceFilter;
        $this->showTextSearch = $showTextSearch;
        $this->enableAutocomplete = $enableAutocomplete;
        $this->filters = $filters;
        $this->paginationSize = $paginationSize;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function getIndexUUID(): IndexUUID
    {
        return IndexUUID::createById($this->index);
    }

    public function isShowPriceFilter(): bool
    {
        return $this->showPriceFilter;
    }

    public function isShowTextSearch(): bool
    {
        return $this->showTextSearch;
    }

    public function isEnableAutocomplete(): bool
    {
        return $this->enableAutocomplete;
    }

    public function getFilters(?string $type = null): array
    {
        if (false === in_array($type, Element::$filters)) {
            return $this->filters;
        }

        return array_filter($this->filters, function (array $filter) use ($type) {
            return $filter['type'] === $type;
        });
    }

    /**
     * @throws Exception
     */
    public function getPaginationSize(): array
    {
        if (0 === count($this->paginationSize)) {
            throw new Exception('Pagination size is not set up.');
        }

        return array_values($this->paginationSize);
    }
}
