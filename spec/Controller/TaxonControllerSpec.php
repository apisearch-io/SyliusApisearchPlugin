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

namespace spec\Apisearch\SyliusApisearchPlugin\Controller;

use Apisearch\SyliusApisearchPlugin\Context\TaxonContextInterface;
use Apisearch\SyliusApisearchPlugin\Controller\TaxonController;
use Apisearch\SyliusApisearchPlugin\Search\View\SearchViewContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\Taxon;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonControllerSpec extends ObjectBehavior
{
    function let(EngineInterface $templatingEngine,
                 SearchViewContext $searchViewContext,
                 TaxonContextInterface $taxonContext
    ): void {
        $this->beConstructedWith($templatingEngine, $searchViewContext, $taxonContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonController::class);
    }

    function it_return_product_list(
        Request $request,
        EngineInterface $templatingEngine,
        Response $response,
        TaxonContextInterface $taxonContext,
        SearchViewContext $searchViewContext
    ): void {
        $request->get('slug', null)->willReturn('jeans');
        $request->get('template')->willReturn('@Template');

        $taxon = new Taxon();
        $taxonContext->findBySlug('jeans')->willReturn($taxon);

        $searchViewContext->getParameters($request, $taxon)->willReturn([]);

        $templatingEngine->renderResponse('@Template', Argument::any())->willReturn($response);
        $this->__invoke($request);
    }
}
