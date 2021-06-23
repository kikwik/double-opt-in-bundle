<?php

namespace Kikwik\DoubleOptInBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('kikwik_double_opt_in');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('sender_email')->defaultValue('no-reply@example.com')->cannotBeEmpty()->end()
                ->scalarNode('sender_name')->defaultValue('')->end()
                ->booleanNode('remove_secret_code_after_verification')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }

}