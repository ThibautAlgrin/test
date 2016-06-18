<?php


namespace Voryx\RESTGeneratorBundle\Mocker;


interface MockerInterface
{
    /**
     * @param array $mapping
     * @return mixed
     */
    public function generate(array $mapping = []);
}