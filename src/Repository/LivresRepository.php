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
   // LivresRepository.php

public function rechercher($test): array
{   return $this->createQueryBuilder('l')
        ->Where('l.titre = :titre')
        ->setParameter('titre', $test->getTitre())
        ->andWhere('l.auteur = :auteur')
        ->setParameter('auteur', $test->getAuteur())
        ->andWhere('l.Categorie = :categorie')
        ->setParameter('categorie', $test->getCategorie()->getId())
        ->getQuery()
        ->getResult();
}
}

