<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AccessAuthority extends Enum
{
    const USER = 1;
    const System = 0;
    const ADMIN = 2;
}
