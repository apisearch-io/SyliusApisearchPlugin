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

namespace Apisearch\SyliusApisearchPlugin\DependencyInjection;

use Apisearch\SyliusApisearchPlugin\Search\Search;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class SyliusApisearchExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $this->processConfiguration($this->getConfiguration([], $container), $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->createParameter($container, $config, 'version');
        $this->createParameter($container, $config, 'repository');
        $this->createParameter($container, $config, 'index');
        $this->createParameter($container, $config, 'show_price_filter');
        $this->createParameter($container, $config, 'show_text_search');
        $this->createParameter($container, $config, 'enable_autocomplete');
        $this->createParameter($container, $config, 'filters');

        $loader->load('services.yml');

        $apiSearchKey = \sprintf(
            'apisearch.repository_%s.%s',
            $config[0]['repository'],
            $config[0]['index']
        );

        $container
            ->register('sylius_apisearch.search', Search::class)
            ->addArgument(new Reference($apiSearchKey))
            ->addArgument(new Reference('sylius_apisearch.configuration'))
            ->addArgument(new Reference('apisearch.url_builder'))
        ;
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     * @param string $key
     */
    private function createParameter(ContainerBuilder $container, array $config, string $key): void
    {
        $container->setParameter(
            \sprintf('sylius_apisearch.config.%s', $key),
            $config[0][$key]
        );
    }
}
