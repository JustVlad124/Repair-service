<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByNotAppointedSpecialists(int $stateId, int $clientId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.specialist IS NULL')
            ->andWhere('o.orderState = :val')
            ->setParameter('val', $stateId)
            ->andWhere('o.client = :id')
            ->setParameter('id', $clientId)
            ->getQuery()->getResult();
    }

    public function findNotAppointedOrders(int $stateId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.specialist IS NULL')
            ->andWhere('o.orderState = :val')
            ->setParameter('val', $stateId)
            ->getQuery()->getResult();
    }

    public function findByAppointedSpecialist(int $stateId, int $clientId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.specialist IS NOT NULL')
            ->andWhere('o.orderState = :val')
            ->setParameter('val', $stateId)
            ->andWhere('o.client = :id')
            ->setParameter('id', $clientId)
            ->getQuery()->getResult();
    }

    public function findByArchivedOrders(int $stateId, int $clientId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.specialist IS NOT NULL')
            ->andWhere('o.orderState = :val')
            ->setParameter('val', $stateId)
            ->andWhere('o.client = :id')
            ->setParameter('id', $clientId)
            ->getQuery()->getResult();
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
