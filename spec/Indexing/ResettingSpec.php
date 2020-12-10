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

use Apisearch\App\AppRepository;
use Apisearch\Model\IndexUUID;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Indexing\Resetting;
use Apisearch\SyliusApisearchPlugin\Indexing\ResettingInterface;
use PhpSpec\ObjectBehavior;

class ResettingSpec extends ObjectBehavior
{
    function let(ApisearchConfigurationInterface $configuration, AppRepository $repository): void
    {
        $this->beConstructedWith($configuration, $repository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Resetting::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(ResettingInterface::class);
    }

    function it_resetting(ApisearchConfigurationInterface $configuration): void
    {
        $configuration->getIndexUUID()->willReturn(IndexUUID::createById('test'));

        $this->reset()->shouldBeFinite();
    }
}
