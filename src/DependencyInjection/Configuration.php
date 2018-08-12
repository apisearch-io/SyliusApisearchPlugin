<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\DependencyInjection;

use Apisearch\Query\Aggregation;
use Apisearch\Query\Filter;
use Apisearch\SyliusApisearchPlugin\Element;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
                    ->values([Element::VERSION_DYNAMIC, Element::VERSION_STATIC])
                    ->defaultValue(Element::VERSION_STATIC)
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
                                ->values([Element::FILTER_OPTION, Element::FILTER_ATTRIBUTE])
                                ->defaultValue(Element::FILTER_ATTRIBUTE)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
