<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static UserRole FOO()
 * @method static UserRole BAR()
 * @method static UserRole BAZ()
 */
final class UserRole extends Enum
{
    const __default = self::FOO;

    const admin = 'admin';
    const author = 'author';
    const subscriber = 'subscriber';
}