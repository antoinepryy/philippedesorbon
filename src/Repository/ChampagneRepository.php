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

}

