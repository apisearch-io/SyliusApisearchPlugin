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

use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Context\TaxonContextInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\SyliusApisearchPlugin\Exception\VersionUnavailableException;
use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Pagerfanta\Adapter\NullAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonController
{
    /**
     * @var ApisearchConfigurationInterface
     */
    private $configuration;

    /**
     * @var TaxonContextInterface
     */
    private $taxonContext;

    /**
     * @var SearchInterface
     */
    private $search;

    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * TaxonStaticController constructor.
     *
     * @param ApisearchConfigurationInterface $configuration
     * @param TaxonContextInterface $taxonContext
     * @param SearchInterface $search
     * @param EngineInterface $templatingEngine
     */
    public function __construct(
        ApisearchConfigurationInterface $configuration,
        TaxonContextInterface $taxonContext,
        SearchInterface $search,
        EngineInterface $templatingEngine
    ) {
        $this->configuration = $configuration;
        $this->taxonContext = $taxonContext;
        $this->search = $search;
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws VersionUnavailableException
     */
    public function __invoke(Request $request): Response
    {
        $taxon = $this->taxonContext->findByRequest($request);

        $version = $this->configuration->getVersion();
        switch ($this->configuration->getVersion()) {
            case Element::VERSION_STATIC:
                $parameters = $this->versionStatic($request, $taxon);

                break;
            case Element::VERSION_DYNAMIC:
                $parameters = $this->versionDynamic();

                break;
            default:
                throw new VersionUnavailableException($version);
        }

        return $this->templatingEngine->renderResponse(
            $request->get('template'),
            \array_merge(
                [
                    'taxon' => $taxon,
                    'configuration' => $this->configuration,
                ],
                $parameters
            )
        );
    }

    /**
     * @param Request $request
     * @param TaxonInterface $taxon
     *
     * @return array
     */
    private function versionStatic(Request $request, TaxonInterface $taxon): array
    {
        $result = $this->search->getResult($request, $taxon);

        $pagerAdapter = new NullAdapter($result->getTotalItems());
        $pagerfanta = new Pagerfanta($pagerAdapter);

        $pagerfanta->setMaxPerPage(
            $this->search->getCurrentSize($request)
        );

        $pagerfanta->setCurrentPage(
            $this->search->getCurrentPage($request)
        );

        return [
            'result' => $result,
            'pager' => $pagerfanta,
        ];
    }

    /**
     * @return array
     */
    private function versionDynamic(): array
    {
        return [];
    }
}
