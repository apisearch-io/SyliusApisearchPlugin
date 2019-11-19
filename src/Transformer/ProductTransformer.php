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
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\Transformer\ReadTransformer;
use Apisearch\Transformer\WriteTransformer;
use Exception;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class ProductTransformer implements ReadTransformer, WriteTransformer
{
    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var ProductRepository */
    private $productRepository;

    /** @var MetadataBuilderInterface */
    private $metadataBuilder;

    /**
     * ProductTransformer constructor.
     */
    public function __construct(
        LocaleContextInterface $localeContext,
        ProductRepository $productRepository,
        MetadataBuilderInterface $metadataBuilder
    ) {
        $this->localeContext = $localeContext;
        $this->productRepository = $productRepository;
        $this->metadataBuilder = $metadataBuilder;
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
        return $item->getType() === Element::PRODUCT_TRANSFORMER_KEY;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function fromItem(Item $item)
    {
        $code = $item->get('code');

        $product = $this->productRepository->findBy(
            ['code' => $code]
        );
        if (null === $product) {
            throw new Exception(sprintf('Product with "%s" code not found', $code));
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
            $object->getCode(),
            Element::PRODUCT_TRANSFORMER_KEY
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toItem($object): Item
    {
        $localCode = $this->localeContext->getLocaleCode();

        /** @var ProductInterface $object */
        return Item::create(
            $this->toItemUUID($object),
            [
                'name' => $object->getName(),
                'description' => $object->getDescription(),
                'slug' => $object->getSlug(),
                'images' => $object->getImages(),
                'code' => $object->getCode(),
                'locale' => $localCode,
            ],
            $this->metadataBuilder->build($object, $localCode),
            [
                'name' => $object->getName(),
                'description' => $object->getDescription(),
            ]
        );
    }
}
