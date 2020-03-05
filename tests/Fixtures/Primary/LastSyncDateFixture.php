<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 17:37
 */

namespace DBSynchronizer\Tests\Fixtures\Primary;


use DateTime;
use DBSynchronizer\Enum\LastSyncStatuses;
use DBSynchronizer\PrimaryDB\Entity\LastSync;
use Exception;

/**
 * Class LastSyncDateFixture
 * @package DBSynchronizer\Tests\Fixtures\Primary
 */
class LastSyncDateFixture
{
    /**
     * @return LastSync
     * @throws Exception
     */
    public static function build(): LastSync
    {
        $lastSync = new LastSync();
        $lastSync
            ->setStatus(LastSyncStatuses::SUCCESSES)
            ->setDateTime(new DateTime());

        return $lastSync;
    }
}