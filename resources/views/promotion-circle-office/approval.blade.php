<div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
    活動テーマ
</div>
<div id="approval-canvas" class="position-relative">
    <table class="table-form" id="table-activity-approval-theme" border="1">
        <thead>
        <tr class="text-center">
            <td class="background-c">No</td>
            <td class="background-c">活動テーマ</td>
            <td class="background-c">特性値</td>
            <td class="background-c">目標値</td>
            <td class="background-c" style="width: 5%">会合回数</td>
            <td class="background-c" style="width: 5%">会合時間</td>
            <td class="background-c" style="width: 4.5%">1月</td>
            <td class="background-c" style="width: 4.5%">2月</td>
            <td class="background-c" style="width: 4.5%">3月</td>
            <td class="background-c" style="width: 4.5%">4月</td>
            <td class="background-c" style="width: 4.5%">5月</td>
            <td class="background-c" style="width: 4.5%">6月</td>
            <td class="background-c" style="width: 4.5%">7月</td>
            <td class="background-c" style="width: 4.5%">8月</td>
            <td class="background-c" style="width: 4.5%">9月</td>
            <td class="background-c" style="width: 4.5%">10月</td>
            <td class="background-c" style="width: 4.5%">11月</td>
            <td class="background-c" style="width: 4.5%; border-right: 1px solid #333333">12月</td>
            <td class="invisible special-arrow-border" style="width: 0; padding: 0 !important;"></td>
        </tr>
        </thead>
        <tbody>
        <?php $them_stt = 1; ?>
        @foreach($theme_list as $theme_list_item)
            <?php
            $activity_other = DB::table('activity_others')->where('theme_id', $theme_list_item->id)->get();
            $meeting_times = 0;
            $meeting_total_time = 0.0;
            if ($activity_other) {
                foreach ($activity_other as $item) {
                    $activity = \App\Activity::find($item->activity_id);
                    if ($activity->activity_category == \App\Enums\Activity::MEETING) {
                        $meeting_times += 1;
                        $meeting_total_time += $item->time;
                    }
                }
            }
            ?>
            <tr class="text-center">
                <td>{{ $them_stt }}</td>
                <td class="text-left">
                    <a class="a-black"
                       href="{{URL::to('theme-office/show/'.$theme_list_item->id.'?callback=yes')}}">{{ $theme_list_item->theme_name }}</a>
                </td>
                <td>{{ $theme_list_item->value_property }}</td>
                <td>{{ $theme_list_item->value_objective }}</td>
                <td>{{ $meeting_times }}</td>
                <td>{{ number_format($meeting_total_time,2) }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-right: 1px solid #333333"></td>
                <td class="invisible special-arrow-border" style="width:0; padding: 0 !important;"></td>
            </tr>
            <?php
            $date_start = new DateTime($theme_list_item->date_start);
            $date_expected_completion = new DateTime($theme_list_item->date_expected_completion);

            ?>
            <script>
                <?php
                $numberColumFirst = 5;
                ?>
                function coorPlan{{$theme_list_item->id}}() {
                    let table = '#table-activity-approval-theme',
                        color = 'blue',
                        positionTop = 2.3,
                        planStartRow = {{$them_stt}},
                        planStartColumn = {{$date_start->format('m') + $numberColumFirst}},
                        planEndRow = {{$them_stt}},
                        planEndColumn = {{$date_expected_completion->format('m') + $numberColumFirst}};
                    let startPeriod = getPeriod({{$date_start->format('d')}});
                    let endPeriod = getPeriod({{$date_expected_completion->format('d')}});
                    let curr_year = {{ $current_year }},
                        start_year = {{$date_start->format('Y')}},
                        end_year = {{$date_expected_completion->format('Y')}};
                    let hasStartpoint = true;
                    let hasEndpoint = true;
                    if (curr_year - start_year > 0) {
                        startPeriod = -1;
                        planStartColumn = {{$numberColumFirst +1}};
                        hasStartpoint = false;
                    }
                    if (curr_year - end_year < 0) {
                        endPeriod = -1;
                        planEndColumn = {{$numberColumFirst +13}};
                        hasEndpoint = false;
                    }
                    let coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndpoint, hasStartpoint];
                    return coors;
                }

                <?php
                if(isset($theme_list_item->date_actual_completion)){
                $date_actual_completion = new DateTime($theme_list_item->date_actual_completion);
                ?>

                function coorReal{{$theme_list_item->id}}() {
                    let table = '#table-activity-approval-theme',
                        color = 'red',
                        positionTop = 1.3,
                        realStartRow = {{$them_stt}},
                        realStartColumn = {{ $date_start->format('m') + $numberColumFirst}},
                        realEndRow = {{$them_stt}},
                        realEndColumn = {{$date_actual_completion->format('m') + $numberColumFirst}};
                    let startPeriod = getPeriod({{$date_start->format('d')}});
                    let endPeriod = getPeriod({{$date_actual_completion->format('d')}});
                    let curr_year = {{ $current_year }},
                        start_year = {{$date_start->format('Y')}},
                        end_year = {{$date_actual_completion->format('Y')}};
                    let hasStartpoint = true;
                    let hasEndpoint = true;
                    if (curr_year - start_year > 0) {
                        startPeriod = -1;
                        realStartColumn = {{$numberColumFirst +1}};
                        hasStartpoint = false;
                    }
                    if (curr_year - end_year < 0) {
                        endPeriod = -1;
                        realEndColumn = {{$numberColumFirst +13}};
                        hasEndpoint = false;
                    }
                    let coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriod, endPeriod, hasEndpoint, hasStartpoint];
                    return coors;
                }
                <?php } ?>

            </script>
            <?php $them_stt++; ?>
        @endforeach
        <script>
            $(function () {
                @foreach($theme_list as $theme_list_item)
                    <?php
                        $date_start_val = new DateTime($theme_list_item->date_start);
                        $date_expected_completion_val = new DateTime($theme_list_item->date_expected_completion);
                        $date_actual_completion_val = new DateTime($theme_list_item->date_actual_completion);
                        if($date_start_val->format('Y') <= $current_year && $current_year <= $date_expected_completion_val->format('Y')){
                    ?>
                    drawArrowOnTableApproval(coorPlan{{$theme_list_item->id}}());
                    resizehandleApproval(coorPlan{{$theme_list_item->id}});
                    <?php } ?>
                    <?php if(isset($theme_list_item->date_actual_completion)){ ?>
                    drawArrowOnTableApproval(coorReal{{$theme_list_item->id}}());
                    resizehandleApproval(coorReal{{$theme_list_item->id}});
                    <?php } ?>
                @endforeach
            });
        </script>
        </tbody>
    </table>
