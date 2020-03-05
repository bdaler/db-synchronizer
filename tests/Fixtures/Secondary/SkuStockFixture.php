<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 20:50
 */

namespace DBSynchronizer\Tests\Fixtures\Secondary;


use DateTime;
use DBSynchronizer\SecondaryDB\Entity\SkuStock;
use Exception;

/**
 * Class SkuStockFixture
 * @package DBSynchronizer\Tests\Fixtures\Secondary
 */
class SkuStockFixture
{
    /**
     * @return SkuStock
     * @throws Exception
     */
    public static function build(): SkuStock
    {
        $skuStock = new SkuStock();
        $skuStock
            ->setCreatedAt(new DateTime('yesterday'))
            ->setModifiedAt(new DateTime())
            ->setStock(12)
            ->setSku(SkuFixture::build());
        return $skuStock;
    }
}