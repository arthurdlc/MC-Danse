<?php

namespace App\Repository;

use App\Entity\StageEvenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StageEvenement>
 *
 * @method StageEvenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method StageEvenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method StageEvenement[]    findAll()
 * @method StageEvenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StageEvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StageEvenement::class);
    }

    public function save(StageEvenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StageEvenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
       /**
    * @return Spectacle[] Returns an array of Spectacle objects     
    */
    public function findAllInTheFutur(): array
    {
        $now = new \DateTime();
    
        return $this->createQueryBuilder('s')
            ->andWhere('s.date >= :now') // Inclut les spectacles à partir de maintenant
            ->andWhere('s.date > :val') // Exclut les spectacles du jour même
            ->setParameter('now', $now)
            ->setParameter('val', $now->format('Y-m-d')) // Utilisation de la date au format 'Y-m-d' pour exclure les spectacles du jour même
            ->orderBy('s.date', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return StageEvenement[] Returns an array of StageEvenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StageEvenement
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
