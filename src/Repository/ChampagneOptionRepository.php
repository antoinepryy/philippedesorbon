<?php

namespace App\Repository;

use App\Entity\ChampagneOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChampagneOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChampagneOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChampagneOption[]    findAll()
 * @method ChampagneOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampagneOptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChampagneOption::class);
    }

//    /**
//     * @return ChampagneOption[] Returns an array of ChampagneOption objects
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
    public function findOneBySomeField($value): ?ChampagneOption
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
