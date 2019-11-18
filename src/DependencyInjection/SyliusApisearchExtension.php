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

use Apisearch\SyliusApisearchPlugin\Element;
use Mmoreram\BaseBundle\DependencyInjection\BaseExtension;
use function sprintf;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SyliusApisearchExtension extends BaseExtension
{
    protected function getConfigFilesLocation(): string
    {
        return __DIR__ . '/../Resources/config';
    }

    protected function getConfigFiles(array $config): array
    {
        return [
            'services',
        ];
    }

    protected function getParametrizationValues(array $config): array
    {
        return [
            'sylius_apisearch.config.version' => $config['version'],
            'sylius_apisearch.config.show_price_filter' => $config['show_price_filter'],
            'sylius_apisearch.config.show_text_search' => $config['show_text_search'],
            'sylius_apisearch.config.enable_autocomplete' => $config['enable_autocomplete'],
            'sylius_apisearch.config.filters' => $config['filters'],
            'sylius_apisearch.config.pagination_size' => $config['pagination_size'],
            'sylius_apisearch.config.template' => Element::$versionTemplate[$config['version']],
        ];
    }

    protected function postLoad(array $config, ContainerBuilder $container)
    {
        $container->setAlias(
            'sylius_apisearch.repository',
            sprintf('apisearch.repository_transformable_%1$s.%1$s', Element::INDEX_NAME)
        );
    }

    protected function getConfigurationInstance(): ? ConfigurationInterface
    {
        return new Configuration($this->getAlias());
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'sylius_apisearch';
    }
}
