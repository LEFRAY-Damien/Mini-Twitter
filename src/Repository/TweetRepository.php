<?php

namespace App\Repository;

use App\Entity\Tweet;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Tweet>
 */
class TweetRepository extends ServiceEntityRepository
{
    // public function __construct(ManagerRegistry $registry)
    // {
    //     parent::__construct($registry, Tweet::class);
    // }

    private PaginatorInterface $paginatorInterface;
    
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Tweet::class);
        $this->paginatorInterface = $paginatorInterface;
    }

    /**
     * search tweets 
     * @param SearchData $searchData
     * @return PaginationInterface
     */
    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $query = $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'DESC');

        if (!empty($searchData->q)) {
            $query->andWhere('p.content LIKE :q')
                  ->setParameter('q', "%{$searchData->q}%");
        }

        return $this->paginatorInterface->paginate(
            $query->getQuery(),
            $searchData->page ?? 1,
            10
        );
    }

}
