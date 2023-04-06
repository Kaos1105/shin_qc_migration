<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Enums\Activity;
use App\Models\Theme;
use Illuminate\Support\Facades\DB;
use Validator;
use DateTime;

class ThemeOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList()
    {
        $year = date('Y');
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        }
        $circle_filter = "all";
        if (isset($_GET['circle'])) {
            $circle_filter = $_GET['circle'];
        }
        $sort = 'date_start';
        $sortType = 'desc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        $list_year = DB::table('themes')->selectRaw('YEAR(date_start) as year')->distinct('year')->orderBy('year')->get();
        $promos = DB::table('promotion_themes')
            ->select(DB::raw('promotion_themes.theme_id, MAX(promotion_themes.progression_category) AS progress, promotion_themes.date_actual_completion AS date_actual_2'))
            ->whereNotNull('promotion_themes.date_actual_completion')
            ->groupBy('promotion_themes.theme_id');
        $circle = DB::table('circles')->select('id as circleID', 'circle_name');
        $circle_list = DB::table('circles')->select('id as circleID', 'circle_name')->groupBy('circle_name')->get();

        $dd = DB::table('themes')
            ->leftJoinSub($circle, 'circle', function ($join) {
                $join->on('circle_id', '=', 'circle.circleID');
            })
            ->leftJoinSub($promos, 'promos', function ($join) {
                $join->on('themes.id', '=', 'promos.theme_id');
            });

        $act = DB::table('activities')->where('activity_category', 1)->pluck('id');

        $cc = DB::table('activity_others')
            ->whereIn('activity_id', $act)
            ->where('time', '>', 0)
            ->select('activity_others.activity_id', 'time' ,'activity_others.theme_id as theme_id_ref');

        if ($year == "all") {
            if ($circle_filter == "all") {
                $theme = $dd
                    ->leftJoinSub($cc, 'cc', function ($join) {
                        $join->on('themes.id', '=', 'cc.theme_id_ref');
                    })
                    ->select('id', 'circle_name', 'theme_name', 'value_property', 'value_objective', 'date_start', 'date_expected_completion', 'date_actual_completion', 'progress', 'date_actual_2')
                    ->addSelect(DB::raw('COUNT(activity_id) as num_meeting, SUM(time) as hours_meeting'))
                    ->groupBy('themes.id')
                    ->orderBy($sort, $sortType)
                    ->paginate(20);
            } else {
                $theme = $dd
                    ->leftJoinSub($cc, 'cc', function ($join) {
                        $join->on('themes.id', '=', 'cc.theme_id_ref');
                    })
                    ->where('circleID', $circle_filter)
                    ->select('id', 'circle_name', 'theme_name', 'value_property', 'value_objective', 'date_start', 'date_expected_completion', 'date_actual_completion', 'progress', 'date_actual_2')
                    ->addSelect(DB::raw('COUNT(activity_id) as num_meeting, SUM(time) as hours_meeting'))
                    ->groupBy('themes.id')
                    ->orderBy($sort, $sortType)
                    ->paginate(20);
            }
        } else {
            if ($circle_filter == "all") {
                $theme = $dd
                    ->leftJoinSub($cc, 'cc', function ($join) {
                        $join->on('themes.id', '=', 'cc.theme_id_ref');
                    })
                    ->whereYear('themes.date_start', '<=', $year)->whereYear('themes.date_expected_completion', '>=', $year)
                    ->select('id', 'circle_name', 'theme_name', 'value_property', 'value_objective', 'date_start', 'date_expected_completion', 'date_actual_completion', 'progress', 'date_actual_2')
                    ->addSelect(DB::raw('COUNT(activity_id) as num_meeting, SUM(time) as hours_meeting'))
                    ->groupBy('themes.id')
                    ->orderBy($sort, $sortType)
                    ->paginate(20);
            } else {
                $theme = $dd
                    ->leftJoinSub($cc, 'cc', function ($join) {
                        $join->on('themes.id', '=', 'cc.theme_id_ref');
                    })
                    ->where('circleID', $circle_filter)
                    ->whereYear('themes.date_start', '<=', $year)->whereYear('themes.date_expected_completion', '>=', $year)
                    ->select('id', 'circle_name', 'theme_name', 'value_property', 'value_objective', 'date_start', 'date_expected_completion', 'date_actual_completion', 'progress', 'date_actual_2')
                    ->addSelect(DB::raw('COUNT(activity_id) as num_meeting, SUM(time) as hours_meeting'))
                    ->groupBy('themes.id')
                    ->orderBy($sort, $sortType)
                    ->paginate(20);
            }
        }

        return view('theme-office.list',
            [
                'paginate' => $theme,
                'list_year' => $list_year,
                'current_year' => isset($_GET['year']) ? $_GET['year'] : null,
                'circle_list' => $circle_list,
                'current_circle' => isset($_GET['circle']) ? $_GET['circle'] : null,
                'year' => $year
            ]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getShow($theme_id)
    {
        $theme = Theme::find($theme_id);
        $circle = Circle::find($theme->circle_id);
        $promotion_theme_1 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 1)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_2 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 2)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_3 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 3)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_4 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 4)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_5 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 5)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_6 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 6)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_7 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 7)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $date_expected_completion_oldest = DB::table('promotion_themes')->where('theme_id', $theme_id)->orderBy('date_expected_start', 'asc')->select('date_expected_start')->first();
        $date_expected_completion_lastest = DB::table('promotion_themes')->where('theme_id', $theme_id)->orderBy('date_expected_completion', 'DESC')->select('date_expected_completion')->first();
        $theme_start = $theme->date_start;
        $theme_complete = $theme->date_expected_completion;
        if (isset($date_expected_completion_lastest)) {
            $datetime_promo1 = $date_expected_completion_oldest->date_expected_start;
            $datetime_promo2 = $date_expected_completion_lastest->date_expected_completion;
        } else {
            $datetime_promo1 = $theme_start;
            $datetime_promo2 = $theme_complete;
        }

        $datetime_str1 = $theme_start < $datetime_promo1 ? $theme_start : $datetime_promo1;
        $datetime_str2 = $theme_complete > $datetime_promo2 ? $theme_complete : $datetime_promo2;
        $datetime1 = new DateTime($datetime_str1);
        $datetime2 = new DateTime($datetime_str2);
        $year1 = $datetime1->format('Y');
        $year2 = $datetime2->format('Y');
        $month1 = $datetime1->format('m');
        $month2 = $datetime2->format('m');
        $year_compare_1 = (int)$datetime1->format('Y');
        $month_compare_1 = (int)$datetime1->format('m');
        $date_compare_1 = new DateTime($year_compare_1 . '-' . $month_compare_1);
        $year_compare_2 = (int)$datetime2->format('Y');
        $month_compare_2 = (int)$datetime2->format('m');
        $date_compare_2 = new DateTime($year_compare_2 . '-' . $month_compare_2);

        $number_year = (int)$datetime2->format('Y') - $datetime1->format('Y');
        $number_month = ((int)($year2 - $year1) * 12) + (int)($month2 - $month1) + 1;
        $finish_month = (int)$datetime2->format('m');
        $start_month = (int)$datetime1->format('m');
        $start_year = (int)$datetime1->format('Y');
        $end_year = (int)$datetime2->format('Y');

        $activity_other = DB::table('activity_others')->where('theme_id', $theme_id)->select('activity_id', 'time')->get();
        $meeting_times = 0;
        $meeting_total_time = 0.00;
        $study_times = 0;
        $study_total_time = 0.00;
        $kaizen_times = 0;
        $kaizen_total_time = 0.00;
        $other_times = 0;
        $other_total_time = 0.00;
        if ($activity_other) {
            foreach ($activity_other as $item) {
                $activity = \App\Activity::find($item->activity_id);
                if ($activity->activity_category == Activity::MEETING) {
                    $meeting_times += 1;
                    $meeting_total_time += $item->time;
                    continue;
                }
                if ($activity->activity_category == Activity::STUDY_GROUP) {
                    $study_times += 1;
                    $study_total_time += $item->time;
                    continue;
                }
                if ($activity->activity_category == Activity::KAIZEN) {
                    $kaizen_times += 1;
                    $kaizen_total_time += $item->time;
                    continue;
                }
                $other_times += 1;
                $other_total_time += $item->time;
            }
        }

        return view('theme-office.view',
            [
                'theme' => $theme,
                'circle' => $circle,
                'promotion_theme_1' => $promotion_theme_1,
                'promotion_theme_2' => $promotion_theme_2,
                'promotion_theme_3' => $promotion_theme_3,
                'promotion_theme_4' => $promotion_theme_4,
                'promotion_theme_5' => $promotion_theme_5,
                'promotion_theme_6' => $promotion_theme_6,
                'promotion_theme_7' => $promotion_theme_7,
                'meeting_times' => $meeting_times,
                'meeting_total_time' => $meeting_total_time,
                'study_times' => $study_times,
                'study_total_time' => $study_total_time,
                'kaizen_times' => $kaizen_times,
                'kaizen_total_time' => $kaizen_total_time,
                'other_times' => $other_times,
                'other_total_time' => $other_total_time,
                'number_year' => isset($number_year) ? $number_year : null,
                'number_month' => isset($number_month) ? $number_month : null,
                'finish_month' => isset($finish_month) ? $finish_month : null,
                'start_month' => isset($start_month) ? $start_month : null,
                'start_year' => isset($start_year) ? $start_year : null,
                'end_year' => isset($end_year) ? $end_year : null,
                'date_expected_start' => isset($datetime1) ? $datetime1 : null,
                'holdsort' => isset($_GET['holdsort']) ? 'yes' : null,
                'callback' => isset($_GET['callback']) ? 'yes' : null,
                'date_compare_start' => isset($date_compare_1) ? $date_compare_1 : null,
                'date_compare_finish' => isset($date_compare_2) ? $date_compare_2 : null,
            ]);
    }

}
