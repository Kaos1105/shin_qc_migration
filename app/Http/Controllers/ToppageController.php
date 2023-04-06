<?php

namespace App\Http\Controllers;

use App\Enums\UseClassificationEnum;
use App\Enums\AccessAuthority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\Activity;

class ToppageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $calendar = DB::table('calendars')->whereRaw('dates >= DATE_ADD(CURDATE(), INTERVAL -7 DAY) AND dates <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')->get();
        if(Auth::user()->access_authority == AccessAuthority::ADMIN) {
            $activity_other = DB::table('activities')
                ->where('activity_category', Activity::MEETING)
                ->where('circle_id', '<>',session('circle.id'))
                ->whereRaw('date_intended >= CURDATE() AND date_intended <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
                ->get();
            if (session()->has('circle')) {
                $activity_own = DB::table('activities')
                    ->where('activity_category', Activity::MEETING)
                    ->where('circle_id', session('circle.id'))
                    ->whereRaw('date_intended >= CURDATE() AND date_intended <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
                    ->get();
            }
        } elseif (session()->has('circle')){
            $activity_own = DB::table('activities')
                ->where('activity_category', Activity::MEETING)
                ->where('circle_id', session('circle.id'))
                ->whereRaw('date_intended >= CURDATE() AND date_intended <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
                ->get();
        }

        $notification = DB::table('notifications')
            ->where('use_classification', UseClassificationEnum::USES)
            ->where('notification_classify', 2) // 2 means TOPPAGE
            ->whereRaw('(notifications.date_start < now() OR notifications.date_start is null) AND (notifications.date_end > now() OR notifications.date_end is null)')
            ->whereRaw('date_start >= DATE_ADD(CURDATE(), INTERVAL -14 DAY) AND date_start <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
            ->orderBy('display_order')
            ->select('message', 'date_start', 'date_end')
            ->get();

        $notification_in_card = DB::table('notifications')
            ->where('use_classification', UseClassificationEnum::USES)
            ->where('notification_classify', 2) // 2 means TOPPAGE
            ->whereRaw('(notifications.date_start < now() OR notifications.date_start is null) AND (notifications.date_end > now() OR notifications.date_end is null)')
            ->orderBy('display_order')
            ->select('id','message', 'date_start', 'date_end')
            ->get();


        $moto = DB::table('plan_by_years')
            ->where('year', date('Y'))
            ->select('target')
            ->first();

        $moto_circle = DB::table('promotion_circles')
            ->where('year', date('Y'))
            ->where('circle_id', session('circle.id'))
            ->select('motto_of_circle')
            ->first();

        $hp = DB::table('homepages')
            ->whereRaw('(homepages.date_start < now() OR homepages.date_start is null) AND (homepages.date_end > now() OR homepages.date_end is null)')
            ->where('use_classification', UseClassificationEnum::USES)->select('title', 'url')->get();

        $category_in_use = DB::table('categories')->where('use_classification', UseClassificationEnum::USES)->pluck('id');
        $thread_noti = DB::table('threads')
            ->whereIn('category_id', $category_in_use)
            ->where('is_display', UseClassificationEnum::USES)
            ->where('use_classification', UseClassificationEnum::USES)
            ->pluck('id');
        $noti_mark = DB::table('topics')->whereIn('thread_id', $thread_noti)->whereRaw('created_at > DATE_ADD(NOW(), INTERVAL -24 HOUR)')->first();

        if(session('toppage') == AccessAuthority::USER){
            $year = date('Y');
            $promotion_circle = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', $year)
                ->select('target_number_of_meeting', 'target_hour_of_meeting', 'target_case_complete', 'improved_cases', 'objectives_of_organizing_classe')->first();
            $promotion_circle_last_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', ($year - 1))
                ->select('target_number_of_meeting', 'target_hour_of_meeting', 'target_case_complete', 'improved_cases', 'objectives_of_organizing_classe')->first();
            $promotion_circle_next_year = DB::table('promotion_circles')->where('circle_id', session('circle.id'))->where('year', ($year + 1))
                ->select('target_number_of_meeting', 'target_hour_of_meeting', 'target_case_complete', 'improved_cases', 'objectives_of_organizing_classe')->first();
            $meeting_list = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)->whereYear('date_execution', $year)->get();
            $total_time = 0.0;
            if(count($meeting_list) > 0){
                foreach($meeting_list as $meeting_list_item){
                    $total_time += \App\Enums\Common::total_time($meeting_list_item->time_start, $meeting_list_item->time_finish);
                }
            }
            $theme_active_list = DB::table('themes')->where('circle_id', session('circle.id'))
                ->whereRaw('YEAR(date_start) <= YEAR(CURDATE()) AND YEAR(date_expected_completion) >= YEAR(CURDATE())')
                ->whereRaw('LAST_DAY(date_start) <= LAST_DAY(DATE_ADD(CURDATE(),INTERVAL 2 MONTH)) '
                        . 'AND  LAST_DAY(date_expected_completion) >= LAST_DAY(DATE_ADD(CURDATE(),INTERVAL -2 MONTH))')
                ->select('id', 'theme_name', 'date_start', 'date_expected_completion')
                ->orderBy('date_actual_completion', 'asc')
                ->get();

            $allowCircle = true;

            return view('top-page.top-page-circle', [
                'calendar' => isset($calendar)? $calendar : null,
                'notification' => isset($notification)? $notification : null,
                'notification_in_card' => isset($notification_in_card)? $notification_in_card : null,
                'activity_own' => isset($activity_own)? $activity_own : null,
                'activity_other' => isset($activity_other)? $activity_other : null,
                'promotion_circle' => isset($promotion_circle)? $promotion_circle : null,
                'promotion_circle_last_year' => isset($promotion_circle_last_year)? $promotion_circle_last_year : null,
                'promotion_circle_next_year' => isset($promotion_circle_next_year)? $promotion_circle_next_year : null,
                'meeting_list' => $meeting_list,
                'theme_active_list' => $theme_active_list,
                'allowCircle' => $allowCircle,
                'moto' => $moto ? $moto : null,
                'hp' => $hp,
                'noti_mark' => isset($noti_mark) ? 1 : 0,
                'moto_circle' => $moto_circle
            ]);
        }

        $activity_last_month = DB::table('activities')->whereYear('date_execution', date('Y'))->whereMonth('date_execution', (date('m') -1))->count();
        $activity_current_month = DB::table('activities')->whereYear('date_execution', date('Y'))->whereMonth('date_execution', date('m'))->count();
        $activity_next_month = DB::table('activities')->whereYear('date_execution', date('Y'))->whereMonth('date_execution', (date('m') + 1))->count();
        $date_25 = date('Y-m-d', strtotime("-25 days"));
        $date_45 = date('Y-m-d', strtotime("-45 days"));
        $activity_lastest = DB::table('activities')
            ->join('circles', 'circles.id', '=', 'activities.circle_id')
            ->select('circles.circle_name', 'activities.circle_id', DB::raw('MAX(activities.date_execution) as max_date'))
            ->whereNotNull('activities.date_execution')
            ->groupBy('activities.circle_id')
            ->get();
        $arr_date_25 = array();
        $arr_date_45 = array();
        if(isset($activity_lastest)){
            foreach($activity_lastest as $activity_lastest_item){
                if($activity_lastest_item->max_date < $date_45){
                    array_push($arr_date_45, $activity_lastest_item);
                }
                elseif($activity_lastest_item->max_date < $date_25){
                    array_push($arr_date_25, $activity_lastest_item);
                }
            }
        }
        $list_circle_id_promotion = DB::table('promotion_circles')->distinct()->pluck('circle_id');
        $list_circle_id_theme = DB::table('themes')->distinct()->pluck('circle_id');
        $cirlce_not_exists_promotion = DB::table('circles')
            ->whereNotIn('id', $list_circle_id_promotion)
            ->select('id', 'circle_name')
            ->get();
        $cirlce_not_exists_theme = DB::table('circles')
            ->whereNotIn('id', $list_circle_id_theme)
            ->select('id', 'circle_name')
            ->get();

        return view('top-page.top-page-m', [
            'calendar' => isset($calendar)? $calendar : null,
            'notification' => isset($notification)? $notification : null,
            'activity_own' => isset($activity_own)? $activity_own : null,
            'activity_other' => isset($activity_other)? $activity_other : null,
            'arr_date_25' => $arr_date_25,
            'arr_date_45' => $arr_date_45,
            'activity_last_month' => $activity_last_month,
            'activity_current_month' => $activity_current_month,
            'activity_next_month' => $activity_next_month,
            'cirlce_not_exists_promotion' => $cirlce_not_exists_promotion,
            'cirlce_not_exists_theme' => $cirlce_not_exists_theme,
            'hp' => $hp,
            'noti_mark' => isset($noti_mark) ? 1 : 0
        ]);
    }
}
