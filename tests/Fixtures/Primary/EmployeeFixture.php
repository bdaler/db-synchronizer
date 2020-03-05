<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 17:33
 */

namespace DBSynchronizer\Tests\Fixtures\Primary;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Employee;
use Exception;

/**
 * Class EmployeeFixture
 * @package DBSynchronizer\Tests\Fixtures\Primary
 */
class EmployeeFixture
{
    /**
     * @return Employee
     * @throws Exception
     */
    public static function build(): Employee
    {
        $employee = new Employee();
        $employee
            ->setName('Ivan')
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime());

        return $employee;
    }
}