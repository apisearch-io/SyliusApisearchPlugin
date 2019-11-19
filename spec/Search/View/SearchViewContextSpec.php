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

namespace spec\Apisearch\SyliusApisearchPlugin\Search\View;

use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfiguration;
use Apisearch\SyliusApisearchPlugin\Exception\VersionUnavailableException;
use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Apisearch\SyliusApisearchPlugin\Search\View\SearchViewContext;
use PhpSpec\ObjectBehavior;

class SearchViewContextSpec extends ObjectBehavior
{
    function let(SearchInterface $search): void
    {
        $configuration = new ApisearchConfiguration('static', true, true, true, [], []);

        $this->beConstructedWith($configuration, $search);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SearchViewContext::class);
    }

    function it_throws_exception_if_view_mode_is_unavailable(SearchInterface $search): void
    {
        $configuration = new ApisearchConfiguration('others', true, true, true, [], []);
        $this->beConstructedWith($configuration, $search);

        $this->shouldThrow(VersionUnavailableException::class)->during('__construct', [$configuration, $search]);
    }
}
