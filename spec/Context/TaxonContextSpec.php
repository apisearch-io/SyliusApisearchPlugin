<?php

declare(strict_types=1);

namespace spec\Apisearch\SyliusApisearchPlugin\Context;

use Apisearch\SyliusApisearchPlugin\Context\TaxonContext;
use Apisearch\SyliusApisearchPlugin\Context\TaxonContextInterface;
use Apisearch\SyliusApisearchPlugin\Exception\TaxonNotFoundException;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

class TaxonContextSpec extends ObjectBehavior
{
    function let(TaxonRepositoryInterface $taxonRepository, LocaleContextInterface $localeContext): void
    {
        $this->beConstructedWith($taxonRepository, $localeContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonContext::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(TaxonContextInterface::class);
    }

    function it_find_taxon_by_slug(
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext,
        TaxonInterface $taxon
    ): void {
        $slug = 'jeans';

        $localeContext->getLocaleCode()->willReturn('en');
        $taxonRepository->findOneBySlug($slug, 'en')->willReturn($taxon);

        $this->findBySlug($slug)->shouldBeEqualTo($taxon);
    }

    function it_throws_exception_if_taxon_is_null(
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext
    ): void {
        $slug = 'jeans';

        $localeContext->getLocaleCode()->willReturn('en');
        $taxonRepository->findOneBySlug($slug, 'en')->willReturn(null);

        $this->shouldThrow(TaxonNotFoundException::class)->during('findBySlug', [$slug]);
    }
}
