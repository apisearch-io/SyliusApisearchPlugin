<?php

declare(strict_types=1);

namespace spec\Apisearch\SyliusApisearchPlugin\Search\View;

use Apisearch\SyliusApisearchPlugin\Search\View\DynamicSearchViewStrategy;
use Apisearch\SyliusApisearchPlugin\Search\View\SearchViewStrategyInterface;
use Exception;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

class DynamicSearchViewStrategySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(DynamicSearchViewStrategy::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(SearchViewStrategyInterface::class);
    }

    function it_throws_exception_on_get_parameters(Request $request, TaxonInterface $taxon): void
    {
        $this->shouldThrow(Exception::class)->during('getParameters', [$request, $taxon]);
    }
}
