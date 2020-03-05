<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 20:47
 */

namespace DBSynchronizer\Tests\Fixtures\Secondary;


use DateTime;
use DBSynchronizer\SecondaryDB\Entity\OutletOwner;
use Exception;

/**
 * Class OutletOwnerFixture
 * @package DBSynchronizer\Tests\Fixtures\Secondary
 */
class OutletOwnerFixture
{
    /**
     * @return OutletOwner
     * @throws Exception
     */
    public static function build(): OutletOwner
    {
        $outletOwner = new OutletOwner();
        $outletOwner
            ->setName('SecondaryOutletOwnerName')
            ->setOwnerName('SecondaryOwnerName')
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime());
        return $outletOwner;
    }
}