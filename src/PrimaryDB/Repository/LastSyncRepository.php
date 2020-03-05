<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 17:26
 */

namespace DBSynchronizer\PrimaryDB\Repository;


use DBSynchronizer\Enum\LastSyncStatuses;
use DBSynchronizer\PrimaryDB\Entity\LastSync;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class LastSyncRepository extends EntityRepository
{
    /**
     * @return null|LastSync
     * @throws NonUniqueResultException
     */
    public function lastSuccessSynced(): ?LastSync
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select('ls')
            ->from(LastSync::class, 'ls')
            ->where('ls.status = :status')
            ->orderBy('ls.id', 'asc')
            ->setMaxResults(1);
        $query->setParameter('status', LastSyncStatuses::SUCCESSES);

        return $query->getQuery()->getOneOrNullResult();
    }
}