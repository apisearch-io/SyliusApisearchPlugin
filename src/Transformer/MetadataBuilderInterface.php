<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Transformer;

use Sylius\Component\Core\Model\ProductInterface;

interface MetadataBuilderInterface
{
    public function build(ProductInterface $product, string $localeCode): array;
}
