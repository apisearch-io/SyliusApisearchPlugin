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

use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\MetadataInterface;
use function array_merge;
use function iterator_to_array;
use Sylius\Component\Core\Model\ProductInterface;

class MetadataBuilder implements MetadataBuilderInterface
{
    /** @var MetadataInterface */
    private $priceMetadata;

    /** @var MetadataInterface */
    private $taxonMetadata;

    /** @var MetadataInterface */
    private $attributeMetadata;

    /** @var MetadataInterface */
    private $optionMetadata;

    public function __construct(
        MetadataInterface $priceMetadata,
        MetadataInterface $taxonMetadata,
        MetadataInterface $attributeMetadata,
        MetadataInterface $optionMetadata
    ) {
        $this->priceMetadata = $priceMetadata;
        $this->taxonMetadata = $taxonMetadata;
        $this->attributeMetadata = $attributeMetadata;
        $this->optionMetadata = $optionMetadata;
    }

    public function build(ProductInterface $product, string $localeCode): array
    {
        return array_merge(
            [
                Element::FIELD_ID => $product->getId(),
                Element::FIELD_LOCALE => $localeCode,
                Element::FIELD_TAXON_CODE => iterator_to_array($this->taxonMetadata->getMetadata($product)),
                Element::FIELD_PRICE => iterator_to_array($this->priceMetadata->getMetadata($product)),
            ],
            iterator_to_array($this->attributeMetadata->getMetadata($product)),
            iterator_to_array($this->optionMetadata->getMetadata($product))
        );
    }
}
