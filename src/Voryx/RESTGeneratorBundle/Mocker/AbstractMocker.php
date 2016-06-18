<?php


namespace Voryx\RESTGeneratorBundle\Mocker;

use Faker\Factory;

class AbstractMocker implements MockerInterface
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function __construct() {
        $this->faker = Factory::create();
    }

    /**
     * @param array $mapping
     * @return mixed
     * @throws \Exception
     */
    public function generate(array $mapping = []) {
        throw new \Exception('You must create generate function');
    }
}