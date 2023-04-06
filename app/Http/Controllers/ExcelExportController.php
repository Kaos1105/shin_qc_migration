<?php

namespace App\Http\Controllers;

use App\Enums\StaticConfig;
use App\Enums\UseClassificationEnum;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use App\Theme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use stdClass;

class ExcelExportController extends Controller
{
    public function dataInput(Request $request)
    {

    }

    public function excelExport()
    {
        $theme_id = $_GET['theme'];
        $circle1 = $_GET['circle1'];
        $circle2 = $_GET['circle2'];
        $circle3 = $_GET['circle3'];
        $thread = $_GET['thread'];

        $spreadsheet = IOFactory::load(base_path() . '/resources/template/template.xlsx');

        $spreadsheet->setActiveSheetIndex(0);
        $worksheet = $spreadsheet->getActiveSheet();

        $circle = $this->fetchCircle($circle1, $circle2, $circle3);
        $theme = $this->fetchTheme($theme_id);
        $symbol = "→";

        $this->fillHeader($worksheet, $theme->header);
        $this->fillFrame($worksheet, $theme->header);
        $plans = $theme->plan;
        $actuary = $theme->actual;
        for ($h = 1; $h <= 7; $h++) {
            $this->fillPlan($worksheet, $theme->header, $plans, $h);
        }
        for ($h = 1; $h <= 7; $h++) {
            $this->fillActual($worksheet, $theme->header, $actuary, $h, $symbol);
        }
        $this->cleanUp($worksheet, $theme->header, $symbol);
        $this->fillTheme($worksheet, $theme->theme, $theme->start_date);
        $this->fillCircle($worksheet, $circle);

        if ($thread != 0) {
            $topic = $this->fetchTopic($thread);
            $worksheet_text = $spreadsheet->getSheet(1);
            $worksheet_pic = $spreadsheet->getSheet(2);
            $this->fillTopic($worksheet_text, $worksheet_pic, $topic);
        }

        $spreadsheet->setActiveSheetIndex(0);
        if (!Storage::exists(StaticConfig::$Excel_Result)) {
            Storage::makeDirectory(StaticConfig::$Excel_Result, 0775, true); //creates directory
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = now()->timestamp;
        $url_string = base_path() . StaticConfig::$Excel_Result . $name . 'abc.xlsx';

        $writer->save($url_string);

        $headers = array(
            'Content-Type: application/xlsx'
        );
        return response()
            ->download($url_string, '活動報告書テンプレート.xlsx', $headers)
            ->deleteFileAfterSend();
    }

    private function fetchCircle($circle1, $circle2, $circle3)
    {
        $user = DB::table('users')->select('id as userID', 'name');
        $member = DB::table('members')->selectRaw('circle_id, COUNT(id) as numMem, IF(is_leader = 2, name, "") as leader')
            ->leftJoinSub($user, 'user', function ($join) {
                $join->on('members.user_id', '=', 'user.userID');
            })->groupBy('circle_id');
        $place = DB::table('places')->select('id as placeID', 'place_name', 'department_id');
        $dep = DB::table('departments')->select('id as depID', 'department_name');
        $circle_core = DB::table('circles')
            ->leftJoinSub($member, 'member', function ($join) {
                $join->on('circles.id', '=', 'member.circle_id');
            })->leftJoinSub($place, 'place', function ($join) {
                $join->on('circles.place_id', '=', 'place.placeID');
            })->leftJoinSub($dep, 'dep', function ($join) {
                $join->on('department_id', '=', 'depID');
            })->where('use_classification', UseClassificationEnum::USES)
            ->select('id', 'circle_name', 'circle_code', 'department_name', 'place_name', 'numMem', 'leader');

        $circle1 = (clone $circle_core)->where('id', $circle1)->first();
        $circle2 = (clone $circle_core)->where('id', $circle2)->first();
        $circle3 = (clone $circle_core)->where('id', $circle3)->first();

        $circle = new stdClass();
        $circle->circle1 = $circle1;
        $circle->circle2 = isset($circle2) ? $circle2 : null;
        $circle->circle3 = isset($circle3) ? $circle3 : null;

        return $circle;
    }

    private function fetchTheme($theme_id)
    {
        $theme = Theme::find($theme_id);
        $promo_theme = DB::table('promotion_themes')->where('theme_id', $theme_id);

        $promo_1 = (clone $promo_theme)->where('progression_category', 1)->first();
        $promo_2 = (clone $promo_theme)->where('progression_category', 2)->first();
        $promo_3 = (clone $promo_theme)->where('progression_category', 3)->first();
        $promo_4 = (clone $promo_theme)->where('progression_category', 4)->first();
        $promo_5 = (clone $promo_theme)->where('progression_category', 5)->first();
        $promo_6 = (clone $promo_theme)->where('progression_category', 6)->first();
        $promo_7 = (clone $promo_theme)->where('progression_category', 7)->first();

        $date_expected_start = (clone $promo_theme)->orderBy('date_expected_start', 'ASC')->select('date_expected_start')->first();
        $date_expected_completion = (clone $promo_theme)->orderBy('date_expected_completion', 'DESC')->select('date_expected_completion')->first();

        $date_actual_start = (clone $promo_theme)->orderBy('date_actual_start', 'ASC')->select('date_actual_start')->first();
        $date_actual_completion = (clone $promo_theme)->orderBy('date_actual_completion', 'DESC')->select('date_actual_completion')->first();

        $theme_start = $theme->date_start;
        $theme_complete_expected = $theme->date_expected_completion;
        $theme_complete_actual = $theme->date_actual_completion;
        $theme_complete = $theme_complete_expected > $theme_complete_actual ? $theme_complete_expected : $theme_complete_actual;

        $expected_start =  isset($date_expected_start->date_expected_start) ? $date_expected_start->date_expected_start : $theme_start;
        $expected_completion = isset($date_expected_completion->date_expected_completion) ? $date_expected_completion->date_expected_completion : $theme_complete;

        $actual_start = isset($date_actual_start->date_actual_start) ? $date_actual_start->date_actual_start : $theme_start;
        $actual_completion = isset($date_actual_completion->date_actual_completion) ? $date_actual_completion->date_actual_completion : $theme_complete;

        $datetime_str1 = min($theme_start, $expected_start, $actual_start);
        $datetime_str2 = max($theme_complete, $expected_completion, $actual_completion);

        $header_columns = $this->dtArray($datetime_str1, $datetime_str2);

        $frame0 = $this->refineArray($header_columns);
        $frame = array();
        foreach ($frame0 as $item) {
            $frame[$item] = 0;
        }

        $plan1 = $this->dtArray(isset($promo_1) ? $promo_1->date_expected_start : null, isset($promo_1) ? $promo_1->date_expected_completion : null);
        $plan1_frame = $this->processArray(array_replace($frame, $plan1));
        $plan2 = $this->dtArray(isset($promo_2) ? $promo_2->date_expected_start : null, isset($promo_2) ? $promo_2->date_expected_completion : null);
        $plan2_frame = $this->processArray(array_replace($frame, $plan2));
        $plan3 = $this->dtArray(isset($promo_3) ? $promo_3->date_expected_start : null, isset($promo_3) ? $promo_3->date_expected_completion : null);
        $plan3_frame = $this->processArray(array_replace($frame, $plan3));
        $plan4 = $this->dtArray(isset($promo_4) ? $promo_4->date_expected_start : null, isset($promo_4) ? $promo_4->date_expected_completion : null);
        $plan4_frame = $this->processArray(array_replace($frame, $plan4));
        $plan5 = $this->dtArray(isset($promo_5) ? $promo_5->date_expected_start : null, isset($promo_5) ? $promo_5->date_expected_completion : null);
        $plan5_frame = $this->processArray(array_replace($frame, $plan5));
        $plan6 = $this->dtArray(isset($promo_6) ? $promo_6->date_expected_start : null, isset($promo_6) ? $promo_6->date_expected_completion : null);
        $plan6_frame = $this->processArray(array_replace($frame, $plan6));
        $plan7 = $this->dtArray(isset($promo_7) ? $promo_7->date_expected_start : null, isset($promo_7) ? $promo_7->date_expected_completion : null);
        $plan7_frame = $this->processArray(array_replace($frame, $plan7));

        $plan_frame = array($plan1_frame, $plan2_frame, $plan3_frame, $plan4_frame, $plan5_frame, $plan6_frame, $plan7_frame);

        // actual data
        $actual1 = $this->dtArray(isset($promo_1) ? $promo_1->date_actual_start : null, isset($promo_1) ? $promo_1->date_actual_completion : null);
        $actual1_frame = $this->processArray(array_replace($frame, $actual1));
        $actual2 = $this->dtArray(isset($promo_2) ? $promo_2->date_actual_start : null, isset($promo_2) ? $promo_2->date_actual_completion : null);
        $actual2_frame = $this->processArray(array_replace($frame, $actual2));
        $actual3 = $this->dtArray(isset($promo_3) ? $promo_3->date_actual_start : null, isset($promo_3) ? $promo_3->date_actual_completion : null);
        $actual3_frame = $this->processArray(array_replace($frame, $actual3));
        $actual4 = $this->dtArray(isset($promo_4) ? $promo_4->date_actual_start : null, isset($promo_4) ? $promo_4->date_actual_completion : null);
        $actual4_frame = $this->processArray(array_replace($frame, $actual4));
        $actual5 = $this->dtArray(isset($promo_5) ? $promo_5->date_actual_start : null, isset($promo_5) ? $promo_5->date_actual_completion : null);
        $actual5_frame = $this->processArray(array_replace($frame, $actual5));
        $actual6 = $this->dtArray(isset($promo_6) ? $promo_6->date_actual_start : null, isset($promo_6) ? $promo_6->date_actual_completion : null);
        $actual6_frame = $this->processArray(array_replace($frame, $actual6));
        $actual7 = $this->dtArray(isset($promo_7) ? $promo_7->date_actual_start : null, isset($promo_7) ? $promo_7->date_actual_completion : null);
        $actual7_frame = $this->processArray(array_replace($frame, $actual7));

        $actual_frame = array($actual1_frame, $actual2_frame, $actual3_frame, $actual4_frame, $actual5_frame, $actual6_frame, $actual7_frame);

        $start_date = isset($promo_1->date_actual_start) ? $promo_1->date_actual_start : null;
        $theme_result = new stdClass();
        $theme_result->theme = $theme;
        $theme_result->start_date = $start_date;
        $theme_result->header = $this->processArray($frame0);
        $theme_result->plan = $plan_frame;
        $theme_result->actual = $actual_frame;

        return $theme_result;
    }

    private function dtArray($datetime_str1, $datetime_str2)
    {
        if (!$datetime_str1 || !$datetime_str2) {
            return $cols = array();
        } else {
            $datetime1 = new DateTime($datetime_str1);
            $datetime2 = new DateTime($datetime_str2);

            $datetime_1_str = $datetime1->format('Ym');
            $datetime_2_str = $datetime2->format('Ym');

            $dt = $datetime_1_str;
            $dat = $datetime1;
            $cols = array($datetime_1_str => $datetime_1_str);
            while ($dt < $datetime_2_str) {
                $u = $dat->format('m');
                $dat = $dat->add(new DateInterval('P1M'));
                $v = $dat->format('m');
                if ($v - $u > 1) {
                    $dat = $dat->sub(new DateInterval('P10D'));
                }
                $dt = $dat->format('Ym');
                array_push($cols, $cols[$dt] = $dt);
            }
            return $cols;
        }
    }

    private function fetchTopic($thread)
    {
        $category = DB::table('categories')
            ->select('id as catID', 'category_name');
        $threads = DB::table('threads')
            ->joinSub($category, 'category', function ($join) {
                $join->on('category_id', '=', 'category.catID');
            })->where('id', $thread)
            ->select('category_name', 'thread_name', 'created_by', 'created_at')
            ->first();

        $user_by = DB::table('users')->where('id', $threads->created_by)->value('name');

        $topic_header = array($threads->category_name, $threads->thread_name, $threads->created_at, $user_by);

        $topic = DB::table('topics')
            ->where('thread_id', $thread)
            ->orderBy('created_at', 'desc')
            ->select('topic', 'file')
            ->get();
        $topic_data = array();
        $types = ['jpeg', 'gif', 'png', 'bmp', 'svg', 'jpg', 'tiff', 'tif'];
        foreach ($topic as $item) {
            if (isset($item->file)) {
                $extension = strtolower(pathinfo($item->file, PATHINFO_EXTENSION));
                if (in_array($extension, $types)) {
                    $path = base_path() . '/storage/app/public/uploaded-files/topic-upload/' . $item->file;
                    array_push($topic_data, $path);
                }
            } else {
                array_push($topic_data, $item->topic);
            }
        }

        $dt_topic = new stdClass();
        $dt_topic->topic = $topic_data;
        $dt_topic->topic_header = $topic_header;

        return $dt_topic;
    }

    private function fillHeader($worksheet, $header)
    {
        $first = '(' . substr($header[0], 0, 4) . ')' . substr($header[0], 4, 2) . '月';
        $month = array();
        array_push($month, $first);
        $num = sizeof($header);
        for ($i = 1; $i <= $num - 1; $i++) {
            $value = substr($header[$i], 4, 2);
            $digit = array("10", "11", "12");
            if ($value != "01") {
                $value = in_array($value, $digit) ? $value : substr($value, 1, 1);
                array_push($month, $value . '月');
            } else {
                array_push($month, '(' . substr($header[$i], 0, 4) . ')' . '1月');
            }
        }
        $excel_cols = ($num - 1) * 3 + 12;
        for ($j = 12, $k = 0; $j <= $excel_cols, $k <= $num - 1; $j += 3, $k++) {
            $coor = $worksheet->getCellByColumnAndRow($j, 41)->getCoordinate();
            $worksheet->setCellValue($coor, $month[$k]);
        }
    }

    private function fillFrame($worksheet, $header)
    {
        $num = sizeof($header);
        $excel_cols = ($num - 1) * 3 + 12;
        for ($i = 42; $i <= 48; $i++) {
            for ($j = 12, $k = 0; $j <= $excel_cols, $k <= $num - 1; $j += 3, $k++) {
                $coor = $worksheet->getCellByColumnAndRow($j, $i)->getCoordinate();
                $worksheet->setCellValue($coor, $header[$k]);
            }
        }
    }

    private function cleanUp($worksheet, $header, $symbol)
    {
        $num = sizeof($header);
        $excel_cols = ($num - 1) * 3 + 12;
        for ($i = 42; $i <= 48; $i++) {
            for ($j = 12, $k = 0; $j <= $excel_cols, $k <= $num - 1; $j += 3, $k++) {
                $coor = $worksheet->getCellByColumnAndRow($j, $i)->getCoordinate();
                $value = $worksheet->getCellByColumnAndRow($j, $i)->getValue();
                if ($value != $symbol) {
                    $worksheet->setCellValue($coor, "");
                }
            }
        }
    }

    private function fillPlan($worksheet, $header, $plan, $row)
    {
        $num = sizeof($header);
        $excel_cols = ($num - 1) * 3 + 12;
        $k = $row + 41;
        for ($j = 12; $j <= $excel_cols; $j += 3) {
            $coor = $worksheet->getCellByColumnAndRow($j, $k)->getCoordinate();
            $value = $worksheet->getCellByColumnAndRow($j, $k)->getValue();
            foreach ($plan[$row - 1] as $item) {
                if ($value == $item) {
                    $worksheet->getStyle($coor)
                        ->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFCCCCCC');
                }
            }
        }
    }

    private function fillActual($worksheet, $header, $actual, $row, $symbol)
    {
        $num = sizeof($header);
        $excel_cols = ($num - 1) * 3 + 12;
        $k = $row + 41;
        for ($j = 12; $j <= $excel_cols; $j += 3) {
            $coor = $worksheet->getCellByColumnAndRow($j, $k)->getCoordinate();
            $value = $worksheet->getCellByColumnAndRow($j, $k)->getValue();
            foreach ($actual[$row - 1] as $item) {
                if ($value == $item) {
                    $worksheet->setCellValue($coor, $symbol);
                }
            }
        }
    }

    private function fillTheme($worksheet, $theme, $starting_date)
    {
        $worksheet->setCellValue('L11', $theme->theme_name);
        $worksheet->setCellValue('Y23', $theme->value_property);
        $worksheet->setCellValue('U37', $theme->value_objective);
        if ($starting_date) {
            $start_date = new DateTime($starting_date);
            $d1 = $start_date->format('Y') . '年' . $start_date->format('m') . '月' . $start_date->format('d') . '日';
            $worksheet->setCellValue('Q21', $d1);
        }
        if ($theme->date_actual_completion) {
            $end_date = new DateTime($theme->date_actual_completion);
            $d2 = $end_date->format('Y') . '年' . $end_date->format('m') . '月' . $end_date->format('d') . '日';
            $worksheet->setCellValue('AK21', $d2);
        }
    }

    private function fillCircle($worksheet, $circle)
    {
        $coor1 = array('B15', 'N15', 'AA15', 'AR15', 'BD15', 'BI15');
        $coor2 = array('B17', 'N17', 'AA17', 'AR17', 'BD17', 'BI17');
        $coor3 = array('B19', 'N19', 'AA19', 'AR19', 'BD19', 'BI19');

        $circle1 = $circle->circle1;
        $worksheet->setCellValue($coor1[0], $circle1->department_name);
        $worksheet->setCellValue($coor1[1], $circle1->place_name);
        $worksheet->setCellValue($coor1[2], $circle1->circle_name);
        $worksheet->setCellValue($coor1[3], $circle1->leader);
        $worksheet->setCellValue($coor1[4], $circle1->numMem);
        $worksheet->setCellValue($coor1[5], $circle1->circle_code);

        if ($circle->circle2) {
            $circle2 = $circle->circle2;
            $worksheet->setCellValue($coor2[0], $circle2->department_name);
            $worksheet->setCellValue($coor2[1], $circle2->place_name);
            $worksheet->setCellValue($coor2[2], $circle2->circle_name);
            $worksheet->setCellValue($coor2[3], $circle2->leader);
            $worksheet->setCellValue($coor2[4], $circle2->numMem);
            $worksheet->setCellValue($coor2[5], $circle2->circle_code);
        }
        if ($circle->circle3) {
            $circle3 = $circle->circle3;
            $worksheet->setCellValue($coor3[0], $circle3->department_name);
            $worksheet->setCellValue($coor3[1], $circle3->place_name);
            $worksheet->setCellValue($coor3[2], $circle3->circle_name);
            $worksheet->setCellValue($coor3[3], $circle3->leader);
            $worksheet->setCellValue($coor3[4], $circle3->numMem);
            $worksheet->setCellValue($coor3[5], $circle3->circle_code);
        }
    }

    private function fillTopic($worksheet_text, $worksheet_pic, $topic)
    {
        $topic_header = $topic->topic_header;
        $worksheet_text->getCell('A1')->setValue($topic_header[0] . "\n" . $topic_header[1] . "\n" . $topic_header[2] . " " . $topic_header[3]);
        $topics = $topic->topic;
        $length = sizeof($topics);
        $k = 2;
        $t = 1;
        for ($i = 1; $i <= $length; $i++) {
            if (file_exists($topics[$i - 1])) {
                $drawing = new Drawing();
                $drawing->setPath($topics[$i - 1]);
                $drawing->setHeight(180);
                $drawing->setCoordinates("A".$t);
                $drawing->setOffsetY(10);
                $drawing->setWorksheet($worksheet_pic);
                $t += 7;
            } else {
                $worksheet_text->setCellValue("A".$k, $topics[$i - 1]);
                $worksheet_text->getStyle("A".$k)->getAlignment()->setWrapText(true);
                $k += 1;
            }
        }
    }

    private function processArray($ar)
    {
        $temp = array();
        foreach ($ar as $value) {
            array_push($temp, $value);
        }
        return $temp;
    }

    private function refineArray($arr)
    {
        $refined = array();
        foreach ($arr as $index) {
            $refined[$index] = $index;
        }
        return $refined;
    }

}
