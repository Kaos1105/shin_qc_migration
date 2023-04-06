<?php

namespace App\Enums;

use DateTime;
use DB;

class Common
{
    public static function show_id($real_id)
    {
        $fixed = '';
        if ($real_id < 10) {
            $fixed = "000";
        } elseif ($real_id < 100) {
            $fixed = "00";
        } elseif ($real_id < 1000) {
            $fixed = "0";
        }
        return $fixed . $real_id;

    }

    public static function total_time($time_start, $time_finish)
    {
        if (empty($time_start) || empty($time_finish)) {
            return 0;
        }
        $datetime_str1 = '2000-01-01 ' . $time_start;
        $datetime_str2 = '2000-01-01 ' . $time_finish;
        try {
            $datetime1 = new DateTime($datetime_str1);
            $datetime2 = new DateTime($datetime_str2);
        } catch (Exception $e) {
            return 0;
        }
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%h') + $interval->format('%i') / 60;
    }

    public static function get_number_day($datetime1, $datetime2)
    {
        $difference = date_diff($datetime1, $datetime2);
        return $difference->format('%a');
    }

    public static function two_number_after_comma($number)
    {
        if (!$number) {
            return '';
        } else {
            return number_format(round($number, 2), 2);
        }
    }

    private const DATE_EXPECTED_START = "date_expected_start";
    public static function show_theme_toppage($theme_id, $year, $month)
    {
        $promotion_theme = DB::table('promotion_themes')->where('theme_id', $theme_id)
            ->whereYear('date_actual_completion', '>=', $year)->whereMonth('date_actual_completion', '>=', $month)
            ->whereYear('date_actual_start', '<=', $year)->whereMonth('date_actual_start', '<=', $month)
            ->select('progression_category', Common::DATE_EXPECTED_START)->get()->toArray();

        $result = '<td></td>';
        if (count($promotion_theme) > 0) {
            $result = '<td class="progress-step">';
            foreach ($promotion_theme as $promotion_theme_item) {
                $result .= 'Step' . $promotion_theme_item->progression_category . '<br/>';
            }
            $result .= '</td>';
        }
        return $result;
    }

    public static function get_promotion_theme_by_themeid($theme_id, $year, $month)
    {
        return DB::table('promotion_themes')->where('theme_id', $theme_id)
            ->whereYear(Common::DATE_EXPECTED_START, $year)->whereMonth(Common::DATE_EXPECTED_START, $month)
            ->select('progression_category', Common::DATE_EXPECTED_START)->get()->toArray();
    }

    public static function generateColor($ext)
    {
        $ext = strtolower($ext);
        $letters = 'c7c7b31ffb2b1fc236c3f6cbc5c3a1f318bf758bfb2c5535b0';
        if (strlen($ext) < 3) {
            $ext = $ext . "z";
        }
        $r = intval(floor(ord($ext[0]) / 100 * 16));
        $g = intval(floor(ord($ext[1]) / 100 * 16));
        $b = intval(floor(ord($ext[2]) / 100 * 16));
        return "#" . $letters[$r] . $letters[$r + 1] . $letters[$g] . $letters[$g + 1] . $letters[$b] . $letters[$b + 1];
    }

    public static function get_circle_display_order_smallest($circle1, $circle2)
    {
        if(isset($circle1) && isset($circle2)){
            return $circle1->display_order >= $circle2->display_order? $circle2 : $circle1;
        }
        return isset($circle1)? $circle1 : $circle2;
    }

    public static function checkYearMonth($year1, $year2)
    {
        $startMonth = 0;
        if ($year1 < $year2) {
            $startMonth = ($year2 - $year1) * 12 + 1;
        }
        return $startMonth;
    }
}