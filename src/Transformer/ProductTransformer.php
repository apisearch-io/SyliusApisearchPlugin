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

namespace Apisearch\SyliusApisearchPlugin\Transformer;

use Apisearch\Model\Item;
use Apisearch\Model\ItemUUID;
use Apisearch\SyliusApisearchPlugin\Repository\ProductRepositoryInterface;
use Apisearch\SyliusApisearchPlugin\Transformer\ItemCode\ItemCodeResolver;
use Apisearch\SyliusApisearchPlugin\Transformer\Resolver\ResolverInterface;
use Apisearch\Transformer\ReadTransformer;
use Apisearch\Transformer\WriteTransformer;
use Exception;
use function sprintf;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class ProductTransformer implements ReadTransformer, WriteTransformer
{
    private const PRODUCT_TRANSFORMER_KEY = 'product';

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var MetadataBuilderInterface */
    private $metadataBuilder;

    /** @var string */
    private $localeCode;

    /** @var ResolverInterface */
    private $slugResolver;

    /**
     * ProductTransformer constructor.
     */
    public function __construct(
        LocaleContextInterface $localeContext,
        ProductRepositoryInterface $productRepository,
        MetadataBuilderInterface $metadataBuilder,
        ResolverInterface $slugResolver
    ) {
        $this->localeCode = $localeContext->getLocaleCode();
        $this->productRepository = $productRepository;
        $this->metadataBuilder = $metadataBuilder;
        $this->slugResolver = $slugResolver;
    }

    public function setLocaleCode(?string $localeCode): void
    {
        if (!empty($localeCode)) {
            $this->localeCode = $localeCode;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValidObject($object): bool
    {
        return $object instanceof ProductInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidItem(Item $item): bool
    {
        return $item->getType() === self::PRODUCT_TRANSFORMER_KEY;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function fromItem(Item $item)
    {
        $itemResolver = ItemCodeResolver::decode($item->getUUID()->getId());

        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneByCodeAndLocale(
            $itemResolver->getProductCode(),
            $itemResolver->getLocaleCode()
        );
        if (null === $product) {
            throw new Exception(sprintf('Product with "%s" code not found', $itemResolver->getProductCode()));
        }

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function toItemUUID($object): ItemUUID
    {
        /** @var ProductInterface $object */
        return new ItemUUID(
            ItemCodeResolver::encode($object->getCode(), $this->localeCode),
            self::PRODUCT_TRANSFORMER_KEY
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toItem($object): Item
    {
        /** @var ProductInterface $object */
        return Item::create(
            $this->toItemUUID($object),
            [
                'name' => $object->getName(),
                'description' => $object->getDescription(),
                'slug' => $this->slugResolver->resolve($object, $this->localeCode),
                'images' => $object->getImages(),
                'code' => $object->getCode(),
                'locale' => $this->localeCode,
            ],
            $this->metadataBuilder->build($object, $this->localeCode),
            [
                'name' => $object->getName(),
                'description' => $object->getDescription(),
            ]
        );
    }
}
