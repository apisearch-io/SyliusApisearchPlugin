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

use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\MetadataInterface;
use Apisearch\SyliusApisearchPlugin\Transformer\Metadata\PriceMetadata;
use Generator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;

class PriceMetadataSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(PriceMetadata::class);
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
