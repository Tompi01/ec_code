<?php

namespace App\Repository;

use App\Entity\BookRead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;

/**
 * @extends ServiceEntityRepository<BookRead>
 */
class BookReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookRead::class);
    }

    /**
     * Method to find all ReadBook entities by user_id
     * @param int $userId
     * @param bool $readState
     * @return array
     */
    public function findByUserId(int $userId, bool $readState): array
    {
        return $this->createQueryBuilder('r')
            ->join(Book::class, 'b')
            ->where('r.user_id = :userId')
            ->andWhere('r.is_read = :isRead')
            ->setParameter('userId', $userId)
            ->setParameter('isRead', $readState)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function getBooksRead() {
    
        return $this->createQueryBuilder('r')
        ->join(Book::class, 'b')
        // ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        ->setFirstResult(0)
        ->setMaxResults(5)
        ->getQuery();
    
    }

    public function search($value) {

        $conn = $this->getEntityManager()->getConnection();

        $stmt = "SELECT book.name, AVG(book_read.rating) as NUM FROM book_read INNER JOIN book ON book.id = book_read.book_id INNER JOIN category ON category.id where book.name like '%". $value ."%' GROUP BY book_id;";
        
        $resultSet = $conn->executeQuery($stmt, ['val' => $value]);

        return $resultSet->fetchAllAssociative();
    }

}