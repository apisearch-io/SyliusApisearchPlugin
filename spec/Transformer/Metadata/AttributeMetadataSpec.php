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

namespace spec\Apisearch\SyliusApisearchPlugin\Transformer\Metadata;

use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\AttributeMetadata;
use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\MetadataInterface;
use Generator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;

class AttributeMetadataSpec extends ObjectBehavior
{
    function let(ApisearchConfigurationInterface $configuration): void
    {
        $this->beConstructedWith($configuration);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributeMetadata::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(MetadataInterface::class);
    }

    function it_get_metadata(ProductInterface $product, ApisearchConfigurationInterface $configuration): void
    {
        $product->getId()->willReturn(1);
        $configuration->getFilters()->willReturn([]);

        $this->getMetadata($product)->shouldBeAnInstanceOf(Generator::class);
    }
}
