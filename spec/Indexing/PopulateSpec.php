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

namespace spec\Apisearch\SyliusApisearchPlugin\Indexing;

use Apisearch\Model\Item;
use Apisearch\Model\ItemUUID;
use Apisearch\Repository\TransformableRepository;
use Apisearch\SyliusApisearchPlugin\Indexing\Populate;
use Apisearch\SyliusApisearchPlugin\Indexing\PopulateInterface;
use Apisearch\SyliusApisearchPlugin\Transformer\ProductTransformer;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;

class PopulateSpec extends ObjectBehavior
{
    function let(TransformableRepository $transformableRepository, ProductTransformer $productTransformer): void
    {
        $this->beConstructedWith($transformableRepository, $productTransformer);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Populate::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(PopulateInterface::class);
    }

    function it_is_populate_single(
        ProductInterface $product,
        ProductTransformer $productTransformer
    ): void {
        $product->getId()->willReturn(1);

        $productTransformer->setLocaleCode(null);
        $productTransformer->toItem($product)->willReturn(
            Item::create(
                ItemUUID::createFromArray(
                    [
                        'id' => 'id',
                        'type' => 'type',
                    ]
                )
            )
        );

        $this->populateSingle($product)->shouldBeFinite();
    }

    function it_is_remove_single(
        ProductInterface $product,
        ProductTransformer $productTransformer
    ): void {
        $product->getId()->willReturn(1);

        $productTransformer->setLocaleCode(null);
        $productTransformer->toItem($product)->willReturn(
            Item::create(
                ItemUUID::createFromArray(
                    [
                        'id' => 'id',
                        'type' => 'type',
                    ]
                )
            )
        );

        $this->removeSingle($product)->shouldBeFinite();
    }

    function it_is_update_single(
        ProductInterface $product,
        ProductTransformer $productTransformer
    ): void {
        $product->getId()->willReturn(1);

        $productTransformer->setLocaleCode(null);
        $productTransformer->toItem($product)->willReturn(
            Item::create(
                ItemUUID::createFromArray(
                    [
                        'id' => 'id',
                        'type' => 'type',
                    ]
                )
            )
        );

        $this->updateSingle($product)->shouldBeFinite();
    }
}
