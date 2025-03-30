<?php

namespace App\Repository;

use App\Entity\Event;
use App\Enum\EventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Builder\EnumCase;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    public function findLastEvents(int $number):array{
        return $this->createQueryBuilder('event')
                    ->orderBy("event.id", "DESC")
                    ->setMaxResults($number)
                    ->getQuery()
                    ->getResult();
    }

    public function findEventByTitles(array $term):array{
        return $this->createQueryBuilder("event")
                    ->where("event.title LIKE :term")
                    ->setParameter(":term", "%".$term["search"]."%")
                    ->getQuery()
                    ->getResult();
    }

    public function findByType(EventType $type): array{
        return $this->createQueryBuilder("event")
                    ->where("event.eventType = :type")
                    ->setParameter("type", $type->value)
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