</div>
<div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
    活動計画（目標）と実績
</div>
<?php
$denominator = 0;
$denominator_time = 0;
$denominator_complete = 0;
$denominator_kaizen = 0;
$denominator_study = 0;
?>
<table class="table-form text-center" border="1" id="table4">
    <thead>
    <tr class="text-center">
        <td class="background-c">管理項目</td>
        <td class="background-c">自社目標</td>
        <td class="background-c">サークル目標</td>
        <td class="background-c" style="width: 4.5%">1月</td>
        <td class="background-c" style="width: 4.5%">2月</td>
        <td class="background-c" style="width: 4.5%">3月</td>
        <td class="background-c" style="width: 4.5%">4月</td>
        <td class="background-c" style="width: 4.5%">5月</td>
        <td class="background-c" style="width: 4.5%">6月</td>
        <td class="background-c" style="width: 4.5%">7月</td>
        <td class="background-c" style="width: 4.5%">8月</td>
        <td class="background-c" style="width: 4.5%">9月</td>
        <td class="background-c" style="width: 4.5%">10月</td>
        <td class="background-c" style="width: 4.5%">11月</td>
        <td class="background-c" style="width: 4.5%">12月</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-left">会合回数</td>
        <td>{{ isset($plan_by_year->meeting_times)? $plan_by_year->meeting_times : 0 }} 回/月</td>
        <td>{{ isset($promotion_circle->target_number_of_meeting)? $promotion_circle->target_number_of_meeting : 0 }}
            回/月
        </td>
        <td>
            <?php
            $denominator += $array_meeting[0];
            echo $array_meeting[0] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[1];
            echo $array_meeting[1] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[2];
            echo $array_meeting[2] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[3];
            echo $array_meeting[3] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[4];
            echo $array_meeting[4] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[5];
            echo $array_meeting[5] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[6];
            echo $array_meeting[6] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[7];
            echo $array_meeting[7] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[8];
            echo $array_meeting[8] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[9];
            echo $array_meeting[9] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[10];
            echo $array_meeting[10] . '/' . $denominator;
            ?>
        </td>
        <td>
            <?php
            $denominator += $array_meeting[11];
            echo $array_meeting[11] . '/' . $denominator;
            ?>
        </td>
    </tr>
    <tr>
        <td class="text-left">会合時間</td>
        <td>{{ isset($plan_by_year->meeting_hour)? number_format(round($plan_by_year->meeting_hour, 2), 2) : 0 }}ｈ/月
        </td>
        <td>{{ isset($promotion_circle->target_hour_of_meeting)? number_format(round($promotion_circle->target_hour_of_meeting, 2), 2) : "0.00" }}
            ｈ/月
        </td>
        <td>
            <?php
            $denominator_time += $array_time[0];
            echo number_format(round($array_time[0], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[1];
            echo number_format(round($array_time[1], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[2];
            echo number_format(round($array_time[2], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[3];
            echo number_format(round($array_time[3], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[4];
            echo number_format(round($array_time[4], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[5];
            echo number_format(round($array_time[5], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[6];
            echo number_format(round($array_time[6], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[7];
            echo number_format(round($array_time[7], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[8];
            echo number_format(round($array_time[8], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[9];
            echo number_format(round($array_time[9], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[10];
            echo number_format(round($array_time[10], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
        <td>
            <?php
            $denominator_time += $array_time[11];
            echo number_format(round($array_time[11], 2), 2) . '/ <br>' . number_format(round($denominator_time, 2), 2);
            ?>
        </td>
    </tr>
    <tr>
        <td class="text-left">テーマ完了件数</td>
        <td>{{ isset($plan_by_year->case_number_complete)? $plan_by_year->case_number_complete : 0 }} 件/年</td>
        <td>{{ isset($promotion_circle->target_case_complete)? $promotion_circle->target_case_complete : 0 }} 件/年</td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_1);
            echo count($theme_complete_month_1) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_2);
            echo count($theme_complete_month_2) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_3);
            echo count($theme_complete_month_3) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_4);
            echo count($theme_complete_month_4) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_5);
            echo count($theme_complete_month_5) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_6);
            echo count($theme_complete_month_6) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_7);
            echo count($theme_complete_month_7) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_8);
            echo count($theme_complete_month_8) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_9);
            echo count($theme_complete_month_9) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_10);
            echo count($theme_complete_month_10) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_11);
            echo count($theme_complete_month_11) . '/' . $denominator_complete;
            ?>
        </td>
        <td>
            <?php
            $denominator_complete += count($theme_complete_month_12);
            echo count($theme_complete_month_12) . '/' . $denominator_complete;
            ?>
        </td>
    </tr>
    <tr>
        <td class="text-left">改善（個人）件数</td>
        <td>{{ isset($plan_by_year->case_number_improve)? $plan_by_year->case_number_improve : 0 }} 件/年</td>
        <td>{{ isset($promotion_circle->improved_cases)? $promotion_circle->improved_cases : 0 }} 件/年</td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_1);
            echo count($kaizen_month_1) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_2);
            echo count($kaizen_month_2) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_3);
            echo count($kaizen_month_3) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_4);
            echo count($kaizen_month_4) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_5);
            echo count($kaizen_month_5) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_6);
            echo count($kaizen_month_6) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_7);
            echo count($kaizen_month_7) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_8);
            echo count($kaizen_month_8) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_9);
            echo count($kaizen_month_9) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_10);
            echo count($kaizen_month_10) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_11);
            echo count($kaizen_month_11) . '/' . $denominator_kaizen;
            ?>
        </td>
        <td>
            <?php
            $denominator_kaizen += count($kaizen_month_12);
            echo count($kaizen_month_12) . '/' . $denominator_kaizen;
            ?>
        </td>
    </tr>
    <tr>
        <td class="text-left">ＱＣ手法勉強会</td>
        <td>{{ isset($plan_by_year->classes_organizing_objective)? $plan_by_year->classes_organizing_objective : 0 }}
            回/年
        </td>
        <td>{{ isset($promotion_circle->objectives_of_organizing_classe)? $promotion_circle->objectives_of_organizing_classe : 0 }}
            回/年
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_1);
            echo count($study_month_1) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_2);
            echo count($study_month_2) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_3);
            echo count($study_month_3) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_4);
            echo count($study_month_4) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_5);
            echo count($study_month_5) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_6);
            echo count($study_month_6) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_7);
            echo count($study_month_7) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_8);
            echo count($study_month_8) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_9);
            echo count($study_month_9) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_10);
            echo count($study_month_10) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_11);
            echo count($study_month_11) . '/' . $denominator_study;
            ?>
        </td>
        <td>
            <?php
            $denominator_study += count($study_month_12);
            echo count($study_month_12) . '/' . $denominator_study;
            ?>
        </td>
    </tr>
    </tbody>
