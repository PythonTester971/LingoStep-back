<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Mission;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Mission>
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    //    /**
    //     * @return Mission[] Returns an array of Mission objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Mission
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findById(int $id): ?Mission
    {
        return $this->createQueryBuilder('m')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findAllMissionByUser(User $user)
    {
        return $this->createQueryBuilder('m')
            ->join('m.answeredQuestions', 'aq')
            ->join('aq.user', 'u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->groupBy('m.id')
            ->getQuery()
            ->getResult()
        ;
    }
}
