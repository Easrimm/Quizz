<?php

namespace App\Repository;

use App\Entity\QuizzEffectue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizzEffectue>
 *
 * @method QuizzEffectue|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizzEffectue|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizzEffectue[]    findAll()
 * @method QuizzEffectue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizzEffectueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizzEffectue::class);
    }

    public function save(QuizzEffectue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QuizzEffectue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return QuizzEffectue[] Returns an array of QuizzEffectue objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuizzEffectue
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
