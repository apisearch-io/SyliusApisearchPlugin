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
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\Transformer\ReadTransformer;
use Apisearch\Transformer\WriteTransformer;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class ProductTransformer implements ReadTransformer, WriteTransformer
{
    /**
     * @var ApisearchConfigurationInterface
     */
    protected $configuration;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * ProductTransformer constructor.
     *
     * @param ApisearchConfigurationInterface $configuration
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(ApisearchConfigurationInterface $configuration, LocaleContextInterface $localeContext)
    {
        $this->configuration = $configuration;
        $this->localeContext = $localeContext;
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
        return $item->getType() === 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function fromItem(Item $item)
    {
        $product = new Product();

        $product->setCurrentLocale((string) $item->get('locale'));
        $product->setName($item->get('name'));
        $product->setDescription($item->get('description'));
        $product->setCode($item->get('code'));

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function toItemUUID($object): ItemUUID
    {
        return new ItemUUID(
            $object->getId(),
            'product'
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
                'slug' => $object->getSlug(),
                'images' => $object->getImages(),
                'code' => $object->getCode(),
            ],
            \array_merge(
                [
                    'locale' => $this->localeContext->getLocaleCode(),
                    'taxon' => $this->getTaxons($object),
                    'price' => $this->getPrices($object),
                ],
                $this->getOptions($object),
                $this->getAttributes($object)
            ),
            [
                'name' => $object->getName(),
                'description' => $object->getDescription(),
            ]
        );
    }

    /**
     * @param ProductInterface $product
     *
     * @return array
     */
    private function getTaxons(ProductInterface $product): array
    {
        $taxons = [];

        $mainTaxon = $product->getMainTaxon();
        if (null !== $mainTaxon) {
            $taxons[] = $mainTaxon->getCode();
        }

        $otherTaxons = $product->getTaxons();
        if ($otherTaxons->count() > 0) {
            foreach ($otherTaxons as $taxon) {
                $taxons[] = $taxon->getCode();
            }
        }

        return $taxons;
    }

    /**
     * @param ProductInterface $product
     *
     * @return array
     */
    private function getPrices(ProductInterface $product): array
    {
        if (0 === $product->getVariants()->count()) {
            return [];
        }

        $prices = [];
        foreach ($product->getVariants() as $variant) {
            /** @var ChannelPricingInterface $channelPricing */
            foreach ($variant->getChannelPricings() as $channelPricing) {
                $prices[] = $channelPricing->getPrice();
            }
        }

        return $prices;
    }

    /**
     * @param ProductInterface $product
     *
     * @return array
     */
    private function getOptions(ProductInterface $product): array
    {
        return [];
    }

    /**
     * @param ProductInterface $product
     *
     * @return array
     */
    private function getAttributes(ProductInterface $product): array
    {
        return [];
    }
}
