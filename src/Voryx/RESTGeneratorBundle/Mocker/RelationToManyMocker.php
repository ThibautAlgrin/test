<?php


namespace Voryx\RESTGeneratorBundle\Mocker;

use Doctrine\ORM\EntityManager;

class RelationToManyMocker implements MockerInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param array $mapping
     * @return mixed
     * @throws \Exception is $mapping is empty
     */
    public function generate(array $mapping = []) {
        if (empty($mapping)) {
            throw new \Exception("The array mapping in relation mustn't be empty");
        }
        $ids = array();
        foreach ($this->em->getRepository($mapping['targetEntity'])->findBy([], null, 5) as $entity) {
            $ids[] = $entity->getId();
        }
        return $ids;
    }

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }
}