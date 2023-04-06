<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SummaryClassificationEnum extends Enum
{
    const DoNotSummarize = 0; //集計しない
    const TargetForAggregation = 1; //集計対象とする
}
