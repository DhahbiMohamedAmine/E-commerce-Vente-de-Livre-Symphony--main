<?php

namespace App\Repository;

use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderDetails>
 *
 * @method OrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetails[]    findAll()
 * @method OrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailsRepository extends ServiceEntityRepository
{
    private $entityManager ;
    public function __construct(ManagerRegistry $registry , EntityManagerInterface $e)
    {
        $this->entityManager=$e;
        parent::__construct($registry, OrderDetails::class);
    }

//    /**
//     * @return OrderDetails[] Returns an array of OrderDetails objects
//     */
public function getOrders(): array
{
    $conn = $this->entityManager->getConnection();

    $sql = '
    SELECT livres_id, SUM(quantity) as quantity,l.titre
    FROM order_details o join livres l on l.id=o.livres_id
    GROUP BY livres_id
    order by quantity';

    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();
    
    return $resultSet->fetchAllAssociative();
}
//    public function findOneBySomeField($value): ?OrderDetails
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
