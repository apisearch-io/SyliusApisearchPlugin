<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Context;

use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

interface TaxonContextInterface
{
    /**
     * @param Request $request
     *
     * @return TaxonInterface
     */
    public function findByRequest(Request $request): TaxonInterface;
}
