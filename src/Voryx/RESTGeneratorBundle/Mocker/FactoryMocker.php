<?php


namespace Voryx\RESTGeneratorBundle\Mocker;


class FactoryMocker
{
    /**
     * @var array
     */
    private $mockers;

    public function __construct() {
        $this->mockers = array();
    }

    public function addMocker($name, MockerInterface $mocker) {
        $this->mockers[$name] = $mocker;
    }

    public function getMockers() {
        return $this->mockers;
    }
}