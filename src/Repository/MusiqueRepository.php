<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Musique;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Musique>
 *
 * @method Musique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musique[]    findAll()
 * @method Musique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Musique::class);
    }

    public function save(Musique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Musique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Musique[] Returns an array of Musique objects
    */
   public function findMusiqueByGenre(?Genre $genre): array
   {
       return $this->createQueryBuilder('m')
           ->andWhere('m.genre = :genreId')
           ->setParameter('genreId', $genre->getId())
           ->orderBy('m.id', 'DEsc')
           ->setMaxResults(5)
           ->getQuery()
           ->getResult()
       ;
   }

    /**
    * @param int $page
    * @return PaginatorInterface
    */

   public function findMusique(int $page): PaginationInterface
   {
      $data =  $this->createQueryBuilder('m')
           ->orderBy('m.id', 'Desc')
           ->getQuery()
           ->getResult();

        $musiques = $this->paginator->paginate($data, $page,5);
       
        return $musiques;
   }



//    public function findOneBySomeField($value): ?Musique
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
