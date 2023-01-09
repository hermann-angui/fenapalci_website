<?php

namespace App\Repository;

use App\Entity\DigitalAsset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DigitalAsset>
 *
 * @method DigitalAsset|null find($id, $lockMode = null, $lockVersion = null)
 * @method DigitalAsset|null findOneBy(array $criteria, array $orderBy = null)
 * @method DigitalAsset[]    findAll()
 * @method DigitalAsset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitalAssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalAsset::class);
    }

    public function add(DigitalAsset $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DigitalAsset $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
