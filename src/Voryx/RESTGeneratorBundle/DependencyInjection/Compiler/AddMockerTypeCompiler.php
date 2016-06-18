<?php


namespace Voryx\RESTGeneratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddMockerTypeCompiler implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container) {
        $items = $container->findTaggedServiceIds('sonata_admin_tests_generator');
        foreach ($items as $id => $item) {
            foreach ($item as $value) {
                if (isset($value['key'])) {
                    $container
                        ->getDefinition('voryx.restgenerator.factory_mocker')
                        ->addMethodCall('addMocker', array($value['key'], $container->getDefinition($id)));
                }
            }
        }
    }
}