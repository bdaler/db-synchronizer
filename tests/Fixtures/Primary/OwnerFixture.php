<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 20:42
 */

namespace DBSynchronizer\Tests\Fixtures\Primary;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Owner;
use Exception;

/**
 * Class OwnerFixture
 * @package DBSynchronizer\Tests\Fixtures\Primary
 */
class OwnerFixture
{
    /**
     * @return Owner
     * @throws Exception
     */
    public static function build(): Owner
    {
        $owner = new Owner();
        $owner
            ->setName('PrimaryOwnerName')
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime());
        return $owner;
    }
}