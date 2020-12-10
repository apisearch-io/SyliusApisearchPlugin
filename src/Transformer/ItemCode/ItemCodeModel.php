<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Transformer\ItemCode;

class ItemCodeModel
{
    /** @var string */
    private $localeCode;

    /** @var string */
    private $productCode;

    public function __construct(string $localeCode, string $productCode)
    {
        $this->localeCode = $localeCode;
        $this->productCode = $productCode;
    }

    public function getLocaleCode(): string
    {
        return $this->localeCode;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }
}
