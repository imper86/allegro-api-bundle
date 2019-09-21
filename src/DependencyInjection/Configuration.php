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
        $treeBuilder = new TreeBuilder('imper86_allegro_api');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('client_id')->defaultValue('%env(ALLEGRO_CLIENT_ID)%')->end()
                ->scalarNode('client_secret')->defaultValue('%env(ALLEGRO_CLIENT_SECRET)%')->end()
                ->scalarNode('logger_service_id')->defaultValue('logger')->end()
                ->scalarNode('redirect_route')->defaultValue('allegro_api_handle_code')->end()
                ->booleanNode('sandbox')->defaultValue(true)->end()
            ->end();

        return $treeBuilder;
    }
}