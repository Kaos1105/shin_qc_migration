<?php

namespace App\Http\Controllers;

use App\CircleLevel;
use App\Member;
use App\PromotionCircle;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CircleLevelController extends Controller
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
            return view('circle-level.list',
                [
                    'member_list' => $member_list,
                    'promotion_circle_id' => $_GET['promotion_circle_id'],
                    'year' => $promotion_circle->year,
                    'circle_name' => $circle_name
                ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(isset($_GET['member_id']) && isset($_GET['promotion_circle_id'])){
            $member_list = Db::table('members')->where('members.circle_id', session('circle.id'))->where('members.id', '<>', $_GET['member_id'])
                ->join('users', 'users.id', '=', 'members.user_id')
                ->select('members.id', 'members.department', 'users.name')->get();
            $member = Member::find($_GET['member_id']);

            $circle_level_own = DB::table('circle_levels')->where('promotion_circle_id', $_GET['promotion_circle_id'])->where('member_id', $_GET['member_id'])
                ->select('id', 'axis_x_i', 'axis_x_ro', 'axis_x_ha', 'axis_x_ni', 'axis_x_ho', 'axis_y_i', 'axis_y_ro', 'axis_y_ha', 'axis_y_ni', 'axis_y_ho')->first();
            $promotion_circle = PromotionCircle::find($_GET['promotion_circle_id']);
            $year = $promotion_circle->year;

            $circle_level_lastyear = DB::table('promotion_circles as pc')
                ->join('circle_levels as cl', 'cl.promotion_circle_id', '=', 'pc.id')
                ->where('pc.circle_id', session('circle.id'))
                ->where('pc.year', $year-1)
                ->where('cl.member_id', $_GET['member_id'])
                ->select('cl.id', 'cl.axis_x_i', 'cl.axis_x_ro', 'cl.axis_x_ha', 'cl.axis_x_ni', 'cl.axis_x_ho', 'cl.axis_y_i', 'cl.axis_y_ro', 'cl.axis_y_ha', 'cl.axis_y_ni', 'cl.axis_y_ho')
                ->first();

            $user = User::find($member->user_id);
            return view('circle-level.add',
                [
                    'member_list' => $member_list,
                    'member' => $member,
                    'circle_level_own' => $circle_level_own,
                    'promotion_circle_id' => $_GET['promotion_circle_id'],
                    'year' => $year,
                    'member_name' => $user->name,
                    'circle_level_lastyear' => isset($circle_level_lastyear)? $circle_level_lastyear : null
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $circle_level = new CircleLevel();
        if($request->id != 0){
            $circle_level = CircleLevel::find($request->id);
        }
        $circle_level->promotion_circle_id = $request->promotion_circle_id;
        $circle_level->member_id = $request->member_id;
        $circle_level->axis_x_i = $request->axis_x_i;
        $circle_level->axis_x_ro = $request->axis_x_ro;
        $circle_level->axis_x_ha = $request->axis_x_ha;
        $circle_level->axis_x_ni = $request->axis_x_ni;
        $circle_level->axis_x_ho = $request->axis_x_ho;
        $circle_level->axis_y_i = $request->axis_y_i;
        $circle_level->axis_y_ro = $request->axis_y_ro;
        $circle_level->axis_y_ha = $request->axis_y_ha;
        $circle_level->axis_y_ni = $request->axis_y_ni;
        $circle_level->axis_y_ho = $request->axis_y_ho;

        $circle_level->display_order = 100;
        $circle_level->statistic_classification = 1;
        $circle_level->use_classification = 1;
        $circle_level->created_by = Auth::id();
        $circle_level->updated_by = Auth::id();
        $circle_level->save();

        $total_x_i = 0;
        $total_x_ro = 0;
        $total_x_ha = 0;
        $total_x_ni = 0;
        $total_x_ho = 0;
        $total_y_i = 0;
        $total_y_ro = 0;
        $total_y_ha = 0;
        $total_y_ni = 0;
        $total_y_ho = 0;
        $count = 0;

        $circle_level_list = DB::table('circle_levels as cl')
            ->join('members', 'members.id', '=', 'cl.member_id')
            ->where('cl.promotion_circle_id', $request->promotion_circle_id)
            ->where('members.circle_id', session('circle.id'))
            ->select('cl.id', 'cl.axis_x_i', 'cl.axis_x_ro', 'cl.axis_x_ha', 'cl.axis_x_ni', 'cl.axis_x_ho', 'cl.axis_y_i', 'cl.axis_y_ro', 'cl.axis_y_ha', 'cl.axis_y_ni', 'cl.axis_y_ho')
            ->get();


        foreach($circle_level_list as $item){
            $total_x_i += $item->axis_x_i;
            $total_x_ro += $item->axis_x_ro;
            $total_x_ha += $item->axis_x_ha;
            $total_x_ni += $item->axis_x_ni;
            $total_x_ho += $item->axis_x_ho;
            $total_y_i += $item->axis_y_i;
            $total_y_ro += $item->axis_y_ro;
            $total_y_ha += $item->axis_y_ha;
            $total_y_ni += $item->axis_y_ni;
            $total_y_ho += $item->axis_y_ho;
            $count++;
        }
        if($count > 0){
            $axis_x = round(($total_x_i/$count + $total_x_ro/$count + $total_x_ha/$count + $total_x_ni/$count + $total_x_ho/$count)/5, 1);
            $axis_y = round(($total_y_i/$count + $total_y_ro/$count + $total_y_ha/$count + $total_y_ni/$count + $total_y_ho/$count)/5, 1);
            $promotion_circle = PromotionCircle::find($request->promotion_circle_id);
            $promotion_circle->axis_x = $axis_x;
            $promotion_circle->axis_y = $axis_y;
            $promotion_circle->save();
        }
        return redirect()->route('circlelevel.index', ['promotion_circle_id' => $request->promotion_circle_id]);
    }

}
