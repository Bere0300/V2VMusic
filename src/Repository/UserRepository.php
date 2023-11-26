<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Genre;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
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
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function findByIsActive($isActive = true)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.profil = :profil')
            ->setParameter('profil', $isActive)
            ->orderBy('u.id', 'DESC') // Ajoutez cette ligne pour trier par ID décroissant
            ->setMaxResults(5) // Limite le nombre de résultats à 5
            ->getQuery()
            ->getResult();
    }
 
   /**
    * @param int $page
    * @return PaginatorInterface
    */
   public function findUsers(int $page): PaginationInterface
   {
       $data= $this->createQueryBuilder('u')
           ->orderBy('u.id', 'DESC')
           ->getQuery()
           ->getResult();

        $users = $this->paginator->paginate($data,$page,5,);

        return $users;
   }

    //   /**
    // * @return User[] Returns an array of Musique objects
    // */
    // public function findUser(?Genre $genre): array
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.genres = :genreId')
    //         ->setParameter('genreId', $genre->getId())
    //         ->orderBy('u.id', 'DESC')
    //         ->setMaxResults(5)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
