<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function save(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
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

   public function findOneByEmail($value): ?Utilisateur
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.email = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   public function findOneByResetToken($value): ?Utilisateur
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.resetToken = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   public function findOneByPseudo($value): ?Utilisateur
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.pseudo = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   // Pour la requête AJAX de l'ajout d'amis
   public function findBySearchFriend(Utilisateur $user, $value = null): array
   {
        $friends = $user->getAmis();

        return $this->createQueryBuilder('u')
           ->andWhere('u.pseudo LIKE :val')
           ->setParameter('val', '%'.$value.'%')
           ->andWhere('u != :user')
           ->setParameter('user', $user)
           ->andWhere(':user NOT MEMBER OF u.estAmisDe')
           ->setParameter('user', $user) 
           ->orderBy('u.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult();
   }

   //Pour la recherche AJAX de bannissement
   public function searchByNotBan($pseudo){
        return $this->createQueryBuilder('u')
            ->leftJoin('u.banRecu', 'br')
            ->where('br is NULL')
            ->andWhere('u.pseudo LIKE :pseudo')
            ->setParameter('pseudo', '%'.$pseudo.'%')
            ->orderBy('u.pseudo', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
   }

   //Comptes actuellement en ban
   public function searchCurrentBan(){
        return $this->createQueryBuilder('u')
            ->leftJoin('u.banRecu', 'br')
            ->andWhere('br is NOT NULL')
            ->orderBy('u.pseudo', 'ASC')
            ->getQuery()
            ->getResult();
   }

   // Connexion avec Email ou Pseudo

   public function loadUserByIdentifier($usernameOrEmail): ?UserInterface
    {
        return $this->createQueryBuilder('u')
            ->where('u.pseudo = :query OR u.email = :query')
            ->setParameter('query', $usernameOrEmail)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
