<?php

namespace Utility\Tests\Fixture;

use Utility\Object\AbstractDictionary;

class UserStatusDictionary extends AbstractDictionary
{
    const
        STATUS_PENDING = 0,
        STATUS_ACTIVE = 1,
        STATUS_DECLINED = 2,
        STATUS_BANNED = 3
    ;

    protected static $titleMapping = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_DECLINED => 'Declined',
        self::STATUS_BANNED => 'Banned',
    ];
}