<?php

namespace App\Http\Controllers;

use App\Models\ActivityApproval;
use App\Models\Circle;
use App\Models\Department;
use App\Enums\AccessAuthority;
use App\Enums\Activity;
use App\Enums\StaticConfig;
use App\Enums\UseClassificationEnum;
use App\Models\Place;
use App\Models\PromotionCircle;
use App\Models\User;
use App\Enums\RoleIndicatorEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionCircleOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_where = array();
        if (isset($_GET['year'])) {
            if ($_GET['year'] != 'all') {
                $arr_where['year'] = $_GET['year'];
            }
        } else {
            $arr_where['year'] = date('Y');
        }
        if (isset($_GET['circle'])) {
            if ($_GET['circle'] != 'all') {
                $arr_where['circle_id'] = $_GET['circle'];
            }
        }
        $sort = 'year';
        $sortType = 'asc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if (isset($_GET['sortType']) && ($_GET['sortType'] == 'asc' || $_GET['sortType'] = 'desc')) {
            $sortType = $_GET['sortType'];
        }

        $circle_selected = DB::table('circles')->select('id as circle_id_dummy', 'circle_name', 'use_classification as useClassy');
        $promotion = DB::table('promotion_circles')
            ->where($arr_where)
            ->leftJoinSub($circle_selected, 'circle_selected', function ($join) {
                $join->on('promotion_circles.circle_id', '=', 'circle_selected.circle_id_dummy');
            })
            ->where('useClassy', UseClassificationEnum::USES)
            ->orderBy($sort, $sortType)
            ->paginate(20);

        $circle_list = DB::table('circles')->where('use_classification', 2)->get();
        $min_year = DB::table('promotion_circles')->min('year');
        $max_year = DB::table('promotion_circles')->max('year');
        return view('promotion-circle-office.list',
            [
                'paginate' => $promotion,
                'circle_list' => $circle_list,
                'min_year' => $min_year,
                'max_year' => $max_year,
                'current_year' => isset($_GET['year']) ? $_GET['year'] : date('Y'),
                'current_circle' => isset($_GET['circle']) ? $_GET['circle'] : null
            ]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $current_year = date("Y");
        if (isset($_GET['year'])) {
            $current_year = $_GET['year'];
        }
        $promotion = PromotionCircle::find($id);
        $review_last_year = "";
        $promotion_circle_last_year = DB::table('promotion_circles')->where('year', ($current_year - 1))->first();
        $promotion_circle = $promotion;
        if (isset($_GET['year'])) {
            $promotion_circle = DB::table('promotion_circles')->where('year', $current_year)->where('circle_id', $promotion->circle_id)->first();
        }
        if ($promotion_circle_last_year) {
            $review_last_year = $promotion_circle_last_year->review_this_year;
        }
        $first_year = DB::table('promotion_circles')->where('circle_id', $promotion->circle_id)->orderby('year', 'ASC')->first();
        if (!$first_year) {
            $first_year = $current_year;
        }
        $last_year = DB::table('promotion_circles')->where('circle_id', $promotion->circle_id)->orderby('year', 'DESC')->first();
        if (!$last_year) {
            $last_year = $current_year;
        }
        $circle = Circle::find($promotion->circle_id);
        $meeting_list = DB::table('activities')->where('circle_id', $circle->id)->where('activity_category', \App\Enums\Activity::MEETING)
            ->whereYear('date_execution', $current_year)
            ->get();
        $total_time = 0.0;
        if (isset($meeting_list)) {
            $total_time = DB::table('activities')
                ->where('circle_id', $circle->id)
                ->where('activity_category', Activity::MEETING)
                ->whereYear('date_execution', $current_year)
                ->sum('time_span');
        }
        $theme_complete = DB::table('activities')->where('circle_id', $circle->id)->whereYear('date_execution', $current_year)->get();
        $study_list = DB::table('activities')->where('circle_id', $circle->id)->where('activity_category', \App\Enums\Activity::STUDY_GROUP)
            ->whereYear('date_execution', $current_year)
            ->get();

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
        if (isset($promotion_circle->id)) {
            $member_list = Db::table('members')->where('circle_id', $promotion_circle->circle_id)->get();
            $count = 0;
            if (count($member_list) > 0) {
                foreach ($member_list as $member_list_item) {
                    $circle_level = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle->id)->where('member_id', $member_list_item->id)->first();
                    if ($circle_level) {
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
                    if (isset($promotion_circle_last_year->id)) {
                        $circle_level_last_year = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle_last_year->id)->where('member_id', $member_list_item->id)->first();
                        if ($circle_level_last_year) {
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
                        }
                    }
                }
                if ($count > 0) {
                    $arr_x_current_year = array(
                        round($total_current_year_axis_x_i / $count, 1),
                        round($total_current_year_axis_x_ro / $count, 1),
                        round($total_current_year_axis_x_ha / $count, 1),
                        round($total_current_year_axis_x_ni / $count, 1),
                        round($total_current_year_axis_x_ho / $count, 1),
                    );
                    $arr_y_current_year = array(
                        round($total_current_year_axis_y_i / $count, 1),
                        round($total_current_year_axis_y_ro / $count, 1),
                        round($total_current_year_axis_y_ha / $count, 1),
                        round($total_current_year_axis_y_ni / $count, 1),
                        round($total_current_year_axis_y_ho / $count, 1),
                    );
                    $arr_x_last_year = array(
                        round($total_last_year_axis_x_i / $count, 1),
                        round($total_last_year_axis_x_ro / $count, 1),
                        round($total_last_year_axis_x_ha / $count, 1),
                        round($total_last_year_axis_x_ni / $count, 1),
                        round($total_last_year_axis_x_ho / $count, 1),
                    );
                    $arr_y_last_year = array(
                        round($total_last_year_axis_y_i / $count, 1),
                        round($total_last_year_axis_y_ro / $count, 1),
                        round($total_last_year_axis_y_ha / $count, 1),
                        round($total_last_year_axis_y_ni / $count, 1),
                        round($total_last_year_axis_y_ho / $count, 1),
                    );
                }
            }
        }

        $list_member = DB::table('members')->where('circle_id', $circle->id)->orderBy('is_leader', 'desc')->orderBy('display_order', 'asc')->get();
        $theme_list = DB::table('themes')->whereRaw('circle_id = ' . $circle->id . ' AND YEAR(date_start) <= ' . $current_year . ' AND ' . $current_year . ' <= YEAR(date_expected_completion) AND (date_actual_completion IS NULL OR ' . $current_year . ' <= YEAR(date_actual_completion))')->get();
        $plan_by_year = DB::table('plan_by_years')->where('year', $current_year)->first();
        $circle_promoter = User::find($circle->user_id);
        $place = Place::find($circle->place_id);
        $place_caretaker = User::find($place->user_id);
        $department = Department::find($place->department_id);
        $department_manager = User::find($department->bs_id);
        $department_caretaker = User::find($department->sw_id);
        if (isset($promotion_circle->id)) {
            $circle_promoter_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_circle->id)->where('approver_classification', RoleIndicatorEnum::CIRCLE_PROMOTER)->first();
            $place_caretaker_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_circle->id)->where('approver_classification', RoleIndicatorEnum::PLACE_CARETAKER)->first();
            $department_caretaker_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_circle->id)->where('approver_classification', RoleIndicatorEnum::DEPARTMENT_CARETAKER)->first();
            $department_manager_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_circle->id)->where('approver_classification', RoleIndicatorEnum::DEPARTMENT_MANAGER)->first();
        }
        $meeting_month = DB::table('activities')->where('activity_category', Activity::MEETING)
            ->where('circle_id', $circle->id)
            ->whereYear('date_execution', $current_year)
            ->whereNotNull('date_execution');
        $meeting_month_1 = (clone $meeting_month)->whereMonth('date_execution', 1)->count();
        $meeting_month_2 = (clone $meeting_month)->whereMonth('date_execution', 2)->count();
        $meeting_month_3 = (clone $meeting_month)->whereMonth('date_execution', 3)->count();
        $meeting_month_4 = (clone $meeting_month)->whereMonth('date_execution', 4)->count();
        $meeting_month_5 = (clone $meeting_month)->whereMonth('date_execution', 5)->count();
        $meeting_month_6 = (clone $meeting_month)->whereMonth('date_execution', 6)->count();
        $meeting_month_7 = (clone $meeting_month)->whereMonth('date_execution', 7)->count();
        $meeting_month_8 = (clone $meeting_month)->whereMonth('date_execution', 8)->count();
        $meeting_month_9 = (clone $meeting_month)->whereMonth('date_execution', 9)->count();
        $meeting_month_10 = (clone $meeting_month)->whereMonth('date_execution', 10)->count();
        $meeting_month_11 = (clone $meeting_month)->whereMonth('date_execution', 11)->count();
        $meeting_month_12 = (clone $meeting_month)->whereMonth('date_execution', 12)->count();
        $array_meeting = array(
            $meeting_month_1,
            $meeting_month_2,
            $meeting_month_3,
            $meeting_month_4,
            $meeting_month_5,
            $meeting_month_6,
            $meeting_month_7,
            $meeting_month_8,
            $meeting_month_9,
            $meeting_month_10,
            $meeting_month_11,
            $meeting_month_12,
        );

        $time_meeting_month_1 = (clone $meeting_month)->whereMonth('date_execution', 1)->sum('time_span');
        $time_meeting_month_2 = (clone $meeting_month)->whereMonth('date_execution', 2)->sum('time_span');
        $time_meeting_month_3 = (clone $meeting_month)->whereMonth('date_execution', 3)->sum('time_span');
        $time_meeting_month_4 = (clone $meeting_month)->whereMonth('date_execution', 4)->sum('time_span');
        $time_meeting_month_5 = (clone $meeting_month)->whereMonth('date_execution', 5)->sum('time_span');
        $time_meeting_month_6 = (clone $meeting_month)->whereMonth('date_execution', 6)->sum('time_span');
        $time_meeting_month_7 = (clone $meeting_month)->whereMonth('date_execution', 7)->sum('time_span');
        $time_meeting_month_8 = (clone $meeting_month)->whereMonth('date_execution', 8)->sum('time_span');
        $time_meeting_month_9 = (clone $meeting_month)->whereMonth('date_execution', 9)->sum('time_span');
        $time_meeting_month_10 = (clone $meeting_month)->whereMonth('date_execution', 10)->sum('time_span');
        $time_meeting_month_11 = (clone $meeting_month)->whereMonth('date_execution', 11)->sum('time_span');
        $time_meeting_month_12 = (clone $meeting_month)->whereMonth('date_execution', 12)->sum('time_span');
        $array_time = array($time_meeting_month_1,
            $time_meeting_month_2,
            $time_meeting_month_3,
            $time_meeting_month_4,
            $time_meeting_month_5,
            $time_meeting_month_6,
            $time_meeting_month_7,
            $time_meeting_month_8,
            $time_meeting_month_9,
            $time_meeting_month_10,
            $time_meeting_month_11,
            $time_meeting_month_12,
        );

        $theme_complete_month = DB::table('themes')
            ->where('circle_id', $circle->id)
            ->whereYear('date_actual_completion', $current_year);
        $theme_complete_month_1 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 1)->get();
        $theme_complete_month_2 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 2)->get();
        $theme_complete_month_3 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 3)->get();
        $theme_complete_month_4 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 4)->get();
        $theme_complete_month_5 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 5)->get();
        $theme_complete_month_6 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 6)->get();
        $theme_complete_month_7 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 7)->get();
        $theme_complete_month_8 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 8)->get();
        $theme_complete_month_9 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 9)->get();
        $theme_complete_month_10 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 10)->get();
        $theme_complete_month_11 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 11)->get();
        $theme_complete_month_12 = (clone $theme_complete_month)->whereMonth('date_actual_completion', 12)->get();

        $study_month = DB::table('activities')
            ->where('activity_category', Activity::STUDY_GROUP)
            ->where('circle_id', $circle->id)
            ->whereYear('date_execution', $current_year);
        $study_month_1 = (clone $study_month)->whereMonth('date_execution', 1)->get();
        $study_month_2 = (clone $study_month)->whereMonth('date_execution', 2)->get();
        $study_month_3 = (clone $study_month)->whereMonth('date_execution', 3)->get();
        $study_month_4 = (clone $study_month)->whereMonth('date_execution', 4)->get();
        $study_month_5 = (clone $study_month)->whereMonth('date_execution', 5)->get();
        $study_month_6 = (clone $study_month)->whereMonth('date_execution', 6)->get();
        $study_month_7 = (clone $study_month)->whereMonth('date_execution', 7)->get();
        $study_month_8 = (clone $study_month)->whereMonth('date_execution', 8)->get();
        $study_month_9 = (clone $study_month)->whereMonth('date_execution', 9)->get();
        $study_month_10 = (clone $study_month)->whereMonth('date_execution', 10)->get();
        $study_month_11 = (clone $study_month)->whereMonth('date_execution', 11)->get();
        $study_month_12 = (clone $study_month)->whereMonth('date_execution', 12)->get();

        $kaizen_month = DB::table('activities')
            ->where('activity_category', Activity::KAIZEN)
            ->where('circle_id', $circle->id)
            ->whereYear('date_execution', $current_year);
        $kaizen_month_1 = (clone $kaizen_month)->whereMonth('date_execution', 1)->get();
        $kaizen_month_2 = (clone $kaizen_month)->whereMonth('date_execution', 2)->get();
        $kaizen_month_3 = (clone $kaizen_month)->whereMonth('date_execution', 3)->get();
        $kaizen_month_4 = (clone $kaizen_month)->whereMonth('date_execution', 4)->get();
        $kaizen_month_5 = (clone $kaizen_month)->whereMonth('date_execution', 5)->get();
        $kaizen_month_6 = (clone $kaizen_month)->whereMonth('date_execution', 6)->get();
        $kaizen_month_7 = (clone $kaizen_month)->whereMonth('date_execution', 7)->get();
        $kaizen_month_8 = (clone $kaizen_month)->whereMonth('date_execution', 8)->get();
        $kaizen_month_9 = (clone $kaizen_month)->whereMonth('date_execution', 9)->get();
        $kaizen_month_10 = (clone $kaizen_month)->whereMonth('date_execution', 10)->get();
        $kaizen_month_11 = (clone $kaizen_month)->whereMonth('date_execution', 11)->get();
        $kaizen_month_12 = (clone $kaizen_month)->whereMonth('date_execution', 12)->get();

        return view('promotion-circle-office.view',
            [
                'promotion_circle' => $promotion_circle,
                'review_last_year' => $review_last_year,
                'first_year' => $first_year->year,
                'last_year' => $last_year->year,
                'current_year' => $current_year,
                'circle_name' => $circle->circle_name,
                'arr_x_current_year' => $arr_x_current_year,
                'arr_y_current_year' => $arr_y_current_year,
                'arr_x_last_year' => $arr_x_last_year,
                'arr_y_last_year' => $arr_y_last_year,
                'count_meeting' => isset($meeting_list) ? count($meeting_list) : 0,
                'total_time' => $total_time,
                'count_theme_complete' => isset($theme_complete) ? count($theme_complete) : 0,
                'count_meeting_study' => isset($study_list) ? count($study_list) : 0,
                'list_member' => $list_member,
                'plan_by_year' => isset($plan_by_year) ? $plan_by_year : null,
                'circle_promoter' => isset($circle_promoter) ? $circle_promoter : null,
                'place_caretaker' => isset($place_caretaker) ? $place_caretaker : null,
                'department_manager' => isset($place_caretaker) ? $department_manager : null,
                'department_caretaker' => isset($department_caretaker) ? $department_caretaker : null,
                'circle_promoter_approval' => isset($circle_promoter_approval) ? $circle_promoter_approval : null,
                'place_caretaker_approval' => isset($place_caretaker_approval) ? $place_caretaker_approval : null,
                'department_caretaker_approval' => isset($department_caretaker_approval) ? $department_caretaker_approval : null,
                'department_manager_approval' => isset($department_manager_approval) ? $department_manager_approval : null,
                'array_meeting' => $array_meeting,
                'theme_complete_month_1' => $theme_complete_month_1,
                'theme_complete_month_2' => $theme_complete_month_2,
                'theme_complete_month_3' => $theme_complete_month_3,
                'theme_complete_month_4' => $theme_complete_month_4,
                'theme_complete_month_5' => $theme_complete_month_5,
                'theme_complete_month_6' => $theme_complete_month_6,
                'theme_complete_month_7' => $theme_complete_month_7,
                'theme_complete_month_8' => $theme_complete_month_8,
                'theme_complete_month_9' => $theme_complete_month_9,
                'theme_complete_month_10' => $theme_complete_month_10,
                'theme_complete_month_11' => $theme_complete_month_11,
                'theme_complete_month_12' => $theme_complete_month_12,
                'study_month_1' => $study_month_1,
                'study_month_2' => $study_month_2,
                'study_month_3' => $study_month_3,
                'study_month_4' => $study_month_4,
                'study_month_5' => $study_month_5,
                'study_month_6' => $study_month_6,
                'study_month_7' => $study_month_7,
                'study_month_8' => $study_month_8,
                'study_month_9' => $study_month_9,
                'study_month_10' => $study_month_10,
                'study_month_11' => $study_month_11,
                'study_month_12' => $study_month_12,
                'theme_list' => $theme_list,
                'kaizen_month_1' => $kaizen_month_1,
                'kaizen_month_2' => $kaizen_month_2,
                'kaizen_month_3' => $kaizen_month_3,
                'kaizen_month_4' => $kaizen_month_4,
                'kaizen_month_5' => $kaizen_month_5,
                'kaizen_month_6' => $kaizen_month_6,
                'kaizen_month_7' => $kaizen_month_7,
                'kaizen_month_8' => $kaizen_month_8,
                'kaizen_month_9' => $kaizen_month_9,
                'kaizen_month_10' => $kaizen_month_10,
                'kaizen_month_11' => $kaizen_month_11,
                'kaizen_month_12' => $kaizen_month_12,
                'array_time' => $array_time
            ]);
    }
    public function removeStamp(Request $request) {
            $this->erase_stamp($request->line_id, $request->month_stamp);
            return redirect()->back();
    }

    private function erase_stamp($id, $month) {
        $approval = ActivityApproval::find($id);
        if (isset($approval)) {
            switch ($month) {
                case 0:
                    $approval->user_approved = null;
                    $approval->date_approved = null;
                    break;
                case 1:
                    $approval->user_jan = null;
                    $approval->date_jan = null;
                    break;
                case 2:
                    $approval->user_feb = null;
                    $approval->date_feb = null;
                    break;
                case 3:
                    $approval->user_mar = null;
                    $approval->date_mar = null;
                    break;
                case 4:
                    $approval->user_apr = null;
                    $approval->date_apr = null;
                    break;
                case 5:
                    $approval->user_may = null;
                    $approval->date_may = null;
                    break;
                case 6:
                    $approval->user_jun = null;
                    $approval->date_jun = null;
                    break;
                case 7:
                    $approval->user_jul = null;
                    $approval->date_jul = null;
                    break;
                case 8:
                    $approval->user_aug = null;
                    $approval->date_aug = null;
                    break;
                case 9:
                    $approval->user_sep = null;
                    $approval->date_sep = null;
                    break;
                case 10:
                    $approval->user_oct = null;
                    $approval->date_oct = null;
                    break;
                case 11:
                    $approval->user_nov = null;
                    $approval->date_nov = null;
                    break;
                case 12:
                    $approval->user_dec = null;
                    $approval->date_dec = null;
                    break;
                default:
                    break;
            }
            $approval->save();
        }
    }
}
