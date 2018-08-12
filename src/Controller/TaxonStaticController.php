<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Controller;

use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Context\TaxonContextInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Apisearch\SyliusApisearchPlugin\Exception\VersionUnavailableException;
use Apisearch\SyliusApisearchPlugin\Search\SearchInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonStaticController
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
        if (false === $this->configuration->isVersion(Element::VERSION_STATIC)) {
            throw new VersionUnavailableException(Element::VERSION_STATIC);
        }

        $taxon = $this->taxonContext->findByRequest($request);
        $result = $this->search->getResult($request, $taxon);

        return $this->templatingEngine->renderResponse(
            $request->get('template'),
            [
                'taxon' => $taxon,
                'result' => $result,
            ]
        );
    }
}
