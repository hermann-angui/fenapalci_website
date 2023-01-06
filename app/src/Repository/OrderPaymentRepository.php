<?php

namespace App\Repository;

use App\Entity\OrderPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderPayment>
 *
 * @method OrderPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderPayment[]    findAll()
 * @method OrderPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPayment::class);
    }

    public function add(OrderPayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OrderPayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
