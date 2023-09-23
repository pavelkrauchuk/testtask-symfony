<?php

namespace App\Repository;

use App\Entity\Parameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parameters>
 *
 * @method Parameters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parameters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parameters[]    findAll()
 * @method Parameters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parameters::class);
    }

    /**
     * @param Parameters $entity
     * @param bool $flush
     * @return void
     */
    public function add(Parameters $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Parameters $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Parameters $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string $parameter
     * @return Parameters|null
     * @throws NonUniqueResultException
     */
    public function findByName(string $parameter): Parameters|null
    {
        $dql = /** @lang DQL */ 'SELECT p FROM App\Entity\Parameters p WHERE p.paramName = :paramName';

        return $this->_em
            ->createQuery($dql)
            ->setParameter('paramName', $parameter)
            ->getOneOrNullResult();
    }
}