</table>

<div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
    計画の承認及び実績の確認印
</div>
<table class="table-form text-center" border="1" id="table5">
    <thead>
    <tr class="text-center">
        <td class="background-c" colspan="2">承認者</td>
        <td class="background-c">計画時</td>
        <td class="background-c" style="width: 4.5%">1月</td>
        <td class="background-c" style="width: 4.5%">2月</td>
        <td class="background-c" style="width: 4.5%">3月</td>
        <td class="background-c" style="width: 4.5%">4月</td>
        <td class="background-c" style="width: 4.5%">5月</td>
        <td class="background-c" style="width: 4.5%">6月</td>
        <td class="background-c" style="width: 4.5%">7月</td>
        <td class="background-c" style="width: 4.5%">8月</td>
        <td class="background-c" style="width: 4.5%">9月</td>
        <td class="background-c" style="width: 4.5%">10月</td>
        <td class="background-c" style="width: 4.5%">11月</td>
        <td class="background-c" style="width: 4.5%">12月</td>
    </tr>
    </thead>
    <tbody>
    <tr class="text-left" data-line-id="{{isset($circle_promoter_approval) ? $circle_promoter_approval->id : ""}}">
        <td>推進者</td>
        <td>{{ isset($circle_promoter)? $circle_promoter->position.' '.$circle_promoter->name : null }}</td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_approved))
                <?php
                $user_approved = \App\Models\User::find($circle_promoter_approval->user_approved);
                $name_approved_explode = explode("　", $user_approved->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_approved}}">&times;</div>
                <span class="d-none month-number">0</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_approved)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_jan))
                <?php
                $user_jan = \App\Models\User::find($circle_promoter_approval->user_jan);
                $name_jan_explode = explode("　", $user_jan->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_jan}}">&times;</div>
                <span class="d-none month-number">1</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_jan)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_feb))
                <?php
                $user_feb = \App\Models\User::find($circle_promoter_approval->user_feb);
                $name_feb_explode = explode("　", $user_feb->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_feb}}">&times;</div>
                <span class="d-none month-number">2</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_feb)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_mar))
                <?php
                $user_mar = \App\Models\User::find($circle_promoter_approval->user_mar);
                $user_mar_explode = explode("　", $user_mar->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_mar}}">&times;</div>
                <span class="d-none month-number">3</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_mar)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_apr))
                <?php
                $user_apr = \App\Models\User::find($circle_promoter_approval->user_apr);
                $user_apr_explode = explode("　", $user_apr->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_apr}}">&times;</div>
                <span class="d-none month-number">4</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_apr)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_may))
                <?php
                $user_may = \App\Models\User::find($circle_promoter_approval->user_may);
                $user_may_explode = explode("　", $user_may->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_may}}">&times;</div>
                <span class="d-none month-number">5</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_may_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_may)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_jun))
                <?php
                $user_jun = \App\Models\User::find($circle_promoter_approval->user_jun);
                $user_jun_explode = explode("　", $user_jun->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_jun}}">&times;</div>
                <span class="d-none month-number">6</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_jun)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_jul))
                <?php
                $user_jul = \App\Models\User::find($circle_promoter_approval->user_jul);
                $user_jul_explode = explode("　", $user_jul->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_jul}}">&times;</div>
                <span class="d-none month-number">7</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_jul)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_aug))
                <?php
                $user_aug = \App\Models\User::find($circle_promoter_approval->user_aug);
                $user_aug_explode = explode("　", $user_aug->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_aug}}">&times;</div>
                <span class="d-none month-number">8</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_aug)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_sep))
                <?php
                $user_sep = \App\Models\User::find($circle_promoter_approval->user_sep);
                $user_sep_explode = explode("　", $user_sep->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_sep}}">&times;</div>
                <span class="d-none month-number">9</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_sep)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_oct))
                <?php
                $user_oct = \App\Models\User::find($circle_promoter_approval->user_oct);
                $user_oct_explode = explode("　", $user_oct->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_oct}}">&times;</div>
                <span class="d-none month-number">10</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_oct)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_nov))
                <?php
                $user_nov = \App\Models\User::find($circle_promoter_approval->user_nov);
                $user_nov_explode = explode("　", $user_nov->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_nov}}">&times;</div>
                <span class="d-none month-number">11</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_nov)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($circle_promoter_approval->user_dec))
                <?php
                $user_dec = \App\Models\User::find($circle_promoter_approval->user_dec);
                $user_dec_explode = explode("　", $user_dec->name);
                ?>
                <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_dec}}">&times;</div>
                <span class="d-none month-number">12</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_dec)) }}</div>
                </div>
            @endif
        </td>
    </tr>
    <tr class="text-left" data-line-id="{{isset($place_caretaker_approval) ? $place_caretaker_approval->id : ""}}">
        <td>職場世話人</td>
        <td>{{ isset($place_caretaker)? $place_caretaker->position.' '.$place_caretaker->name : null }}</td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_approved))
                <?php
                $user_approved = \App\Models\User::find($place_caretaker_approval->user_approved);
                $name_approved_explode = explode("　", $user_approved->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_approved}}">&times;</div>
                <span class="d-none month-number">0</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_approved)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_jan))
                <?php
                $user_jan = \App\Models\User::find($place_caretaker_approval->user_jan);
                $name_jan_explode = explode("　", $user_jan->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_jan}}">&times;</div>
                <span class="d-none month-number">1</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_jan)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_feb))
                <?php
                $user_feb = \App\Models\User::find($place_caretaker_approval->user_feb);
                $name_feb_explode = explode("　", $user_feb->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_feb}}">&times;</div>
                <span class="d-none month-number">2</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_feb)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_mar))
                <?php
                $user_mar = \App\Models\User::find($place_caretaker_approval->user_mar);
                $user_mar_explode = explode("　", $user_mar->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_mar}}">&times;</div>
                <span class="d-none month-number">3</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_mar)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_apr))
                <?php
                $user_apr = \App\Models\User::find($place_caretaker_approval->user_apr);
                $user_apr_explode = explode("　", $user_apr->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_apr}}">&times;</div>
                <span class="d-none month-number">4</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_apr)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_may))
                <?php
                $user_may = \App\Models\User::find($place_caretaker_approval->user_may);
                $user_may_explode = explode("　", $user_may->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_may}}">&times;</div>
                <span class="d-none month-number">5</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_may_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_may)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_jun))
                <?php
                $user_jun = \App\Models\User::find($place_caretaker_approval->user_jun);
                $user_jun_explode = explode("　", $user_jun->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_jun}}">&times;</div>
                <span class="d-none month-number">6</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_jun)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_jul))
                <?php
                $user_jul = \App\Models\User::find($place_caretaker_approval->user_jul);
                $user_jul_explode = explode("　", $user_jul->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_jul}}">&times;</div>
                <span class="d-none month-number">7</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_jul)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_aug))
                <?php
                $user_aug = \App\Models\User::find($place_caretaker_approval->user_aug);
                $user_aug_explode = explode("　", $user_aug->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_aug}}">&times;</div>
                <span class="d-none month-number">8</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_aug)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_sep))
                <?php
                $user_sep = \App\Models\User::find($place_caretaker_approval->user_sep);
                $user_sep_explode = explode("　", $user_sep->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_sep}}">&times;</div>
                <span class="d-none month-number">9</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_sep)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_oct))
                <?php
                $user_oct = \App\Models\User::find($place_caretaker_approval->user_oct);
                $user_oct_explode = explode("　", $user_oct->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_oct}}">&times;</div>
                <span class="d-none month-number">10</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_oct)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_nov))
                <?php
                $user_nov = \App\Models\User::find($place_caretaker_approval->user_nov);
                $user_nov_explode = explode("　", $user_nov->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_nov}}">&times;</div>
                <span class="d-none month-number">11</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_nov)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($place_caretaker_approval->user_dec))
                <?php
                $user_dec = \App\Models\User::find($place_caretaker_approval->user_dec);
                $user_dec_explode = explode("　", $user_dec->name);
                ?>
                <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_dec}}">&times;</div>
                <span class="d-none month-number">12</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_dec)) }}</div>
                </div>
            @endif
        </td>
    </tr>
    <tr class="text-left" data-line-id="{{isset($department_caretaker_approval) ? $department_caretaker_approval->id : ""}}">
        <td>部門世話人</td>
        <td>{{ isset($department_caretaker)? $department_caretaker->position.' '.$department_caretaker->name : null }}</td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_approved))
                <?php
                $user_approved = \App\Models\User::find($department_caretaker_approval->user_approved);
                $name_approved_explode = explode("　", $user_approved->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_approved}}">&times;</div>
                <span class="d-none month-number">0</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_approved)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_jan))
                <?php
                $user_jan = \App\Models\User::find($department_caretaker_approval->user_jan);
                $name_jan_explode = explode("　", $user_jan->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_jan}}">&times;</div>
                <span class="d-none month-number">1</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_jan)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_feb))
                <?php
                $user_feb = \App\Models\User::find($department_caretaker_approval->user_feb);
                $name_feb_explode = explode("　", $user_feb->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_feb}}">&times;</div>
                <span class="d-none month-number">2</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_feb)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_mar))
                <?php
                $user_mar = \App\Models\User::find($department_caretaker_approval->user_mar);
                $user_mar_explode = explode("　", $user_mar->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_mar}}">&times;</div>
                <span class="d-none month-number">3</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_mar)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_apr))
                <?php
                $user_apr = \App\Models\User::find($department_caretaker_approval->user_apr);
                $user_apr_explode = explode("　", $user_apr->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_apr}}">&times;</div>
                <span class="d-none month-number">4</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_apr)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_may))
                <?php
                $user_may = \App\Models\User::find($department_caretaker_approval->user_may);
                $user_may_explode = explode("　", $user_may->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_may}}">&times;</div>
                <span class="d-none month-number">5</span>
                <div class="stamp">
                    <div class="stamp-upper">{{  $user_may_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_may)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_jun))
                <?php
                $user_jun = \App\Models\User::find($department_caretaker_approval->user_jun);
                $user_jun_explode = explode("　", $user_jun->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_jun}}">&times;</div>
                <span class="d-none month-number">6</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_jun)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_jul))
                <?php
                $user_jul = \App\Models\User::find($department_caretaker_approval->user_jul);
                $user_jul_explode = explode("　", $user_jul->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_jul}}">&times;</div>
                <span class="d-none month-number">7</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_jul)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_aug))
                <?php
                $user_aug = \App\Models\User::find($department_caretaker_approval->user_aug);
                $user_aug_explode = explode("　", $user_aug->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_aug}}">&times;</div>
                <span class="d-none month-number">8</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_aug)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_sep))
                <?php
                $user_sep = \App\Models\User::find($department_caretaker_approval->user_sep);
                $user_sep_explode = explode("　", $user_sep->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_sep}}">&times;</div>
                <span class="d-none month-number">9</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_sep)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_oct))
                <?php
                $user_oct = \App\Models\User::find($department_caretaker_approval->user_oct);
                $user_oct_explode = explode("　", $user_oct->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_oct}}">&times;</div>
                <span class="d-none month-number">10</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_oct)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_nov))
                <?php
                $user_nov = \App\Models\User::find($department_caretaker_approval->user_nov);
                $user_nov_explode = explode("　", $user_nov->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_nov}}">&times;</div>
                <span class="d-none month-number">11</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_nov)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_caretaker_approval->user_dec))
                <?php
                $user_dec = \App\Models\User::find($department_caretaker_approval->user_dec);
                $user_dec_explode = explode("　", $user_dec->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_dec}}">&times;</div>
                <span class="d-none month-number">12</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_dec)) }}</div>
                </div>
            @endif
        </td>
    </tr>
    <tr class="text-left" data-line-id="{{isset($department_manager_approval) ? $department_manager_approval->id : ""}}">
        <td>部門責任者</td>
        <td>{{ isset($department_manager)? $department_manager->position.' '.$department_manager->name : null }}</td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_approved))
                <?php
                $user_approved = \App\Models\User::find($department_manager_approval->user_approved);
                $name_approved_explode = explode("　", $user_approved->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_approved}}">&times;</div>
                <span class="d-none month-number">0</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                    <div
                        class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_approved)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_jan))
                <?php
                $user_jan = \App\Models\User::find($department_manager_approval->user_jan);
                $name_jan_explode = explode("　", $user_jan->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_jan}}">&times;</div>
                <span class="d-none month-number">1</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_jan)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_feb))
                <?php
                $user_feb = \App\Models\User::find($department_manager_approval->user_feb);
                $name_feb_explode = explode("　", $user_feb->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_feb}}">&times;</div>
                <span class="d-none month-number">2</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_feb)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_mar))
                <?php
                $user_mar = \App\Models\User::find($department_manager_approval->user_mar);
                $user_mar_explode = explode("　", $user_mar->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_mar}}">&times;</div>
                <span class="d-none month-number">3</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_mar)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_apr))
                <?php
                $user_apr = \App\Models\User::find($department_manager_approval->user_apr);
                $user_apr_explode = explode("　", $user_apr->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_apr}}">&times;</div>
                <span class="d-none month-number">4</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_apr)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_may))
                <?php
                $user_may = \App\Models\User::find($department_manager_approval->user_may);
                $user_may_explode = explode("　", $user_may->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_may}}">&times;</div>
                <span class="d-none month-number">5</span>
                <div class="stamp">
                    <div class="stamp-upper">{{  $user_may_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_may)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_jun))
                <?php
                $user_jun = \App\Models\User::find($department_manager_approval->user_jun);
                $user_jun_explode = explode("　", $user_jun->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_jun}}">&times;</div>
                <span class="d-none month-number">6</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_jun)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_jul))
                <?php
                $user_jul = \App\Models\User::find($department_manager_approval->user_jul);
                $user_jul_explode = explode("　", $user_jul->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_jul}}">&times;</div>
                <span class="d-none month-number">7</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_jul)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_aug))
                <?php
                $user_aug = \App\Models\User::find($department_manager_approval->user_aug);
                $user_aug_explode = explode("　", $user_aug->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_aug}}">&times;</div>
                <span class="d-none month-number">8</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_aug)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_sep))
                <?php
                $user_sep = \App\Models\User::find($department_manager_approval->user_sep);
                $user_sep_explode = explode("　", $user_sep->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_sep}}">&times;</div>
                <span class="d-none month-number">9</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_sep)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_oct))
                <?php
                $user_oct = \App\Models\User::find($department_manager_approval->user_oct);
                $user_oct_explode = explode("　", $user_oct->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_oct}}">&times;</div>
                <span class="d-none month-number">10</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_oct)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_nov))
                <?php
                $user_nov = \App\Models\User::find($department_manager_approval->user_nov);
                $user_nov_explode = explode("　", $user_nov->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_nov}}">&times;</div>
                <span class="d-none month-number">11</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_nov)) }}</div>
                </div>
            @endif
        </td>
        <td class="stamp-box">
            @if(isset($department_manager_approval->user_dec))
                <?php
                $user_dec = \App\Models\User::find($department_manager_approval->user_dec);
                $user_dec_explode = explode("　", $user_dec->name);
                ?>
                <div class="remove-stamp" data-user="{{$department_manager_approval->user_dec}}">&times;</div>
                <span class="d-none month-number">12</span>
                <div class="stamp">
                    <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                    <div class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_dec)) }}</div>
                </div>
            @endif
        </td>
    </tr>
    </tbody>
