<?php

namespace App\Http\Controllers;

use App\Models\CircleLevel;
use App\Models\Member;
use App\Models\PromotionCircle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CircleLevelOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset($_GET['promotion_circle_id'])){
            $promotion_circle = PromotionCircle::find($_GET['promotion_circle_id']);
            $circle_name = DB::table('circles')->where('id', $promotion_circle->circle_id)->value('circle_name');
            $member_list = Db::table('members')->where('members.circle_id', $promotion_circle->circle_id)
                ->join('users', 'users.id', '=', 'members.user_id')
                ->orderBy('is_leader', 'desc')
                ->select('members.id', 'members.department', 'users.name', 'users.position', 'is_leader')
                ->get();
            return view('circle-level-office.list',
                [
                    'member_list' => $member_list,
                    'promotion_circle_id' => $_GET['promotion_circle_id'],
                    'year' => $promotion_circle->year,
                    'circle_name' => $circle_name
                ]);
        }
    }

}
