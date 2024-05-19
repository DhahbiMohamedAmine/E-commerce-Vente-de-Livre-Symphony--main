<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $entityManager ;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $e)
    {
        $this->entityManager=$e;
        parent::__construct($registry, Order::class);
    }

    public function getNBOrders(): array
{
    $conn = $this->entityManager->getConnection();

    $sql = '
    SELECT o.user_id_id, u.email, COUNT(*) AS orders FROM `order` o JOIN `user` u ON u.id = o.user_id_id GROUP BY o.user_id_id, u.email';

    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();
    
    return $resultSet->fetchAllAssociative();
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
