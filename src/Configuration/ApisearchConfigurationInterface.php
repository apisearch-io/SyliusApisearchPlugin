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

interface ApisearchConfigurationInterface
{
    public function getVersion(): string;

    public function getIndex(): string;

    public function getIndexUUID(): IndexUUID;

    public function isShowPriceFilter(): bool;

    public function isShowTextSearch(): bool;

    public function isEnableAutocomplete(): bool;

    public function getFilters(?string $type = null): array;

    public function getPaginationSize(): array;

    public function getToken(): string;

    public function getEndpoint(): string;

    public function getAppId(): string;
}
