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

use Apisearch\Result\Result;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

interface SearchInterface
{
    /**
     * @param Request $request
     * @param TaxonInterface $taxon
     *
     * @return Result
     */
    public function getResult(Request $request, TaxonInterface $taxon): Result;

    /**
     * @param Request $request
     *
     * @return int
     */
    public function getCurrentPage(Request $request): int;

    /**
     * @param Request $request
     *
     * @return int
     */
    public function getCurrentSize(Request $request): int;
}
