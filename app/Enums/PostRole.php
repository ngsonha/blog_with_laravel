<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static PostRole FOO()
 * @method static PostRole BAR()
 * @method static PostRole BAZ()
 */
final class PostRole extends Enum
{
    const __default = self::FOO;

    const active0 = 0;
    const active1 = 1;
    
}