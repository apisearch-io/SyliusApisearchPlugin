<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Configuration;

interface ApisearchConfigurationInterface
{
    /**
     * @param string $version
     *
     * @return bool
     */
    public function isVersion(string $version): bool;

    /**
     * @return string
     */
    public function getRepository(): string;

    /**
     * @return string
     */
    public function getIndex(): string;

    /**
     * @return bool
     */
    public function isShowPriceFilter(): bool;

    /**
     * @return bool
     */
    public function isShowTextSearch(): bool;

    /**
     * @return bool
     */
    public function isEnableAutocomplete(): bool;

    /**
     * @return array
     */
    public function getFilters(): array;
}
