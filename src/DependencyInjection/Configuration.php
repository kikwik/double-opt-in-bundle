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

        return $treeBuilder;
    }

}