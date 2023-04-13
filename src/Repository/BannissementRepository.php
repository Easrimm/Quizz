<?php

namespace App\Repository;

use App\Entity\Bannissement;
use App\Entity\Utilisateur;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bannissement>
 *
 * @method Bannissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bannissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bannissement[]    findAll()
 * @method Bannissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bannissement::class);
    }

    public function save(Bannissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bannissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Bannissement[] Returns an array of Bannissement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bannissement
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function verifBan(Utilisateur $user){
        $now = new DateTime('now');
        $endBan = $user->getBanRecu()->getDateTimeFin();
        if($endBan){
            if($now > $endBan){
                $em = $this->getEntityManager();
                $em->remove($endBan);
                $em->flush();
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
}
