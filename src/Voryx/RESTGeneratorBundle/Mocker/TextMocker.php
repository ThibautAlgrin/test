<?php


namespace Voryx\RESTGeneratorBundle\Mocker;


class TextMocker extends AbstractMocker
{
    /**
     * @param array $mapping
     * @return string
     */
    public function generate(array $mapping = []) {
        return implode(" ", $this->faker->words(20));
    }
}