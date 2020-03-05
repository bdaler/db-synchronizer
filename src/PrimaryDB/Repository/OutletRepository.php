<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 16:36
 */


namespace DBSynchronizer\PrimaryDB\Repository;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Outlet;
use Doctrine\ORM\EntityRepository;

/**
 * Class OutletRepository
 * @package DBSynchronizer\PrimaryDB\Repository
 */
class OutletRepository extends EntityRepository
{
    /**
     * @param DateTime|null $dateTime
     * @return array|Outlet[]|mixed
     */
    public function findAll(DateTime $dateTime = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select('ot')
            ->from(Outlet::class, 'ot')
            ->orderBy('ot.id', 'asc');

        if ($dateTime) {
            $query->where('ot.createdAt > :createdAt');
            $query->setParameter('createdAt', $dateTime);
        }
        return $query->getQuery()->getResult();
    }
}