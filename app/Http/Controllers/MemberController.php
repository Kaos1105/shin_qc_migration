<?php

namespace App\Http\Controllers;

use App\Circle;
use App\CircleLevel;
use App\Enums\UseClassificationEnum;
use App\Member;
use App\PromotionCircle;
use App\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class MemberController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($circle_id)
    {
        $circle = Circle::find($circle_id);
        $user = DB::table('users')->where('use_classification', UseClassificationEnum::USES)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('member.add', ['user' => $user, 'circle' => $circle]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'user_id'=> 'required',
                "is_leader"	=>	"required",
                "display_order"	=>	"required|numeric|min:0|max:999999"
            ],
            [
                'user_id.required'=> StaticConfig::$Required,
                'is_leader.required'=> StaticConfig::$Required,
                'display_order.required'=> StaticConfig::$Required,
                'display_order.numeric'=> StaticConfig::$Number,
                'display_order.min'=> StaticConfig::$Min,
                'display_order.max'=> StaticConfig::$Max,
            ]
        );
        $member = new Member;
        $member->user_id = $request->user_id;
        $member->circle_id = $request->circle_id;
        $member->is_leader = $request->is_leader;
        $member->classification = $request->classification;
        $member->department = $request->department;
        $member->display_order = $request->display_order;
        $member->statistic_classification = $request->statistic_classification;
        $member->use_classification = $request->use_classification;
        $member->note = $request->note;
        $member->created_by = Auth::id();
        $member->updated_by = Auth::id();
        $member->save();
        return redirect()->route('circle.show', $request->circle_id);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = Member::find($id);
        $user_updated_by_name = '';
        $user_updated_by = User::find($member->updated_by);
        if($user_updated_by != null){
            $user_updated_by_name = $user_updated_by->name;
        }
        $circle = Circle::find($member->circle_id);
        $user = DB::table('users')->where('use_classification', UseClassificationEnum::USES)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('member.edit',['member' => $member, 'user_updated_by' => $user_updated_by_name, 'circle' => $circle, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'user_id'=> 'required',
                "is_leader"	=>	"required",
                "display_order"	=>	"required|numeric|min:0|max:999999"
            ],
            [
                'user_id.required'=> StaticConfig::$Required,
                'is_leader.required'=> StaticConfig::$Required,
                'display_order.required'=> StaticConfig::$Required,
                'display_order.numeric'=> StaticConfig::$Number,
                'display_order.min'=> StaticConfig::$Min,
                'display_order.max'=> StaticConfig::$Max,
            ]
        );
        $member = Member::find($id);
        $member->user_id = $request->user_id;
        $member->circle_id = $request->circle_id;
        $member->is_leader = $request->is_leader;
        $member->classification = $request->classification;
        $member->department = $request->department;
        $member->display_order = $request->display_order;
        $member->statistic_classification = $request->statistic_classification;
        $member->use_classification = $request->use_classification;
        $member->note = $request->note;
        $member->updated_by = Auth::id();
        $member->save();
        return redirect()->route('circle.show', $request->circle_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $circle_id = $member->circle_id;
        $circle_level_list = DB::table('circle_levels')->where('member_id', $id)->get();
        foreach($circle_level_list as $circle_level){
            $promotion_circle_id = $circle_level->promotion_circle_id;
            $circle_level_delete = CircleLevel::find($circle_level->id);
            $circle_level_delete->delete();
            $circle_level_list = DB::table('circle_levels as cl')
                ->join('members', 'members.id', '=', 'cl.member_id')
                ->where('cl.promotion_circle_id', $promotion_circle_id)
                ->where('members.circle_id', $circle_id)
                ->select('cl.id', 'cl.axis_x_i', 'cl.axis_x_ro', 'cl.axis_x_ha', 'cl.axis_x_ni', 'cl.axis_x_ho', 'cl.axis_y_i', 'cl.axis_y_ro', 'cl.axis_y_ha', 'cl.axis_y_ni', 'cl.axis_y_ho')
                ->get();
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
                $promotion_circle = PromotionCircle::find($promotion_circle_id);
                $promotion_circle->axis_x = $axis_x;
                $promotion_circle->axis_y = $axis_y;
                $promotion_circle->save();
            }
            else{
                $promotion_circle = PromotionCircle::find($promotion_circle_id);
                $promotion_circle->axis_x = 0.0;
                $promotion_circle->axis_y = 0.0;
                $promotion_circle->save();
            }
        }
        $member->delete();
        return redirect()->route('circle.show', $circle_id);
    }
}
