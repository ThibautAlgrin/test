<?php

namespace Voryx\RESTGeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Voryx\RESTGeneratorBundle\DependencyInjection\Compiler\AddMockerTypeCompiler;

class VoryxRESTGeneratorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
//        $container->addCompilerPass(new AddMockerTypeCompiler());
    }
}
