<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 11:30
 */

namespace Imper86\AllegroApiBundle\DependencyInjection;


use Imper86\AllegroApiBundle\Factory\AllegroServiceFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class Imper86AllegroApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loggerService = $config['logger_service_id'] ? new Reference($config['logger_service_id']) : null;

        $definition = $container->getDefinition(AllegroServiceFactory::class);
        $definition->setArgument(0, $config);
        $definition->setArgument(2, $loggerService);

        $container->setParameter('imper86.allegro_api.entity_manager', 'default');
        $container->setParameter('imper86.allegro_api.doctrine', true);
    }
}
