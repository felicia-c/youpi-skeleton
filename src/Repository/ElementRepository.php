<?php
namespace App\Repository;

use App\Entity\Element;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ElementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Element::class);
    }

    public function findAll(){
        return $this->findBy(array(),
            array('id' => 'DESC'));
    }

    /**
     * @return Element[]
     */
    public function findAllPublished(): array
    {
        // automatically knows to select Element
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.published = true')
            //->setParameter('published', $published)
            ->orderBy('e.initDate', 'DESC')
            ->getQuery();

        return $qb->execute();

        // to get just one result:
        // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }
}