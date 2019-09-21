<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 20.09.2019
 * Time: 17:54
 */

namespace Imper86\AllegroApiBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('imper86_allegroapi');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('client_id')->defaultValue('%env(ALLEGRO_CLIENT_ID)%')->isRequired()->end()
                ->scalarNode('client_secret')->defaultValue('%env(ALLEGRO_CLIENT_SECRET)%')->isRequired()->end()
                ->scalarNode('redirect_route')->defaultValue(null)->end()
                ->booleanNode('sandbox')->defaultValue(false)->end()
            ->end();

        return $treeBuilder;
    }
}
