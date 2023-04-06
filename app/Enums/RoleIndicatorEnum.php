<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RoleIndicatorEnum extends Enum
{
    const PROMOTION_OFFICER = 1; //推進責任者
    const PROMOTION_COMMITTEE = 2; //推進委員
    const DEPARTMENT_MANAGER = 3; //部門責任者
    const DEPARTMENT_CARETAKER  = 4; //部門世話人
    const PLACE_CARETAKER = 5; //世話人
    const CIRCLE_PROMOTER =6; //サークル推進者
    const MEMBER = 7; //メンバー
    const EXECUTIVE_DIRECTOR = 8; //事務局長
    const OFFICE_WORKER = 9; //事務局員
}