</table>
<div class="float-right stamp-remove-toggle" style="margin-top: 0.5rem">
    <label class="switch">
        <input type="checkbox" id="toggle-remove-stamp">
        <span class="slider round"></span>
    </label>
    <span>スタンプを削除</span>
</div>

<form class="d-none" id="remove-stamp-info" method="POST"
      action="{{ action('PromotionCircleOfficeController@removeStamp') }}">
    @csrf
    <input type="hidden" id="line-id" name="line_id" value=""/>
    <input type="hidden" id="month-stamp" name="month_stamp" value=""/>
    <input type="hidden" id="user-stamp" name="user_stamp" value=""/>
</form>
<script>
    $(function () {
        if (sessionStorage.getItem('stampMode')) {
            $('#toggle-remove-stamp').trigger('click');
        }
        if ($('.remove-stamp').length === 0) {
            $('.stamp-remove-toggle').hide();
        } else {
            $('.stamp-remove-toggle').show();
        }
        restoreScroll();
    });

    function restoreScroll() {
        if (sessionStorage.getItem('justStamped')) {
            window.scrollTo(0, document.body.scrollHeight);
            sessionStorage.removeItem('justStamped');
        }
    }

    $('#toggle-remove-stamp').change(function () {
        if (this.checked) {
            $('.remove-stamp').show('fade', 100);
            sessionStorage.setItem('stampMode', 'on');
        } else {
            $('.remove-stamp').hide('fade', 100);
            if (sessionStorage.getItem('stampMode')) {
                sessionStorage.removeItem('stampMode');
            }
        }
    });

    function markStamping() {
        sessionStorage.setItem('justStamped', 'yes');
    }

    $('.remove-stamp').click(function () {
        let cf = confirm("{{\App\Enums\StaticConfig::$Remove_Stamp}}");
        if (cf) {
            markStamping();
            $('#line-id').val($(this).parent().parent().attr('data-line-id'));
            $('#month-stamp').val($(this).next('.month-number').text());
            $('#user-stamp').val($(this).attr('data-user'));
            $('#remove-stamp-info').submit();
        }
    });
</script>
