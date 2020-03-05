<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 16:56
 */

namespace DBSynchronizer\SecondaryDB\Repository;


use DateTime;
use DBSynchronizer\SecondaryDB\Entity\OutletOwner;
use Doctrine\ORM\EntityRepository;

class OutletOwnerRepository extends EntityRepository
{
    /**
     * @param DateTime|null $dateTime
     * @return array|OutletOwner[]|mixed
     */
    public function findAll(DateTime $dateTime = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select('ot')
            ->from(OutletOwner::class, 'ot')
            ->orderBy('ot.id', 'asc');

        if ($dateTime) {
            $query->where('ot.createdAt > :createdAt');
            $query->setParameter('createdAt', $dateTime);
        }
        return $query->getQuery()->getResult();
    }
}