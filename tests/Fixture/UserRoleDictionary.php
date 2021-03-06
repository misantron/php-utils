<?php

namespace Utility\Tests\Fixture;

use Utility\Object\AbstractDictionary;

class UserRoleDictionary extends AbstractDictionary
{
    const
        ROLE_USER = 1,
        ROLE_MODERATOR = 2,
        ROLE_ADMIN = 3
    ;

    protected static $titleMapping = [
        self::ROLE_USER => 'User',
        self::ROLE_MODERATOR => 'Moderator',
        self::ROLE_ADMIN => 'Admin'
    ];
}