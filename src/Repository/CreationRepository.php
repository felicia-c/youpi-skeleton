<?php

namespace App\Repository;

use App\Entity\Creation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Creation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creation[]    findAll()
 * @method Creation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Creation::class);
    }

/*
    public function findAll(){
        return $this->findBy(array(),
            array('id' => 'DESC'));
    }
*/
    /**
     * @return Creation[]
     */
    public function findAllPublished(): array
    {
        // automatically knows to select Element
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.published = true')
            //->setParameter('published', $published)
            ->orderBy('c.achievement_date', 'DESC')
            ->getQuery();

        return $qb->execute();

        // to get just one result:
        // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }


    public function findByCategory($category): array
    {
        $qb = $this->createQueryBuilder('creation')
            ->leftjoin ('creation.category','c')
            ->where('c.name = :name')
            ->setParameter('name', $category)
            ->getQuery();

        return $qb->execute();
    }
    // /**
    //  * @return Creation[] Returns an array of Creation objects
    //  */
/*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.category = :val')
            ->setParameter('val', $value)
            //->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
*/

    /*
    public function findOneBySomeField($value): ?Creation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
