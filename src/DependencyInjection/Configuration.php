<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\DependencyInjection;

use Apisearch\Query\Aggregation;
use Apisearch\SyliusApisearchPlugin\FilterAdapter;
use Apisearch\SyliusApisearchPlugin\Version;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Apisearch\Query\Filter;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sylius_apisearch');

        $rootNode
            ->children()
                ->enumNode('version')
                    ->values([Version::VERSION_DYNAMIC, Version::VERSION_STATIC])
                    ->defaultValue(Version::VERSION_STATIC)
                ->end()
                ->scalarNode('repository')
                    ->defaultValue('product')
                ->end()
                ->scalarNode('index')
                    ->defaultValue('product')
                ->end()
                ->scalarNode('show_price_filter')
            ->defaultTrue()
                ->end()
                ->scalarNode('show_text_search')
                    ->defaultTrue()
                ->end()
                ->scalarNode('enable_autocomplete')
                    ->defaultTrue()
                ->end()
                ->arrayNode('filters')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('field')
                                ->cannotBeEmpty()
                            ->end()
                            ->enumNode('precision')
                                ->values([Filter::AT_LEAST_ONE, Filter::MUST_ALL, Filter::MUST_ALL_WITH_LEVELS])
                                ->defaultValue(Filter::AT_LEAST_ONE)
                            ->end()
                            ->scalarNode('aggregate')
                                ->defaultTrue()
                            ->end()
                            ->arrayNode('aggregation_sort')
                                ->scalarPrototype()
                                ->end()
                                ->defaultValue(Aggregation::SORT_BY_COUNT_DESC)
                            ->end()
                            ->enumNode('type')
                                ->values([FilterAdapter::FILTER_OPTION, FilterAdapter::FILTER_ATTRIBUTE])
                                ->defaultValue(FilterAdapter::FILTER_ATTRIBUTE)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
