<?php


namespace Voryx\RESTGeneratorBundle\Mocker;

use Doctrine\ORM\EntityManager;

class RelationToOneMocker implements MockerInterface
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
        $entity = $this->em->getRepository($mapping['targetEntity'])->findOneBy([]);
        if ($entity == NULL) {
            throw new \Exception("No found some Entity for class [%s]", $mapping['targetEntity']);
        }
        return $entity->getId();
    }

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }
}