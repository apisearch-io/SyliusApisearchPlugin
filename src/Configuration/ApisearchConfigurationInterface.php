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

interface ApisearchConfigurationInterface
{
    /**
     * @return string
     */
    public function getVersion(): string;

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
     * @param string|null $type
     *
     * @return array
     */
    public function getFilters(?string $type = null): array;

    /**
     * @return array
     */
    public function getPaginationSize(): array;
}
