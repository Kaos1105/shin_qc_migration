<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Activity extends Enum
{
    const MEETING = 1;
    const STUDY_GROUP = 2;
    const OTHER = 3;
    const KAIZEN = 4;
}
