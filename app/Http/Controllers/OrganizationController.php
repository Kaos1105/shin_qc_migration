<?php

namespace App\Http\Controllers;

use App\Enums\RoleIndicatorEnum;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function index()
    {
        $promotion_committee = DB::table('users')->where('role_indicator', RoleIndicatorEnum::PROMOTION_COMMITTEE)->first(); //推進委員
        $promotion_officer = DB::table('users')->where('role_indicator', RoleIndicatorEnum::PROMOTION_OFFICER)->first(); //推進責任者
        $executive_director = DB::table('users')->where('role_indicator', RoleIndicatorEnum::EXECUTIVE_DIRECTOR)->first(); //事務局長
        $office_worker = DB::table('users')->where('role_indicator', RoleIndicatorEnum::OFFICE_WORKER)->get(); //事務局員
        $department = DB::table('departments')->where('use_classification', 2)->orderBy('display_order', 'desc')->get(); //事務局員

        return view('organization.index',
            [
                'promotion_committee' => isset($promotion_committee)? $promotion_committee : null,
                'promotion_officer' => isset($promotion_officer)? $promotion_officer : null,
                'executive_director' => isset($executive_director)? $executive_director : null,
                'office_worker' => isset($office_worker)? $office_worker : null,
                'department' => isset($department)? $department : null,
            ]
        );
    }
}
