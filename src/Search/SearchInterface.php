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

namespace Apisearch\SyliusApisearchPlugin\Search;

use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

interface SearchInterface
{
    public function getSearchResult(Request $request, TaxonInterface $taxon): SearchResult;

    public function getCurrentPage(Request $request): int;

    public function getCurrentSize(Request $request): int;
}
