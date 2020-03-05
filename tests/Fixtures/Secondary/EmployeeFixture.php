<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 18:08
 */

namespace DBSynchronizer\Tests\Fixtures\Secondary;


use DateTime;
use DBSynchronizer\SecondaryDB\Entity\Employee;
use Exception;

/**
 * Class EmployeeFixture
 * @package DBSynchronizer\Tests\Fixtures\Secondary
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
            ->setName('Daler')
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime());

        return $employee;
    }
}