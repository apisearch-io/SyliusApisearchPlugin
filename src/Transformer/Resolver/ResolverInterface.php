<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Transformer\Resolver;

use Sylius\Component\Core\Model\ProductInterface;

interface ResolverInterface
{
    public function resolve(ProductInterface $product, string $localeCode);
}
