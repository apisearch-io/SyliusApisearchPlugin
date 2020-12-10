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

namespace spec\Apisearch\SyliusApisearchPlugin\Transformer;

use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\MetadataInterface;
use Apisearch\SyliusApisearchPlugin\Transformer\MetadataBuilder;
use Apisearch\SyliusApisearchPlugin\Transformer\MetadataBuilderInterface;
use Generator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;

class MetadataBuilderSpec extends ObjectBehavior
{
    function let(
        MetadataInterface $priceMetadata,
        MetadataInterface $taxonMetadata,
        MetadataInterface $attributeMetadata,
        MetadataInterface $optionMetadata
    ): void {
        $this->beConstructedWith($priceMetadata, $taxonMetadata, $attributeMetadata, $optionMetadata);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(MetadataBuilder::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(MetadataBuilderInterface::class);
    }

    function it_build_metadata(
        MetadataInterface $priceMetadata,
        MetadataInterface $taxonMetadata,
        MetadataInterface $attributeMetadata,
        MetadataInterface $optionMetadata,
        ProductInterface $product
    ): void {
        $product->getId()->willReturn(1);

        $priceMetadata->getMetadata($product)->willReturn($this->generator());
        $taxonMetadata->getMetadata($product)->willReturn($this->generator());
        $attributeMetadata->getMetadata($product)->willReturn($this->generator());
        $optionMetadata->getMetadata($product)->willReturn($this->generator());

        $this->build($product, 'en_US');
    }

    private function generator(): Generator
    {
        yield [];
    }
}
