<?php

namespace App\Repository;

use App\Entity\Champagne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Champagne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Champagne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Champagne[]    findAll()
 * @method Champagne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampagneRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Champagne::class);
    }

//    /**
//     * @return Champagne[] Returns an array of Champagne objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Champagne
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
