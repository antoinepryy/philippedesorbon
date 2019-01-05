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

}

