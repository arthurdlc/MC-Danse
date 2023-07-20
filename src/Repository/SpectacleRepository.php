<?php

namespace App\Repository;

use DateTime;
use App\Entity\Spectacle;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Spectacle>
 *
 * @method Spectacle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spectacle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spectacle[]    findAll()
 * @method Spectacle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpectacleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spectacle::class);
    }

    public function save(Spectacle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Spectacle $entity, bool $flush = false): void
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
    

//    public function findOneBySomeField($value): ?Spectacle
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
