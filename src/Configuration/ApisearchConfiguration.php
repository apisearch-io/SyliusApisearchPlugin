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

use Apisearch\SyliusApisearchPlugin\Element;

class ApisearchConfiguration implements ApisearchConfigurationInterface
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $repository;

    /**
     * @var string
     */
    private $index;

    /**
     * @var bool
     */
    private $showPriceFilter;

    /**
     * @var bool
     */
    private $showTextSearch;

    /**
     * @var bool
     */
    private $enableAutocomplete;

    /**
     * @var array
     */
    private $filters;

    /**
     * ApisearchConfiguration constructor.
     *
     * @param string $version
     * @param string $repository
     * @param string $index
     * @param bool $showPriceFilter
     * @param bool $showTextSearch
     * @param bool $enableAutocomplete
     * @param array $filters
     */
    public function __construct(
        string $version,
        string $repository,
        string $index,
        bool $showPriceFilter,
        bool $showTextSearch,
        bool $enableAutocomplete,
        array $filters
    ) {
        $this->version = $version;
        $this->repository = $repository;
        $this->index = $index;
        $this->showPriceFilter = $showPriceFilter;
        $this->showTextSearch = $showTextSearch;
        $this->enableAutocomplete = $enableAutocomplete;
        $this->filters = $filters;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function isShowPriceFilter(): bool
    {
        return $this->showPriceFilter;
    }

    /**
     * @return bool
     */
    public function isShowTextSearch(): bool
    {
        return $this->showTextSearch;
    }

    /**
     * @return bool
     */
    public function isEnableAutocomplete(): bool
    {
        return $this->enableAutocomplete;
    }

    /**
     * @param string|null $type
     *
     * @return array
     */
    public function getFilters(?string $type = null): array
    {
        if (false === \in_array($type, Element::$filters)) {
            return $this->filters;
        }

        return \array_filter($this->filters, function (array $filter) use ($type) {
            return $filter['type'] === $type;
        });
    }
}
