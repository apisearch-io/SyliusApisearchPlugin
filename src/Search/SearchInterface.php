<?php

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
}
