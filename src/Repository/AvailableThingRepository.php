<?php

namespace App\Repository;

use App\Entity\AvailableThing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvailableThing>
 *
 * @method AvailableThing|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvailableThing|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvailableThing[]    findAll()
 * @method AvailableThing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailableThingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvailableThing::class);
    }

    /**
     * @param AvailableThing $entity
     * @param bool $flush
     * @return void
     */
    public function add(AvailableThing $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param AvailableThing $entity
     * @param bool $flush
     * @return void
     */
    public function remove(AvailableThing $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws Exception
     * @throws \ReflectionException
     * @throws ORMException
     */
    public function getRandomThing(): AvailableThing
    {
        $sql = /** @lang SQL */ 'SELECT * FROM available_things ORDER BY RANDOM() LIMIT 1';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAssociative();

        $entityMetadata = $this->getEntityManager()->getClassMetadata(AvailableThing::class);
        $thing = new AvailableThing();

        foreach ($result as $property => $value) {
            if ($entityMetadata->hasField($property)) {
                $reflectionProperty = new \ReflectionProperty(AvailableThing::class, $property);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($thing, $value);
            }
        }

        return $this->getEntityManager()->getReference(AvailableThing::class, $thing->getId());
    }
}
