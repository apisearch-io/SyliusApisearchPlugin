<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Configuration;

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
     * @param string $version
     *
     * @return bool
     */
    public function isVersion(string $version): bool
    {
        return $version === $this->version;
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
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
