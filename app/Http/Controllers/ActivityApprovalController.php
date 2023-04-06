<?php
namespace App\Http\Controllers;

use App\ActivityApproval;
use App\ActivityApprovalsStatistics;
use App\Department;
use App\Enums\AccessAuthority;
use App\Enums\Activity;
use App\Enums\RoleIndicatorEnum;
use App\Enums\StaticConfig;
use App\Place;
use App\PromotionCircle;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_year = date("Y");
        if (isset($_GET['year'])) {
            $current_year = $_GET['year'];
        }
        $promotion_cirlce = DB::table('promotion_circles')->where('year', $current_year)->where('circle_id', session('circle.id'))->first();
        if (!$promotion_cirlce) {
            $promotion_cirlce = new PromotionCircle();
            $promotion_cirlce->year = $current_year;
        }
        $review_last_year = "";
        $promotion_circle_last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', ($current_year - 1))->first();
        if ($promotion_circle_last_year) {
            $review_last_year = $promotion_circle_last_year->review_this_year;
        }
        $first_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->orderby('year', 'ASC')->first();
        $last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->orderby('year', 'DESC')->first();

        $list_member = DB::table('members')->where('circle_id', session('circle.id'))->orderBy('is_leader', 'desc')->orderBy('display_order', 'asc')->get();
        $theme_list = DB::table('themes')->whereRaw('circle_id = ' . session("circle.id") . ' AND ((date_actual_completion IS NULL AND YEAR(date_start) <= ' . $current_year . ' '
                . 'AND YEAR(date_expected_completion) >= ' . $current_year . ') OR (date_actual_completion IS NOT NULL AND YEAR(date_start) <= ' . $current_year . ' '
                . 'AND YEAR(date_actual_completion) >= ' . $current_year . '))')->get();

        $unique_members = DB::table('members')->where('circle_id', session('circle.id'))->groupBy('user_id')->pluck('user_id');

        $plan_by_year = DB::table('plan_by_years')->where('year', $current_year)->first();
        $circle_promoter = User::find(session('circle.user_id'));
        $place = Place::find(session('circle.place_id'));
        $place_caretaker = User::find($place->user_id);
        $department = Department::find($place->department_id);
        $department_manager = User::find($department->bs_id);
        $department_caretaker = User::find($department->sw_id);
        if (isset($promotion_cirlce->id)) {
            $circle_promoter_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_cirlce->id)->where('approver_classification', RoleIndicatorEnum::CIRCLE_PROMOTER)->first();
            $place_caretaker_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_cirlce->id)->where('approver_classification', RoleIndicatorEnum::PLACE_CARETAKER)->first();
            $department_caretaker_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_cirlce->id)->where('approver_classification', RoleIndicatorEnum::DEPARTMENT_CARETAKER)->first();
            $department_manager_approval = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_cirlce->id)->where('approver_classification', RoleIndicatorEnum::DEPARTMENT_MANAGER)->first();
        }

        $meeting_month = DB::table('activities')->where('activity_category', Activity::MEETING)
            ->where('circle_id', session('circle.id'))
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
            ->where('circle_id', session('circle.id'))
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
            ->where('circle_id', session('circle.id'))
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
            ->where('circle_id', session('circle.id'))
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

        $other_month = DB::table('activities')
            ->where('activity_category', Activity::OTHER)
            ->where('circle_id', session('circle.id'))
            ->whereYear('date_execution', $current_year);
        $other_month_1 = (clone $other_month)->whereMonth('date_execution', 1)->get();
        $other_month_2 = (clone $other_month)->whereMonth('date_execution', 2)->get();
        $other_month_3 = (clone $other_month)->whereMonth('date_execution', 3)->get();
        $other_month_4 = (clone $other_month)->whereMonth('date_execution', 4)->get();
        $other_month_5 = (clone $other_month)->whereMonth('date_execution', 5)->get();
        $other_month_6 = (clone $other_month)->whereMonth('date_execution', 6)->get();
        $other_month_7 = (clone $other_month)->whereMonth('date_execution', 7)->get();
        $other_month_8 = (clone $other_month)->whereMonth('date_execution', 8)->get();
        $other_month_9 = (clone $other_month)->whereMonth('date_execution', 9)->get();
        $other_month_10 = (clone $other_month)->whereMonth('date_execution', 10)->get();
        $other_month_11 = (clone $other_month)->whereMonth('date_execution', 11)->get();
        $other_month_12 = (clone $other_month)->whereMonth('date_execution', 12)->get();
        
        // NTQ MODIFIED 20200130
        // Create ActivityApprovalsStatistics corresponding to each Circle and Year
        $actual_kaizen_month_record = DB::table('activity_approvals_statistics')->where('circle_id', session('circle.id'))->where('year', $current_year)->first();

        if (!$actual_kaizen_month_record) {
            $new_actual_kaizen_month = new ActivityApprovalsStatistics();
            $new_actual_kaizen_month->circle_id = session('circle.id');
            $new_actual_kaizen_month->year = $current_year;
            $new_actual_kaizen_month->save();
        }

        $actual_kaizen_month = DB::table('activity_approvals_statistics')
            ->where('circle_id', session('circle.id'))
            ->where('year', $current_year)->first();

        $actual_kaizen_month_id = $actual_kaizen_month->id;
        $kaizen_editinline_month_1 = $actual_kaizen_month->kaizen_month_1;
        $kaizen_editinline_month_2 = $actual_kaizen_month->kaizen_month_2;
        $kaizen_editinline_month_3 = $actual_kaizen_month->kaizen_month_3;
        $kaizen_editinline_month_4 = $actual_kaizen_month->kaizen_month_4;
        $kaizen_editinline_month_5 = $actual_kaizen_month->kaizen_month_5;
        $kaizen_editinline_month_6 = $actual_kaizen_month->kaizen_month_6;
        $kaizen_editinline_month_7 = $actual_kaizen_month->kaizen_month_7;
        $kaizen_editinline_month_8 = $actual_kaizen_month->kaizen_month_8;
        $kaizen_editinline_month_9 = $actual_kaizen_month->kaizen_month_9;
        $kaizen_editinline_month_10 = $actual_kaizen_month->kaizen_month_10;
        $kaizen_editinline_month_11 = $actual_kaizen_month->kaizen_month_11;
        $kaizen_editinline_month_12 = $actual_kaizen_month->kaizen_month_12;
        $kaizen_editinline_month_total = $kaizen_editinline_month_1 + $kaizen_editinline_month_2 + $kaizen_editinline_month_3
                            + $kaizen_editinline_month_4 + $kaizen_editinline_month_5 + $kaizen_editinline_month_6
                            + $kaizen_editinline_month_7 + $kaizen_editinline_month_8 + $kaizen_editinline_month_9
                            + $kaizen_editinline_month_10 + $kaizen_editinline_month_11 + $kaizen_editinline_month_12;
        
        return view('activity-approval.view',
            [
                'promotion_cirlce' => $promotion_cirlce,
                'review_last_year' => $review_last_year,
                'first_year' => isset($first_year) ? $first_year->year : $current_year,
                'last_year' => isset($last_year) ? $last_year->year : $current_year,
                'theme_list' => $theme_list,
                'plan_by_year' => isset($plan_by_year) ? $plan_by_year : null,
                'circle_promoter' => isset($circle_promoter) ? $circle_promoter : null,
                'place_caretaker' => isset($place_caretaker) ? $place_caretaker : null,
                'department_manager' => isset($place_caretaker) ? $department_manager : null,
                'department_caretaker' => isset($department_caretaker) ? $department_caretaker : null,
                'circle_promoter_approval' => isset($circle_promoter_approval) ? $circle_promoter_approval : new ActivityApproval(),
                'place_caretaker_approval' => isset($place_caretaker_approval) ? $place_caretaker_approval : new ActivityApproval(),
                'department_caretaker_approval' => isset($department_caretaker_approval) ? $department_caretaker_approval : new ActivityApproval(),
                'department_manager_approval' => isset($department_manager_approval) ? $department_manager_approval : new ActivityApproval(),
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
                'current_year' => $current_year,
                'list_member' => $list_member,
                'actual_kaizen_month_id' => $actual_kaizen_month_id,
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
                'array_time' => $array_time,
                'unique_members' => $unique_members,
                'kaizen_editinline_month_1' => $kaizen_editinline_month_1,
                'kaizen_editinline_month_2' => $kaizen_editinline_month_2,
                'kaizen_editinline_month_3' => $kaizen_editinline_month_3,
                'kaizen_editinline_month_4' => $kaizen_editinline_month_4,
                'kaizen_editinline_month_5' => $kaizen_editinline_month_5,
                'kaizen_editinline_month_6' => $kaizen_editinline_month_6,
                'kaizen_editinline_month_7' => $kaizen_editinline_month_7,
                'kaizen_editinline_month_8' => $kaizen_editinline_month_8,
                'kaizen_editinline_month_9' => $kaizen_editinline_month_9,
                'kaizen_editinline_month_10' => $kaizen_editinline_month_10,
                'kaizen_editinline_month_11' => $kaizen_editinline_month_11,
                'kaizen_editinline_month_12' => $kaizen_editinline_month_12,
                'other_month_1' => $other_month_1,
                'other_month_2' => $other_month_2,
                'other_month_3' => $other_month_3,
                'other_month_4' => $other_month_4,
                'other_month_5' => $other_month_5,
                'other_month_6' => $other_month_6,
                'other_month_7' => $other_month_7,
                'other_month_8' => $other_month_8,
                'other_month_9' => $other_month_9,
                'other_month_10' => $other_month_10,
                'other_month_11' => $other_month_11,
                'other_month_12' => $other_month_12,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['promotion_circle_id' => 'required'], ['promotion_circle_id.required' => StaticConfig::$Required]);
        $activity_aproval = new ActivityApproval();
        $activity_aproval->promotion_circle_id = $request->promotion_circle_id;
        $activity_aproval->approver_classification = $request->approver_classification;
        $activity_aproval->user_approved = $request->user_approved;
        $activity_aproval->date_approved = $request->date_approved;
        $activity_aproval->user_jan = $request->user_jan;
        $activity_aproval->date_jan = $request->date_jan;
        $activity_aproval->user_feb = $request->user_feb;
        $activity_aproval->date_feb = $request->date_feb;
        $activity_aproval->user_mar = $request->user_mar;
        $activity_aproval->date_mar = $request->date_mar;
        $activity_aproval->user_apr = $request->user_apr;
        $activity_aproval->date_apr = $request->date_apr;
        $activity_aproval->user_may = $request->user_may;
        $activity_aproval->date_may = $request->date_may;
        $activity_aproval->user_jun = $request->user_jun;
        $activity_aproval->date_jun = $request->date_jun;
        $activity_aproval->user_jul = $request->user_jul;
        $activity_aproval->date_jul = $request->date_jul;
        $activity_aproval->user_aug = $request->user_aug;
        $activity_aproval->date_aug = $request->date_aug;
        $activity_aproval->user_sep = $request->user_sep;
        $activity_aproval->date_sep = $request->date_sep;
        $activity_aproval->user_oct = $request->user_oct;
        $activity_aproval->date_oct = $request->date_oct;
        $activity_aproval->user_nov = $request->user_nov;
        $activity_aproval->date_nov = $request->date_nov;
        $activity_aproval->user_dec = $request->user_dec;
        $activity_aproval->date_dec = $request->date_dec;

        $activity_aproval->created_by = Auth::id();
        $activity_aproval->updated_by = Auth::id();
        $activity_aproval->save();
        return redirect('activity-approval?year=' . $request->year);
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
        $this->validate($request, ['promotion_circle_id' => 'required'], ['promotion_circle_id.required' => StaticConfig::$Required]);
        $activity_approval = ActivityApproval::find($id);
        $activity_approval->user_approved = $request->user_approved;
        $activity_approval->date_approved = $request->date_approved;
        $activity_approval->user_jan = $request->user_jan;
        $activity_approval->date_jan = $request->date_jan;
        $activity_approval->user_feb = $request->user_feb;
        $activity_approval->date_feb = $request->date_feb;
        $activity_approval->user_mar = $request->user_mar;
        $activity_approval->date_mar = $request->date_mar;
        $activity_approval->user_apr = $request->user_apr;
        $activity_approval->date_apr = $request->date_apr;
        $activity_approval->user_may = $request->user_may;
        $activity_approval->date_may = $request->date_may;
        $activity_approval->user_jun = $request->user_jun;
        $activity_approval->date_jun = $request->date_jun;
        $activity_approval->user_jul = $request->user_jul;
        $activity_approval->date_jul = $request->date_jul;
        $activity_approval->user_aug = $request->user_aug;
        $activity_approval->date_aug = $request->date_aug;
        $activity_approval->user_sep = $request->user_sep;
        $activity_approval->date_sep = $request->date_sep;
        $activity_approval->user_oct = $request->user_oct;
        $activity_approval->date_oct = $request->date_oct;
        $activity_approval->user_nov = $request->user_nov;
        $activity_approval->date_nov = $request->date_nov;
        $activity_approval->user_dec = $request->user_dec;
        $activity_approval->date_dec = $request->date_dec;

        $activity_approval->updated_by = Auth::id();
        $activity_approval->save();
        return redirect('activity-approval?year=' . $request->year);
    }

    public function removeStamp(Request $request)
    {
        $unique_members = DB::table('members')->where('circle_id', session('circle.id'))->groupBy('user_id')->pluck('user_id')->toArray();
        if (Auth::user()->access_authority == AccessAuthority::ADMIN) {
            $this->erase_stamp($request->line_id, $request->month_stamp);
            return redirect()->back();
        } else {
            if ($request->user_stamp == Auth::id() || in_array($request->user_stamp, $unique_members)) {
                $this->erase_stamp($request->line_id, $request->month_stamp);
                return redirect()->back();
            } else {
                return redirect()->back()->withErrors(StaticConfig::$Cannot_Remove_Stamp);
            }
        }
    }

    private function erase_stamp($id, $month)
    {
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

    public function saveKaizenEditInLine(Request $request)
    {
        $activityApprovalsStatistics = ActivityApprovalsStatistics::find($request->actual_kaizen_month_id);
        $activityApprovalsStatistics->kaizen_month_1=$request->kaizen_month_1;
        $activityApprovalsStatistics->kaizen_month_2=$request->kaizen_month_2;
        $activityApprovalsStatistics->kaizen_month_3=$request->kaizen_month_3;
        $activityApprovalsStatistics->kaizen_month_4=$request->kaizen_month_4;
        $activityApprovalsStatistics->kaizen_month_5=$request->kaizen_month_5;
        $activityApprovalsStatistics->kaizen_month_6=$request->kaizen_month_6;
        $activityApprovalsStatistics->kaizen_month_7=$request->kaizen_month_7;
        $activityApprovalsStatistics->kaizen_month_8=$request->kaizen_month_8;
        $activityApprovalsStatistics->kaizen_month_9=$request->kaizen_month_9;
        $activityApprovalsStatistics->kaizen_month_10=$request->kaizen_month_10;
        $activityApprovalsStatistics->kaizen_month_11=$request->kaizen_month_11;
        $activityApprovalsStatistics->kaizen_month_12=$request->kaizen_month_12;
        $activityApprovalsStatistics->save();
        exit();
    }

}
