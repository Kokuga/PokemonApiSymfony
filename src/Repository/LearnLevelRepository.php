<?php

namespace App\Repository;

use App\Entity\LearnLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LearnLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method LearnLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method LearnLevel[]    findAll()
 * @method LearnLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LearnLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LearnLevel::class);
    }

    // /**
    //  * @return LearnLevel[] Returns an array of LearnLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LearnLevel
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
