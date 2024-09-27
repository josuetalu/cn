<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    // Ajoutez ici vos méthodes personnalisées
    // Ajoutez cette méthode
    public function findUndeliveredOrders()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.delivered = :delivered')
            ->setParameter('delivered', false)
            ->getQuery()
            ->getResult();
    }    
}