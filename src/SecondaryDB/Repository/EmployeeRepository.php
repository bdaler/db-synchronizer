<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 16:56
 */

namespace DBSynchronizer\SecondaryDB\Repository;


use DateTime;
use DBSynchronizer\SecondaryDB\Entity\Employee;
use Doctrine\ORM\EntityRepository;

class EmployeeRepository extends EntityRepository
{
    /**
     * @param DateTime|null $dateTime
     * @return Employee[]
     */
    public function findAll(DateTime $dateTime = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select('e')
            ->from(Employee::class, 'e')
            ->orderBy('e.id', 'asc');
        if ($dateTime) {
            $query->where('e.createdAt > :createdAt');
            $query->setParameter('createdAt', $dateTime);
        }
        return $query->getQuery()->getResult();
    }
}