<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Enums\Activity;
use App\Enums\Common;
use App\PromotionCircle;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionCircleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_year = date("Y");
        if(isset($_GET['year'])){
            $current_year = $_GET['year'];
        }
        $promotion_cirlce = DB::table('promotion_circles')->where('year', $current_year)
            ->where('circle_id', session('circle.id'))
            ->first();
        if(!$promotion_cirlce){
            $promotion_cirlce = new PromotionCircle();
            $promotion_cirlce->year = $current_year;
        }
        $review_last_year = "";
        $promotion_circle_last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', ($current_year - 1))->first();
        if($promotion_circle_last_year){
            $review_last_year = $promotion_circle_last_year->review_this_year;
        }
        $first_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->orderby('year', 'ASC')->first();
        if(!$first_year){
            $first_year = $current_year;
        }
        $last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->orderby('year', 'DESC')->first();
        if(!$last_year){
            $last_year = $current_year;
        }
        $meeting_list = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', Activity::MEETING)
            ->whereYear('date_execution', $current_year)->get();
        $total_time = 0.0;
        if(isset($meeting_list)){
            $total_time = DB::table('activities')
                ->where('circle_id', session('circle.id'))
                ->where('activity_category', Activity::MEETING)
                ->whereYear('date_execution', $current_year)
                ->sum('time_span');
        }
        $theme_complete = DB::table('themes')->where('circle_id', session('circle.id'))->whereYear('date_actual_completion', $current_year)->get();
        $study_list = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', Activity::STUDY_GROUP)->whereYear('date_execution', $current_year)->get();
        $kaizen_list = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', Activity::KAIZEN)->whereYear('date_execution', $current_year)->get();

        $arr_x_current_year = array(0.0, 0.0, 0.0, 0.0, 0.0);
        $arr_y_current_year = array(0.0, 0.0, 0.0, 0.0, 0.0);
        $arr_x_last_year = array(0.0, 0.0, 0.0, 0.0, 0.0);
        $arr_y_last_year = array(0.0, 0.0, 0.0, 0.0, 0.0);
        $total_current_year_axis_x_i = 0.0;
        $total_current_year_axis_x_ro = 0.0;
        $total_current_year_axis_x_ha = 0.0;
        $total_current_year_axis_x_ni = 0.0;
        $total_current_year_axis_x_ho = 0.0;
        $total_current_year_axis_y_i = 0.0;
        $total_current_year_axis_y_ro = 0.0;
        $total_current_year_axis_y_ha = 0.0;
        $total_current_year_axis_y_ni = 0.0;
        $total_current_year_axis_y_ho = 0.0;
        $total_last_year_axis_x_i = 0.0;
        $total_last_year_axis_x_ro = 0.0;
        $total_last_year_axis_x_ha = 0.0;
        $total_last_year_axis_x_ni = 0.0;
        $total_last_year_axis_x_ho = 0.0;
        $total_last_year_axis_y_i = 0.0;
        $total_last_year_axis_y_ro = 0.0;
        $total_last_year_axis_y_ha = 0.0;
        $total_last_year_axis_y_ni = 0.0;
        $total_last_year_axis_y_ho = 0.0;
        /*get data member*/
        $member_list = Db::table('members')->where('circle_id', session('circle.id'))->get();
        $count = 0;
        $count_last_year = 0;
        if(count($member_list) > 0){
            foreach($member_list as $member_list_item){
                $circle_level = DB::table('circle_levels')->where('promotion_circle_id', $promotion_cirlce->id)->where('member_id', $member_list_item->id)->first();
                if($circle_level){
                    $total_current_year_axis_x_i += $circle_level->axis_x_i;
                    $total_current_year_axis_x_ro += $circle_level->axis_x_ro;
                    $total_current_year_axis_x_ha += $circle_level->axis_x_ha;
                    $total_current_year_axis_x_ni += $circle_level->axis_x_ni;
                    $total_current_year_axis_x_ho += $circle_level->axis_x_ho;
                    $total_current_year_axis_y_i += $circle_level->axis_y_i;
                    $total_current_year_axis_y_ro += $circle_level->axis_y_ro;
                    $total_current_year_axis_y_ha += $circle_level->axis_y_ha;
                    $total_current_year_axis_y_ni += $circle_level->axis_y_ni;
                    $total_current_year_axis_y_ho += $circle_level->axis_y_ho;
                    $count++;
                }
                if(isset($promotion_circle_last_year->id)){
                    $circle_level_last_year = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle_last_year->id)->where('member_id', $member_list_item->id)->first();
                    if($circle_level_last_year){
                        $total_last_year_axis_x_i += $circle_level_last_year->axis_x_i;
                        $total_last_year_axis_x_ro += $circle_level_last_year->axis_x_ro;
                        $total_last_year_axis_x_ha += $circle_level_last_year->axis_x_ha;
                        $total_last_year_axis_x_ni += $circle_level_last_year->axis_x_ni;
                        $total_last_year_axis_x_ho += $circle_level_last_year->axis_x_ho;
                        $total_last_year_axis_y_i += $circle_level_last_year->axis_y_i;
                        $total_last_year_axis_y_ro += $circle_level_last_year->axis_y_ro;
                        $total_last_year_axis_y_ha += $circle_level_last_year->axis_y_ha;
                        $total_last_year_axis_y_ni += $circle_level_last_year->axis_y_ni;
                        $total_last_year_axis_y_ho += $circle_level_last_year->axis_y_ho;
                        $count_last_year++;
                    }
                }
            }
            if($count > 0){
                $arr_x_current_year = array(
                    round($total_current_year_axis_x_i/$count, 1),
                    round($total_current_year_axis_x_ro/$count, 1),
                    round($total_current_year_axis_x_ha/$count, 1),
                    round($total_current_year_axis_x_ni/$count, 1),
                    round($total_current_year_axis_x_ho/$count, 1),
                );
                $arr_y_current_year = array(
                    round($total_current_year_axis_y_i/$count, 1),
                    round($total_current_year_axis_y_ro/$count, 1),
                    round($total_current_year_axis_y_ha/$count, 1),
                    round($total_current_year_axis_y_ni/$count, 1),
                    round($total_current_year_axis_y_ho/$count, 1),
                );

            }
            if($count_last_year > 0){
                $arr_x_last_year = array(
                    round($total_last_year_axis_x_i/$count_last_year, 1),
                    round($total_last_year_axis_x_ro/$count_last_year, 1),
                    round($total_last_year_axis_x_ha/$count_last_year, 1),
                    round($total_last_year_axis_x_ni/$count_last_year, 1),
                    round($total_last_year_axis_x_ho/$count_last_year, 1),
                );
                $arr_y_last_year = array(
                    round($total_last_year_axis_y_i/$count_last_year, 1),
                    round($total_last_year_axis_y_ro/$count_last_year, 1),
                    round($total_last_year_axis_y_ha/$count_last_year, 1),
                    round($total_last_year_axis_y_ni/$count_last_year, 1),
                    round($total_last_year_axis_y_ho/$count_last_year, 1),
                );
            }
        }

        $list_member = DB::table('members')->where('circle_id', session('circle.id'))->orderBy('is_leader', 'desc')->orderBy('display_order', 'asc')->get();

        return view('promotioncircle.view',
            [
                'promotion_cirlce' => $promotion_cirlce,
                'review_last_year' => $review_last_year,
                'first_year' => isset($first_year->year)? $first_year->year : $current_year,
                'last_year' => isset($last_year->year)? $last_year->year : $current_year,
                'arr_x_current_year' => $arr_x_current_year,
                'arr_y_current_year' => $arr_y_current_year,
                'arr_x_last_year' => $arr_x_last_year,
                'arr_y_last_year' => $arr_y_last_year,
                'count_meeting' => isset($meeting_list)? count($meeting_list) : 0,
                'total_time' => $total_time,
                'count_theme_complete' => isset($theme_complete)? count($theme_complete) : 0,
                'count_meeting_study' => isset($study_list)? count($study_list) : 0,
                'count_kaizen' => isset($kaizen_list)? count($kaizen_list) : 0,
                'list_member' => $list_member
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $year = date("Y");
        if(isset($_GET['year'])){
            $year = $_GET['year'];
        }
        $promotion_cirlce = DB::table('promotion_circles')->where('year', $year)->where('circle_id', session('circle.id'))->first();
        if($promotion_cirlce){
            return redirect()->route('promotioncircle.edit', ['id' => session('circle.id')]);
        }
        $last_year = $year - 1;
        $review_last_year = "";
        $promotion_cirlce_last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', $last_year)->first();
        if($promotion_cirlce_last_year){
            $review_last_year = $promotion_cirlce_last_year->review_this_year;
        }
        return view('promotioncircle.add', ['year' => $year, 'review_last_year' => $review_last_year]);
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
                'target_number_of_meeting'=> 'nullable|numeric',
                'target_hour_of_meeting'=> 'nullable|numeric',
                'target_case_complete'=> 'nullable|numeric',
                'improved_cases'=> 'nullable|numeric',
                'objectives_of_organizing_classe'=> 'nullable|numeric',
            ],
            [
                'target_number_of_meeting.numeric'=> StaticConfig::$Number,
                'target_hour_of_meeting.numeric'=> StaticConfig::$Number,
                'target_case_complete.numeric'=> StaticConfig::$Number,
                'improved_cases.numeric'=> StaticConfig::$Number,
                'objectives_of_organizing_classe.numeric'=> StaticConfig::$Number,
            ]
        );
        $promotion = new PromotionCircle();
        $promotion->circle_id = $request->circle_id;
        $promotion->year = $request->year;
        $promotion->motto_of_the_workplace = $request->motto_of_the_workplace;
        $promotion->motto_of_circle = $request->motto_of_circle;
        $promotion->axis_x = 0.0;
        $promotion->axis_y = 0.0;
        $promotion->target_number_of_meeting = $request->target_number_of_meeting;
        $promotion->target_hour_of_meeting = $request->target_hour_of_meeting;
        $promotion->target_case_complete = $request->target_case_complete;
        $promotion->improved_cases = $request->improved_cases;
        $promotion->objectives_of_organizing_classe = $request->objectives_of_organizing_classe;
        $promotion->review_this_year = $request->review_this_year;
        $promotion->comment_promoter = $request->comment_promoter;
        /*Doan nay khong hien thi tren view*/
        $promotion->display_order = 100;
        $promotion->statistic_classification = 2;
        $promotion->use_classification = 2;
        $promotion->note = '';
        $promotion->created_by = Auth::id();
        $promotion->updated_by = Auth::id();
        $promotion->save();
        return redirect()->route('promotioncircle.index', ['year' => $request->year]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion_cirlce = PromotionCircle::find($id);
        if(session('circle.id') != $promotion_cirlce->circle_id){
            $current_year = date("Y");
            if(isset($_GET['year'])){
                $current_year = $_GET['year'];
            }
            return redirect()->route('promotioncircle.create', ['year' => $current_year]);
        }
        $last_year = $promotion_cirlce->year - 1;
        $review_last_year = "";
        $promotion_cirlce_last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', $last_year)->first();
        if($promotion_cirlce_last_year){
            $review_last_year = $promotion_cirlce_last_year->review_this_year;
        }
        $circle = Circle::find($promotion_cirlce->circle_id);
        return view('promotioncircle.edit',['promotion_cirlce' => $promotion_cirlce, 'review_last_year' => $review_last_year, 'circle_name' => $circle->circle_name ]);
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
                'target_number_of_meeting'=> 'nullable|numeric',
                'target_hour_of_meeting'=> 'nullable|numeric',
                'target_case_complete'=> 'nullable|numeric',
                'improved_cases'=> 'nullable|numeric',
                'objectives_of_organizing_classe'=> 'nullable|numeric',
            ],
            [
                'target_number_of_meeting.numeric'=> StaticConfig::$Number,
                'target_hour_of_meeting.numeric'=> StaticConfig::$Number,
                'target_case_complete.numeric'=> StaticConfig::$Number,
                'improved_cases.numeric'=> StaticConfig::$Number,
                'objectives_of_organizing_classe.numeric'=> StaticConfig::$Number,
            ]
        );
        $promotion = PromotionCircle::find($id);
        $promotion->motto_of_the_workplace = $request->motto_of_the_workplace;
        $promotion->motto_of_circle = $request->motto_of_circle;
        $promotion->target_number_of_meeting = $request->target_number_of_meeting;
        $promotion->target_hour_of_meeting = $request->target_hour_of_meeting;
        $promotion->target_case_complete = $request->target_case_complete;
        $promotion->improved_cases = $request->improved_cases;
        $promotion->objectives_of_organizing_classe = $request->objectives_of_organizing_classe;
        $promotion->review_this_year = $request->review_this_year;
        $promotion->comment_promoter = $request->comment_promoter;
        $promotion->updated_by = Auth::id();
        $promotion->save();
        return redirect()->route('promotioncircle.index', ['year' => $promotion->year]);
    }

    /**
     * history
     */
    public function show()
    {
        $sort = 'year';
        $sortType = 'asc';
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }
        $promition_circle = DB::table('promotion_circles')
            ->where('circle_id', session('circle.id'))
            ->orderBy($sort, $sortType)->paginate(20);
        return view('promotioncircle.history',['paginate' => $promition_circle]);
    }
}
