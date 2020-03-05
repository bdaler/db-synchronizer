<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 20:45
 */

namespace DBSynchronizer\Tests\Fixtures\Primary;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Sku;
use Exception;

/**
 * Class SkuFixture
 * @package DBSynchronizer\Tests\Fixtures\Primary
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
            ->setName('PrimarySKU')
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime())
            ->setStock(10);
        return $sku;
    }
}