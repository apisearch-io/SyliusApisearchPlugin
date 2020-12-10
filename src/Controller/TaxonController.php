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

namespace Apisearch\SyliusApisearchPlugin\Controller;

use Apisearch\SyliusApisearchPlugin\Context\TaxonContextInterface;
use Apisearch\SyliusApisearchPlugin\Exception\TaxonNotFoundException;
use Apisearch\SyliusApisearchPlugin\Exception\VersionUnavailableException;
use Apisearch\SyliusApisearchPlugin\Search\View\SearchViewContext;
use function array_merge;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonController
{
    /** @var EngineInterface */
    private $templatingEngine;

    /** @var SearchViewContext */
    private $searchViewContext;

    /** @var TaxonContextInterface */
    private $taxonContext;

    /**
     * TaxonStaticController constructor.
     */
    public function __construct(
        EngineInterface $templatingEngine,
        SearchViewContext $searchViewContext,
        TaxonContextInterface $taxonContext
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->searchViewContext = $searchViewContext;
        $this->taxonContext = $taxonContext;
    }

    /**
     * @throws VersionUnavailableException
     */
    public function __invoke(Request $request): Response
    {
        $slug = $request->get('slug', null);
        if (null === $slug) {
            throw new TaxonNotFoundException();
        }

        $taxon = $this->taxonContext->findBySlug($slug);

        return $this->templatingEngine->renderResponse(
            $request->get('template'),
            array_merge(
                [
                    'taxon' => $taxon,
                ],
                $this->searchViewContext->getParameters($request, $taxon)
            )
        );
    }
}
