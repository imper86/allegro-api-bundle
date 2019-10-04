<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 20.09.2019
 * Time: 17:53
 */

namespace Imper86\AllegroApiBundle;


use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Imper86AllegroApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                ['Imper86\AllegroApiBundle\Entity'],
                [realpath(__DIR__ . '/Entity')],
                ['imper86.allegro_api.entity_manager'],
                'imper86.allegro_api.doctrine',
                ['Imper86AllegroApiBundle' => 'Imper86\AllegroApiBundle\Entity']
            )
        );
    }

}
