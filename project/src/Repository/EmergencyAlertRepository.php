<?php

namespace App\Repository;

use App\Entity\EmergencyAlert;
use App\Enum\EmergencyAlertStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmergencyAlert>
 */
class EmergencyAlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmergencyAlert::class);
    }

    public function findPublic()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.status IN (:status)')
            ->setParameter('status', [EmergencyAlertStatusEnum::ACTIVE->name, EmergencyAlertStatusEnum::RESOLVED->name, EmergencyAlertStatusEnum::CANCELLED->name])
            ->orderBy('e.createdAt', 'DESC') // Changed to DESC to show newest first
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPublicFiltered(?string $type = null, ?string $location = null)
    {
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.status IN (:status)')
            ->setParameter('status', [EmergencyAlertStatusEnum::ACTIVE->name, EmergencyAlertStatusEnum::RESOLVED->name, EmergencyAlertStatusEnum::CANCELLED->name]);

        if ($type) {
            $qb->andWhere('e.type = :type')
               ->setParameter('type', $type);
        }

        if ($location) {
            // Parse the location string to get latitude and longitude
            $coordinates = explode(',', $location);
            if (count($coordinates) === 2) {
                $latitude = trim($coordinates[0]);
                $longitude = trim($coordinates[1]);

                $qb->andWhere('e.latitude = :latitude AND e.longitude = :longitude')
                   ->setParameter('latitude', $latitude)
                   ->setParameter('longitude', $longitude);
            }
        }

        return $qb->orderBy('e.createdAt', 'DESC') // Changed to DESC to show newest first
                 ->getQuery()
                 ->getResult();
    }

    /**
     * Finds emergency alerts by search term
     *
     * @param string|null $searchTerm
     * @return EmergencyAlert[] Returns an array of EmergencyAlert objects
     */
    public function findBySearchTerm(?string $searchTerm): array
    {
        if (empty($searchTerm)) {
            return $this->findAll();
        }

        $queryBuilder = $this->createQueryBuilder('ea');
        $queryBuilder->leftJoin('ea.createdBy', 'u');

        return $queryBuilder
            ->where('ea.title LIKE :searchTerm')
            ->orWhere('ea.description LIKE :searchTerm')
            ->orWhere('ea.alertLevel LIKE :searchTerm')
            ->orWhere('ea.type LIKE :searchTerm')
            ->orWhere('ea.status LIKE :searchTerm')
            ->orWhere('u.name LIKE :searchTerm')
            ->orWhere('u.surname LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('ea.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return EmergencyAlert[] Returns an array of EmergencyAlert objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmergencyAlert
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
