<?php

namespace App\Repository;

use App\Entity\Money;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Money>
 *
 * @method Money|null find($id, $lockMode = null, $lockVersion = null)
 * @method Money|null findOneBy(array $criteria, array $orderBy = null)
 * @method Money[]    findAll()
 * @method Money[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoneyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Money::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Money $entity, bool $flush = false): void
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
    public function remove(Money $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param int $limit
     * @return array<int, array{id: int, amount: float, isConverted: bool, isTransferred: bool, type: string}>
     */
    public function getNotTransferred(int $limit): array
    {
        $dql = 'SELECT m FROM App\Entity\Money m WHERE m.isTransferred = false AND m.isConverted = false';
        return $this->getEntityManager()->createQuery($dql)->setMaxResults($limit)->getArrayResult();
    }

    /**
     * @param array<int> $arMoneyId
     * @return void
     */
    public function updateTransferred(array $arMoneyId): void
    {
        $dql = 'UPDATE App\Entity\Money m SET m.isTransferred = true WHERE m.id IN (' . implode(', ', $arMoneyId) . ')';
        $this->getEntityManager()->createQuery($dql)->execute();
    }

    public function getNotTransferredCount(): int
    {
        return $this->getEntityManager()->getRepository(Money::class)->count(array(
            'isTransferred' => false,
            'isConverted' => false
        ));
    }

//    /**
//     * @return Money[] Returns an array of Money objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Money
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
