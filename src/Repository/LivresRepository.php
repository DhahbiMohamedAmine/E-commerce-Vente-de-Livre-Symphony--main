<?php

namespace App\Repository;

use App\Entity\Livres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livres>
 *
 * @method Livres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livres[]    findAll()
 * @method Livres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livres::class);
    }

    /**
     * @return Livres[] Returns an array of Livres objects
     */
    public function search($searchData): array
    {
        $qb = $this->createQueryBuilder('l');

        if ($searchData->getTitre()) {
            $qb->andWhere('l.titre = :titre');
            $qb->setParameter('titre', $searchData->getTitre());
        }

        if ($searchData->getAuteur()) {
            $qb->andWhere('l.auteur = :auteur');
            $qb->setParameter('auteur', $searchData->getAuteur());
        }

        if ($searchData->getCategorie()) {
            $qb->andWhere('l.Categorie = :categorie');
            $qb->setParameter('categorie', $searchData->getCategorie()->getId());
        }

        return $qb->getQuery()->getResult();
    }
}