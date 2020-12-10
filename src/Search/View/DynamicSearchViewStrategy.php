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

namespace Apisearch\SyliusApisearchPlugin\Search\View;

use Exception;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

class DynamicSearchViewStrategy implements SearchViewStrategyInterface
{
    public function getParameters(Request $request, TaxonInterface $taxon): array
    {
        throw new Exception('Dynamic view is not implemented yet.');
    }
}
