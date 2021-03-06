<?php

namespace App\Repository;

use App\Entity\AvailableThing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AvailableThing $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AvailableThing $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getRandomThing() : AvailableThing
    {
        $thing = $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $thing[0];
    }

//    /**
//     * @return AvailableThing[] Returns an array of AvailableThing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AvailableThing
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
