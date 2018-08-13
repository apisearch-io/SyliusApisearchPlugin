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
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonDynamicController
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
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * TaxonStaticController constructor.
     *
     * @param ApisearchConfigurationInterface $configuration
     * @param TaxonContextInterface $taxonContext
     * @param EngineInterface $templatingEngine
     */
    public function __construct(
        ApisearchConfigurationInterface $configuration,
        TaxonContextInterface $taxonContext,
        EngineInterface $templatingEngine
    ) {
        $this->configuration = $configuration;
        $this->taxonContext = $taxonContext;
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
        if (false === $this->configuration->isVersion(Element::VERSION_DYNAMIC)) {
            throw new VersionUnavailableException(Element::VERSION_DYNAMIC);
        }

        return $this->templatingEngine->renderResponse(
            $request->get('template'),
            [
                'taxon' => $this->taxonContext->findByRequest($request),
                'configuration' => $this->configuration
            ]
        );
    }
}
