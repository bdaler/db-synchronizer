<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 17:24
 */

namespace DBSynchronizer\PrimaryDB\Entity;


use DateTime;

/**
 * Class LastSyncPrimary
 * @package DBSynchronizer\PrimaryDB\Entity
 */
class LastSync
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var int
     */
    private $status;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LastSync
     */
    public function setId(int $id): LastSync
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return LastSync
     */
    public function setDateTime(DateTime $dateTime): LastSync
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return LastSync
     */
    public function setStatus(int $status): LastSync
    {
        $this->status = $status;
        return $this;
    }
}