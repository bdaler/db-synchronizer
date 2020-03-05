<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 18:26
 */

namespace DBSynchronizer\Tests\Fixtures\Secondary;


use DBSynchronizer\Enum\LastSyncStatuses;
use DBSynchronizer\SecondaryDB\Entity\LastSync;

class LastSyncDateFixture
{
    public static function build()
    {
        $lastSync = new LastSync();
        $lastSync
            ->setDateTime(new \DateTime())
            ->setStatus(LastSyncStatuses::SUCCESSES);
        return $lastSync;
    }
}