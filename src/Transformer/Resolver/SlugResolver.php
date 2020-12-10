<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Transformer\Resolver;

use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class SlugResolver implements ResolverInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function resolve(ProductInterface $product, string $localeCode): ?string
    {
        if (null === $product->getSlug()) {
            return null;
        }

        return $this->router->generate(
            'sylius_shop_product_show',
            [
                'slug' => $product->getSlug(),
                '_locale' => $localeCode,
            ],
            UrlGeneratorInterface::RELATIVE_PATH
        );
    }
}
