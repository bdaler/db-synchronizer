<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 17:38
 */

namespace DBSynchronizer\Tests\Fixtures\Primary;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Outlet;
use Exception;

/**
 * Class OutletFixture
 * @package DBSynchronizer\Tests\Fixtures\Primary
 */
class OutletFixture
{
    /**
     * @return Outlet
     * @throws Exception
     */
    public static function build(): Outlet
    {
        $outlet = new Outlet();
        $outlet
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime())
            ->setName('Apple')
            ->setOwner(OwnerFixture::build());

        return $outlet;
    }
}