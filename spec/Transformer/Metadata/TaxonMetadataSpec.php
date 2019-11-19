<?php

declare(strict_types=1);

namespace spec\Apisearch\SyliusApisearchPlugin\Transformer\Metadata;

use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\MetadataInterface;
use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\TaxonMetadata;
use Generator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;

class TaxonMetadataSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonMetadata::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(MetadataInterface::class);
    }

    function it_get_metadata(ProductInterface $product): void
    {
        $product->getId()->willReturn(1);

        $this->getMetadata($product)->shouldBeAnInstanceOf(Generator::class);
    }
}
