<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxonNotFoundException extends NotFoundHttpException
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('Taxon not found.');
    }
}
