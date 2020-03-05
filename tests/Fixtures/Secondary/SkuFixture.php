<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 20:49
 */

namespace DBSynchronizer\Tests\Fixtures\Secondary;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Sku;
use Exception;

/**
 * Class SkuFixture
 * @package DBSynchronizer\Tests\Fixtures\Secondary
 */
class SkuFixture
{
    /**
     * @return Sku
     * @throws Exception
     */
    public static function build(): Sku
    {
        $sku = new Sku();
        $sku
            ->setName('SecondarySkuName')
            ->setStock(11)
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime());
        return $sku;
    }
}