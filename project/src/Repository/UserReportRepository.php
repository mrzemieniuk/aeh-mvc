<?php

namespace App\Repository;

use App\Entity\UserReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserReport>
 */
class UserReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserReport::class);
    }

    /**
     * Finds user reports by search term
     *
     * @param string|null $searchTerm
     * @return UserReport[] Returns an array of UserReport objects
     */
    public function findBySearchTerm(?string $searchTerm): array
    {
        if (empty($searchTerm)) {
            return $this->findAll();
        }

        $queryBuilder = $this->createQueryBuilder('ur');
        $queryBuilder->leftJoin('ur.createdBy', 'u');

        return $queryBuilder
            ->where('ur.location LIKE :searchTerm')
            ->orWhere('ur.type LIKE :searchTerm')
            ->orWhere('ur.description LIKE :searchTerm')
            ->orWhere('ur.status LIKE :searchTerm')
            ->orWhere('u.name LIKE :searchTerm')
            ->orWhere('u.surname LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('ur.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return UserReport[] Returns an array of UserReport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserReport
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
