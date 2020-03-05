<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 17:59
 */

namespace DBSynchronizer\Enum;


use MyCLabs\Enum\Enum;

/**
 * Class LastSyncStatuses
 * @package DBSynchronizer\Enum
 */
class LastSyncStatuses extends Enum
{
    /**
     * Успешно обновили
     */
    public const SUCCESSES = 1;
    /**
     * Успеха нет :)
     */
    public const FAILED = 2;
}