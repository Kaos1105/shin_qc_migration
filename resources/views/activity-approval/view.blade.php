@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
                })
    }}

    {{
        Breadcrumbs::for('view', function ($trail) {
                $trail->parent('toppage');
                $trail->push('推進管理');
            })
    }}

    {{ Breadcrumbs::render('view') }}
@endsection
@section('content')
    <div class="btn-form-controlling" style="width: 100%;">
        <div class="btn-area">
            <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
        </div>
        <div class="btn-arrow">
            <button type="button" class="btn btn-back btn-short" onclick="firstYear();">|<</button>
            <button type="button" class="btn btn-back btn-short" onclick="prevYear();"><</button>
            <span class="datePaginate">
                <span id="currentYear">{{$promotion_cirlce->year}}</span> 年
            </span>
            <button type="button" class="btn btn-back btn-short" onclick="nextYear();">></button>
            <button type="button" class="btn btn-back btn-short" onclick="lastYear();">>|</button>
        </div>
    </div>
    <div class="container-fluid bottom-fix">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ \App\Enums\Common::show_id($promotion_cirlce->id) }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">{{ __('サークル名') }}</td>
                <td>
                    @if(session()->exists('circle'))
                        <span>{{ session('circle.circle_name') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">{{ __('登録年度') }}</td>
                <td>
                    <span>{{ $promotion_cirlce->year }} 年</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">前年度の反省</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$review_last_year!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">職場方針</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$promotion_cirlce->motto_of_the_workplace!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル方針</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$promotion_cirlce->motto_of_circle!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">レベル</td>
                <td>
                    @if($promotion_cirlce->id) <span>Ｘ軸 {{number_format($promotion_cirlce->axis_x, 1)}} 点　／　Ｙ軸 {{number_format($promotion_cirlce->axis_y, 1)}} 点</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">今年度の反省</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$promotion_cirlce->review_this_year !!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">推進者コメント</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$promotion_cirlce->comment_promoter !!}</span></span>
                </td>
            </tr>
        </table>
        <div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
            メンバー
        </div>
        <table class="table-member-list" border="1">
            <thead>
            <tr>
                <td style="width: 5%">No</td>
                <td style="width: 20%">メンバ名</td>
                <td style="width: 20%">所属グループ</td>
                <td style="width: 20%">役割</td>
                <td>備考</td>
            </tr>
            </thead>
            <tbody>
            <?php $sttMembers = 1;?>
            @foreach($list_member as $item)
                <?php
                $user = App\User::find($item->user_id);
                ?>
                <tr>
                    <td style="text-align: center">
                        @if($item->is_leader == 2) <span class="d-none">{{$sttMembers++}}</span><span>L</span>
                        @else <span>{{ $sttMembers++ }}</span>
                        @endif
                    </td>
                    <td>
                        <span>@if(isset($user->position)) ({{ $user->position }}) @endif {{ $user->name }}</span>
                    </td>
                    <td>{{ $item->department }}</td>
                    <td>{{ $item->classification }}</td>
                    <td>
                        <span class="line-brake-preserve">{{$item->note}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
            <?php $them_stt = 1;?>
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
                        <a class="a-black" style="word-break: break-word"
                           href="{{ URL::to('theme/'.$theme_list_item->id.'?callback=yes') }}">{{ $theme_list_item->theme_name }}
                        </a>
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
                    <td class="invisible special-arrow-border" style="width: 0; padding: 0 !important;"></td>
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
                            positionTop = 2.7,
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
                    if (isset($theme_list_item->date_actual_completion)) {
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
                    <?php }?>

                </script>
                <?php $them_stt++;?>
            @endforeach
            <script>
                $(function () {
                    @foreach($theme_list as $theme_list_item)
                    drawArrowOnTableApproval(coorPlan{{$theme_list_item->id}}());
                    resizehandleApproval(coorPlan{{$theme_list_item->id}});
                    <?php if (isset($theme_list_item->date_actual_completion)) {?>
                    drawArrowOnTableApproval(coorReal{{$theme_list_item->id}}());
                    resizehandleApproval(coorReal{{$theme_list_item->id}});
                    <?php }?>
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
        $kaizen_editinline_month_total = 0;
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
                <td>会合回数</td>
                <td>{{ isset($plan_by_year->meeting_times)? $plan_by_year->meeting_times : 0 }} 回/月</td>
                <td>{{ isset($promotion_cirlce->target_number_of_meeting)? $promotion_cirlce->target_number_of_meeting : 0 }}
                    回/月
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_1 = count($kaizen_month_1) + $array_meeting[0] + count($study_month_1) + count($other_month_1);
                    $denominator += $total_meeting_kaizen_month_1;
                    echo $total_meeting_kaizen_month_1 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_2 = count($kaizen_month_2) + $array_meeting[1] + count($study_month_2) + count($other_month_2);
                    $denominator += $total_meeting_kaizen_month_2;
                    echo $total_meeting_kaizen_month_2 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_3 = count($kaizen_month_3) + $array_meeting[2] + count($study_month_3) + count($other_month_3);
                    $denominator += $total_meeting_kaizen_month_3;
                    echo $total_meeting_kaizen_month_3 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_4 = count($kaizen_month_4) + $array_meeting[3] + count($study_month_4) + count($other_month_4);
                    $denominator += $total_meeting_kaizen_month_4;
                    echo $total_meeting_kaizen_month_4 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_5 = count($kaizen_month_5) + $array_meeting[4] + count($study_month_5) + count($other_month_5);
                    $denominator += $total_meeting_kaizen_month_5;
                    echo $total_meeting_kaizen_month_5 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_6 = count($kaizen_month_6) + $array_meeting[5] + count($study_month_6) + count($other_month_6);
                    $denominator += $total_meeting_kaizen_month_6;
                    echo $total_meeting_kaizen_month_6 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_7 = count($kaizen_month_7) + $array_meeting[6] + count($study_month_7) + count($other_month_7);
                    $denominator += $total_meeting_kaizen_month_7;
                    echo $total_meeting_kaizen_month_7 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_8 = count($kaizen_month_8) + $array_meeting[7] + count($study_month_8) + count($other_month_8);
                    $denominator += $total_meeting_kaizen_month_8;
                    echo $total_meeting_kaizen_month_8 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_9 = count($kaizen_month_9) + $array_meeting[8] + count($study_month_9) + count($other_month_9);
                    $denominator += $total_meeting_kaizen_month_9;
                    echo $total_meeting_kaizen_month_9 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_10 = count($kaizen_month_10) + $array_meeting[9] + count($study_month_10) + count($other_month_10);
                    $denominator += $total_meeting_kaizen_month_10;
                    echo $total_meeting_kaizen_month_10 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_11 = count($kaizen_month_11) + $array_meeting[10] + count($study_month_11) + count($other_month_11);
                    $denominator += $total_meeting_kaizen_month_11;
                    echo $total_meeting_kaizen_month_11 . '/' . $denominator;
                    ?>
                </td>
                <td>
                    <?php
                    //20200120 NTQ MODIFIED
                    $total_meeting_kaizen_month_12 = count($kaizen_month_12) + $array_meeting[11] + count($study_month_12) + count($other_month_12);
                    $denominator += $total_meeting_kaizen_month_12;
                    echo $total_meeting_kaizen_month_12 . '/' . $denominator;
                    ?>
                </td>
            </tr>
            <tr>
                <td>会合時間</td>
                <td>{{ isset($plan_by_year->meeting_hour)? number_format(round($plan_by_year->meeting_hour, 2), 2) : 0 }}
                    ｈ/月
                </td>
                <td>{{ isset($promotion_cirlce->target_hour_of_meeting)? number_format(round($promotion_cirlce->target_hour_of_meeting, 2), 2) : "0.00" }}
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
                <td>テーマ完了件数</td>
                <td>{{ isset($plan_by_year->case_number_complete)? $plan_by_year->case_number_complete : 0 }} 件/年</td>
                <td>{{ isset($promotion_cirlce->target_case_complete)? $promotion_cirlce->target_case_complete : 0 }}
                    件/年
                </td>
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
                <td>改善（個人）件数</td>
                <td>{{ isset($plan_by_year->case_number_improve)? $plan_by_year->case_number_improve : 0 }} 件/年</td>
                <td>{{ isset($promotion_cirlce->improved_cases)? $promotion_cirlce->improved_cases : 0 }} 件/年</td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_1}}" id="kaizen_month_1">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_1;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                    
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_1 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                     <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_2}}" id="kaizen_month_2">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_2;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                       
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_2 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_3}}" id="kaizen_month_3">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_3;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                           
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_3 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_4}}" id="kaizen_month_4">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_4;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                        
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_4 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_5}}" id="kaizen_month_5">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_5;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                                            
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_5 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_6}}" id="kaizen_month_6">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_6;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                                                      
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_6 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_7}}" id="kaizen_month_7">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_7;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                                       
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_7 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_8}}" id="kaizen_month_8">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_8;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                                                      
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_8 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_9}}" id="kaizen_month_9">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_9;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                                                 
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_9 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_10}}" id="kaizen_month_10">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_10;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                 
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_10 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_11}}" id="kaizen_month_11">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_11;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                      
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_11 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
                <td>
                    <div style="width:100%;display: none">
                        <select style="width:60%" value="{{$kaizen_editinline_month_12}}" id="kaizen_month_12">
                        </select>
                        <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                            <?php
                            $kaizen_editinline_month_total += $kaizen_editinline_month_12;
                            echo '/' . $kaizen_editinline_month_total;
                            ?>
                        </span>
                    </div>                                                  
                    <span style="cursor: pointer" class="txtKaizen" title="ダブルクリックして編集">
                        <?php
                        echo $kaizen_editinline_month_12 . '/' . $kaizen_editinline_month_total;
                        ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>ＱＣ手法勉強会</td>
                <td>{{ isset($plan_by_year->classes_organizing_objective)? $plan_by_year->classes_organizing_objective : 0 }}
                    回/年
                </td>
                <td>{{ isset($promotion_cirlce->objectives_of_organizing_classe)? $promotion_cirlce->objectives_of_organizing_classe : 0 }}
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
        <div class="float-right" style="margin-top: 1rem; display: none" id="btnSaveKaizen">
            <a class="btn btn-back">保存</a>
        </div>
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
            <tr data-line-id="{{$circle_promoter_approval->id}}">
                <td>推進者</td>
                <td>{{ isset($circle_promoter)? $circle_promoter->position.' '.$circle_promoter->name : null }}</td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_approved))
                        <?php
						$user_approved = \App\User::find($circle_promoter_approval->user_approved);
						$name_approved_explode = explode("　", $user_approved->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_approved}}">&times;</div>
                        <span class="d-none month-number">0</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_approved)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-approved">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_jan))
                        <?php
						$user_jan = \App\User::find($circle_promoter_approval->user_jan);
						$name_jan_explode = explode("　", $user_jan->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_jan}}">&times;</div>
                        <span class="d-none month-number">1</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_jan)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-jan">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_feb))
                        <?php
						$user_feb = \App\User::find($circle_promoter_approval->user_feb);
						$name_feb_explode = explode("　", $user_feb->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_feb}}">&times;</div>
                        <span class="d-none month-number">2</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_feb)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-feb">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_mar))
                        <?php
						$user_mar = \App\User::find($circle_promoter_approval->user_mar);
						$user_mar_explode = explode("　", $user_mar->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_mar}}">&times;</div>
                        <span class="d-none month-number">3</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_mar)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-mar">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_apr))
                        <?php
						$user_apr = \App\User::find($circle_promoter_approval->user_apr);
						$user_apr_explode = explode("　", $user_apr->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_apr}}">&times;</div>
                        <span class="d-none month-number">4</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_apr)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-apr">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_may))
                        <?php
						$user_may = \App\User::find($circle_promoter_approval->user_may);
						$user_may_explode = explode("　", $user_may->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_may}}">&times;</div>
                        <span class="d-none month-number">5</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_may_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_may)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-may">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_jun))
                        <?php
						$user_jun = \App\User::find($circle_promoter_approval->user_jun);
						$user_jun_explode = explode("　", $user_jun->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_jun}}">&times;</div>
                        <span class="d-none month-number">6</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_jun)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-jun">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_jul))
                        <?php
						$user_jul = \App\User::find($circle_promoter_approval->user_jul);
						$user_jul_explode = explode("　", $user_jul->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_jul}}">&times;</div>
                        <span class="d-none month-number">7</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_jul)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-jul">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_aug))
                        <?php
						$user_aug = \App\User::find($circle_promoter_approval->user_aug);
						$user_aug_explode = explode("　", $user_aug->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_aug}}">&times;</div>
                        <span class="d-none month-number">8</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_aug)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-aug">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_sep))
                        <?php
						$user_sep = \App\User::find($circle_promoter_approval->user_sep);
						$user_sep_explode = explode("　", $user_sep->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_sep}}">&times;</div>
                        <span class="d-none month-number">9</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_sep)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-sep">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_oct))
                        <?php
						$user_oct = \App\User::find($circle_promoter_approval->user_oct);
						$user_oct_explode = explode("　", $user_oct->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_oct}}">&times;</div>
                        <span class="d-none month-number">10</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_oct)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-oct">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_nov))
                        <?php
						$user_nov = \App\User::find($circle_promoter_approval->user_nov);
						$user_nov_explode = explode("　", $user_nov->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_nov}}">&times;</div>
                        <span class="d-none month-number">11</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_nov)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-now">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($circle_promoter_approval->user_dec))
                        <?php
						$user_dec = \App\User::find($circle_promoter_approval->user_dec);
						$user_dec_explode = explode("　", $user_dec->name);
						?>
                        <div class="remove-stamp" data-user="{{$circle_promoter_approval->user_dec}}">&times;</div>
                        <span class="d-none month-number">12</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($circle_promoter_approval->date_dec)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="CirclePromoterSign(this);"
                              data-content="circle-dec">（印）</span>
                    @endif
                </td>
                @if(isset($circle_promoter_approval->id))
                    {{ Form::model($circle_promoter_approval, array('route' => array('activityapproval.update', $circle_promoter_approval->id), 'method' => 'PUT', 'id' => 'circle-promoter-approval-form')) }}
                @else
                    <form id="circle-promoter-approval-form" method="POST"
                          action="{{ action('ActivityApprovalController@store') }}">
                        @endif
                        @csrf
                        <input type="hidden" name="year" value="{{ isset($_GET['year'])? $_GET['year'] : date('Y') }}"/>
                        <input type="hidden" name="promotion_circle_id"
                               value="{{ isset($promotion_cirlce)? $promotion_cirlce->id : null }}"/>
                        <input type="hidden" name="approver_classification"
                               value="{{ App\Enums\RoleIndicatorEnum::CIRCLE_PROMOTER }}"/>
                        <input type="hidden" name="user_approved"
                               value="{{ isset($circle_promoter_approval->user_approved)? $circle_promoter_approval->user_approved : null }}"
                               id="user-circle-approved"/>
                        <input type="hidden" name="date_approved"
                               value="{{ isset($circle_promoter_approval->date_approved)? $circle_promoter_approval->date_approved : null }}"
                               id="date-circle-approved"/>
                        <input type="hidden" name="user_jan"
                               value="{{ isset($circle_promoter_approval->user_jan)? $circle_promoter_approval->user_jan : null }}"
                               id="user-circle-jan"/>
                        <input type="hidden" name="date_jan"
                               value="{{ isset($circle_promoter_approval->date_jan)? $circle_promoter_approval->date_jan : null }}"
                               id="date-circle-jan"/>
                        <input type="hidden" name="user_feb"
                               value="{{ isset($circle_promoter_approval->user_feb)? $circle_promoter_approval->user_feb : null }}"
                               id="user-circle-feb"/>
                        <input type="hidden" name="date_feb"
                               value="{{ isset($circle_promoter_approval->date_feb)? $circle_promoter_approval->date_feb : null }}"
                               id="date-circle-feb"/>
                        <input type="hidden" name="user_mar"
                               value="{{ isset($circle_promoter_approval->user_mar)? $circle_promoter_approval->user_mar : null }}"
                               id="user-circle-mar"/>
                        <input type="hidden" name="date_mar"
                               value="{{ isset($circle_promoter_approval->date_mar)? $circle_promoter_approval->date_mar : null }}"
                               id="date-circle-mar"/>
                        <input type="hidden" name="user_apr"
                               value="{{ isset($circle_promoter_approval->user_apr)? $circle_promoter_approval->user_apr : null }}"
                               id="user-circle-apr"/>
                        <input type="hidden" name="date_apr"
                               value="{{ isset($circle_promoter_approval->date_apr)? $circle_promoter_approval->date_apr : null }}"
                               id="date-circle-apr"/>
                        <input type="hidden" name="user_may"
                               value="{{ isset($circle_promoter_approval->user_may)? $circle_promoter_approval->user_may : null }}"
                               id="user-circle-may"/>
                        <input type="hidden" name="date_may"
                               value="{{ isset($circle_promoter_approval->date_may)? $circle_promoter_approval->date_may : null }}"
                               id="date-circle-may"/>
                        <input type="hidden" name="user_jun"
                               value="{{ isset($circle_promoter_approval->user_jun)? $circle_promoter_approval->user_jun : null }}"
                               id="user-circle-jun"/>
                        <input type="hidden" name="date_jun"
                               value="{{ isset($circle_promoter_approval->date_jun)? $circle_promoter_approval->date_jun : null }}"
                               id="date-circle-jun"/>
                        <input type="hidden" name="user_jul"
                               value="{{ isset($circle_promoter_approval->user_jul)? $circle_promoter_approval->user_jul : null }}"
                               id="user-circle-jul"/>
                        <input type="hidden" name="date_jul"
                               value="{{ isset($circle_promoter_approval->date_jul)? $circle_promoter_approval->date_jul : null }}"
                               id="date-circle-jul"/>
                        <input type="hidden" name="user_aug"
                               value="{{ isset($circle_promoter_approval->user_aug)? $circle_promoter_approval->user_aug : null }}"
                               id="user-circle-aug"/>
                        <input type="hidden" name="date_aug"
                               value="{{ isset($circle_promoter_approval->date_aug)? $circle_promoter_approval->date_aug : null }}"
                               id="date-circle-aug"/>
                        <input type="hidden" name="user_sep"
                               value="{{ isset($circle_promoter_approval->user_sep)? $circle_promoter_approval->user_sep : null }}"
                               id="user-circle-sep"/>
                        <input type="hidden" name="date_sep"
                               value="{{ isset($circle_promoter_approval->date_sep)? $circle_promoter_approval->date_sep : null }}"
                               id="date-circle-sep"/>
                        <input type="hidden" name="user_oct"
                               value="{{ isset($circle_promoter_approval->user_oct)? $circle_promoter_approval->user_oct : null }}"
                               id="user-circle-oct"/>
                        <input type="hidden" name="date_oct"
                               value="{{ isset($circle_promoter_approval->date_oct)? $circle_promoter_approval->date_oct : null }}"
                               id="date-circle-oct"/>
                        <input type="hidden" name="user_nov"
                               value="{{ isset($circle_promoter_approval->user_nov)? $circle_promoter_approval->user_nov : null }}"
                               id="user-circle-now"/>
                        <input type="hidden" name="date_nov"
                               value="{{ isset($circle_promoter_approval->date_nov)? $circle_promoter_approval->date_nov : null }}"
                               id="date-circle-now"/>
                        <input type="hidden" name="user_dec"
                               value="{{ isset($circle_promoter_approval->user_dec)? $circle_promoter_approval->user_dec : null }}"
                               id="user-circle-dec"/>
                        <input type="hidden" name="date_dec"
                               value="{{ isset($circle_promoter_approval->date_dec)? $circle_promoter_approval->date_dec : null }}"
                               id="date-circle-dec"/>
                    </form>
            </tr>
            <tr data-line-id="{{$place_caretaker_approval->id}}">
                <td>職場世話人</td>
                <td>{{ isset($place_caretaker)? $place_caretaker->position.' '.$place_caretaker->name : null }}</td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_approved))
                        <?php
						$user_approved = \App\User::find($place_caretaker_approval->user_approved);
						$name_approved_explode = explode("　", $user_approved->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_approved}}">&times;</div>
                        <span class="d-none month-number">0</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_approved)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);"
                              data-content="place-approved">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_jan))
                        <?php
						$user_jan = \App\User::find($place_caretaker_approval->user_jan);
						$name_jan_explode = explode("　", $user_jan->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_jan}}">&times;</div>
                        <span class="d-none month-number">1</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_jan)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-jan">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_feb))
                        <?php
						$user_feb = \App\User::find($place_caretaker_approval->user_feb);
						$name_feb_explode = explode("　", $user_feb->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_feb}}">&times;</div>
                        <span class="d-none month-number">2</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_feb)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-feb">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_mar))
                        <?php
						$user_mar = \App\User::find($place_caretaker_approval->user_mar);
						$user_mar_explode = explode("　", $user_mar->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_mar}}">&times;</div>
                        <span class="d-none month-number">3</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_mar)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-mar">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_apr))
                        <?php
						$user_apr = \App\User::find($place_caretaker_approval->user_apr);
						$user_apr_explode = explode("　", $user_apr->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_apr}}">&times;</div>
                        <span class="d-none month-number">4</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_apr)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-apr">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_may))
                        <?php
						$user_may = \App\User::find($place_caretaker_approval->user_may);
						$user_may_explode = explode("　", $user_may->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_may}}">&times;</div>
                        <span class="d-none month-number">5</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_may_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_may)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-may">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_jun))
                        <?php
						$user_jun = \App\User::find($place_caretaker_approval->user_jun);
						$user_jun_explode = explode("　", $user_jun->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_jun}}">&times;</div>
                        <span class="d-none month-number">6</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_jun)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-jun">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_jul))
                        <?php
						$user_jul = \App\User::find($place_caretaker_approval->user_jul);
						$user_jul_explode = explode("　", $user_jul->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_jul}}">&times;</div>
                        <span class="d-none month-number">7</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_jul)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-jul">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_aug))
                        <?php
						$user_aug = \App\User::find($place_caretaker_approval->user_aug);
						$user_aug_explode = explode("　", $user_aug->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_aug}}">&times;</div>
                        <span class="d-none month-number">8</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_aug)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-aug">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_sep))
                        <?php
						$user_sep = \App\User::find($place_caretaker_approval->user_sep);
						$user_sep_explode = explode("　", $user_sep->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_sep}}">&times;</div>
                        <span class="d-none month-number">9</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_sep)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-sep">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_oct))
                        <?php
						$user_oct = \App\User::find($place_caretaker_approval->user_oct);
						$user_oct_explode = explode("　", $user_oct->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_oct}}">&times;</div>
                        <span class="d-none month-number">10</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_oct)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-oct">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_nov))
                        <?php
						$user_nov = \App\User::find($place_caretaker_approval->user_nov);
						$user_nov_explode = explode("　", $user_nov->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_nov}}">&times;</div>
                        <span class="d-none month-number">11</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_nov)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-now">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($place_caretaker_approval->user_dec))
                        <?php
						$user_dec = \App\User::find($place_caretaker_approval->user_dec);
						$user_dec_explode = explode("　", $user_dec->name);
						?>
                        <div class="remove-stamp" data-user="{{$place_caretaker_approval->user_dec}}">&times;</div>
                        <span class="d-none month-number">12</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($place_caretaker_approval->date_dec)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="PlaceCaretakerSign(this);" data-content="place-dec">（印）</span>
                    @endif
                </td>
                @if(isset($place_caretaker_approval->id))
                    {{ Form::model($place_caretaker_approval, array('route' => array('activityapproval.update', $place_caretaker_approval->id), 'method' => 'PUT', 'id' => 'place-caretaker-approval-form')) }}
                @else
                    <form id="place-caretaker-approval-form" method="POST"
                          action="{{ action('ActivityApprovalController@store') }}">
                        @endif
                        @csrf
                        <input type="hidden" name="year" value="{{ isset($_GET['year'])? $_GET['year'] : date('Y') }}"/>
                        <input type="hidden" name="promotion_circle_id"
                               value="{{ isset($promotion_cirlce)? $promotion_cirlce->id : null }}"/>
                        <input type="hidden" name="approver_classification"
                               value="{{ App\Enums\RoleIndicatorEnum::PLACE_CARETAKER }}"/>
                        <input type="hidden" name="user_approved"
                               value="{{ isset($place_caretaker_approval->user_approved)? $place_caretaker_approval->user_approved : null }}"
                               id="user-place-approved"/>
                        <input type="hidden" name="date_approved"
                               value="{{ isset($place_caretaker_approval->date_approved)? $place_caretaker_approval->date_approved : null }}"
                               id="date-place-approved"/>
                        <input type="hidden" name="user_jan"
                               value="{{ isset($place_caretaker_approval->user_jan)? $place_caretaker_approval->user_jan : null }}"
                               id="user-place-jan"/>
                        <input type="hidden" name="date_jan"
                               value="{{ isset($place_caretaker_approval->date_jan)? $place_caretaker_approval->date_jan : null }}"
                               id="date-place-jan"/>
                        <input type="hidden" name="user_feb"
                               value="{{ isset($place_caretaker_approval->user_feb)? $place_caretaker_approval->user_feb : null }}"
                               id="user-place-feb"/>
                        <input type="hidden" name="date_feb"
                               value="{{ isset($place_caretaker_approval->date_feb)? $place_caretaker_approval->date_feb : null }}"
                               id="date-place-feb"/>
                        <input type="hidden" name="user_mar"
                               value="{{ isset($place_caretaker_approval->user_mar)? $place_caretaker_approval->user_mar : null }}"
                               id="user-place-mar"/>
                        <input type="hidden" name="date_mar"
                               value="{{ isset($place_caretaker_approval->date_mar)? $place_caretaker_approval->date_mar : null }}"
                               id="date-place-mar"/>
                        <input type="hidden" name="user_apr"
                               value="{{ isset($place_caretaker_approval->user_apr)? $place_caretaker_approval->user_apr : null }}"
                               id="user-place-apr"/>
                        <input type="hidden" name="date_apr"
                               value="{{ isset($place_caretaker_approval->date_apr)? $place_caretaker_approval->date_apr : null }}"
                               id="date-place-apr"/>
                        <input type="hidden" name="user_may"
                               value="{{ isset($place_caretaker_approval->user_may)? $place_caretaker_approval->user_may : null }}"
                               id="user-place-may"/>
                        <input type="hidden" name="date_may"
                               value="{{ isset($place_caretaker_approval->date_may)? $place_caretaker_approval->date_may : null }}"
                               id="date-place-may"/>
                        <input type="hidden" name="user_jun"
                               value="{{ isset($place_caretaker_approval->user_jun)? $place_caretaker_approval->user_jun : null }}"
                               id="user-place-jun"/>
                        <input type="hidden" name="date_jun"
                               value="{{ isset($place_caretaker_approval->date_jun)? $place_caretaker_approval->date_jun : null }}"
                               id="date-place-jun"/>
                        <input type="hidden" name="user_jul"
                               value="{{ isset($place_caretaker_approval->user_jul)? $place_caretaker_approval->user_jul : null }}"
                               id="user-place-jul"/>
                        <input type="hidden" name="date_jul"
                               value="{{ isset($place_caretaker_approval->date_jul)? $place_caretaker_approval->date_jul : null }}"
                               id="date-place-jul"/>
                        <input type="hidden" name="user_aug"
                               value="{{ isset($place_caretaker_approval->user_aug)? $place_caretaker_approval->user_aug : null }}"
                               id="user-place-aug"/>
                        <input type="hidden" name="date_aug"
                               value="{{ isset($place_caretaker_approval->date_aug)? $place_caretaker_approval->date_aug : null }}"
                               id="date-place-aug"/>
                        <input type="hidden" name="user_sep"
                               value="{{ isset($place_caretaker_approval->user_sep)? $place_caretaker_approval->user_sep : null }}"
                               id="user-place-sep"/>
                        <input type="hidden" name="date_sep"
                               value="{{ isset($place_caretaker_approval->date_sep)? $place_caretaker_approval->date_sep : null }}"
                               id="date-place-sep"/>
                        <input type="hidden" name="user_oct"
                               value="{{ isset($place_caretaker_approval->user_oct)? $place_caretaker_approval->user_oct : null }}"
                               id="user-place-oct"/>
                        <input type="hidden" name="date_oct"
                               value="{{ isset($place_caretaker_approval->date_oct)? $place_caretaker_approval->date_oct : null }}"
                               id="date-place-oct"/>
                        <input type="hidden" name="user_nov"
                               value="{{ isset($place_caretaker_approval->user_nov)? $place_caretaker_approval->user_nov : null }}"
                               id="user-place-now"/>
                        <input type="hidden" name="date_nov"
                               value="{{ isset($place_caretaker_approval->date_nov)? $place_caretaker_approval->date_nov : null }}"
                               id="date-place-now"/>
                        <input type="hidden" name="user_dec"
                               value="{{ isset($place_caretaker_approval->user_dec)? $place_caretaker_approval->user_dec : null }}"
                               id="user-place-dec"/>
                        <input type="hidden" name="date_dec"
                               value="{{ isset($place_caretaker_approval->date_dec)? $place_caretaker_approval->date_dec : null }}"
                               id="date-place-dec"/>
                    </form>
            </tr>
            <tr data-line-id="{{$department_caretaker_approval->id}}">
                <td>部門世話人</td>
                <td>{{ isset($department_caretaker)? $department_caretaker->position.' '.$department_caretaker->name : null }}</td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_approved))
                        <?php
						$user_approved = \App\User::find($department_caretaker_approval->user_approved);
						$name_approved_explode = explode("　", $user_approved->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_approved}}">&times;</div>
                        <span class="d-none month-number">0</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_approved)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-approved">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_jan))
                        <?php
						$user_jan = \App\User::find($department_caretaker_approval->user_jan);
						$name_jan_explode = explode("　", $user_jan->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_jan}}">&times;</div>
                        <span class="d-none month-number">1</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_jan)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-jan">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_feb))
                        <?php
						$user_feb = \App\User::find($department_caretaker_approval->user_feb);
						$name_feb_explode = explode("　", $user_feb->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_feb}}">&times;</div>
                        <span class="d-none month-number">2</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_feb)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-feb">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_mar))
                        <?php
						$user_mar = \App\User::find($department_caretaker_approval->user_mar);
						$user_mar_explode = explode("　", $user_mar->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_mar}}">&times;</div>
                        <span class="d-none month-number">3</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_mar)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-mar">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_apr))
                        <?php
						$user_apr = \App\User::find($department_caretaker_approval->user_apr);
						$user_apr_explode = explode("　", $user_apr->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_apr}}">&times;</div>
                        <span class="d-none month-number">4</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_apr)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-apr">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_may))
                        <?php
						$user_may = \App\User::find($department_caretaker_approval->user_may);
						$user_may_explode = explode("　", $user_may->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_may}}">&times;</div>
                        <span class="d-none month-number">5</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{  $user_may_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_may)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-may">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_jun))
                        <?php
						$user_jun = \App\User::find($department_caretaker_approval->user_jun);
						$user_jun_explode = explode("　", $user_jun->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_jun}}">&times;</div>
                        <span class="d-none month-number">6</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_jun)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-jun">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_jul))
                        <?php
						$user_jul = \App\User::find($department_caretaker_approval->user_jul);
						$user_jul_explode = explode("　", $user_jul->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_jul}}">&times;</div>
                        <span class="d-none month-number">7</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_jul)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-jul">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_aug))
                        <?php
						$user_aug = \App\User::find($department_caretaker_approval->user_aug);
						$user_aug_explode = explode("　", $user_aug->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_aug}}">&times;</div>
                        <span class="d-none month-number">8</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_aug)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-aug">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_sep))
                        <?php
						$user_sep = \App\User::find($department_caretaker_approval->user_sep);
						$user_sep_explode = explode("　", $user_sep->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_sep}}">&times;</div>
                        <span class="d-none month-number">9</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_sep)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-sep">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_oct))
                        <?php
						$user_oct = \App\User::find($department_caretaker_approval->user_oct);
						$user_oct_explode = explode("　", $user_oct->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_oct}}">&times;</div>
                        <span class="d-none month-number">10</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_oct)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-oct">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_nov))
                        <?php
						$user_nov = \App\User::find($department_caretaker_approval->user_nov);
						$user_nov_explode = explode("　", $user_nov->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_nov}}">&times;</div>
                        <span class="d-none month-number">11</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_nov)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-now">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_caretaker_approval->user_dec))
                        <?php
						$user_dec = \App\User::find($department_caretaker_approval->user_dec);
						$user_dec_explode = explode("　", $user_dec->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_caretaker_approval->user_dec}}">&times;</div>
                        <span class="d-none month-number">12</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_caretaker_approval->date_dec)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentCaretakerSign(this);"
                              data-content="department-caretaker-dec">（印）</span>
                    @endif
                </td>
                @if(isset($department_caretaker_approval->id))
                    {{ Form::model($department_caretaker_approval, array('route' => array('activityapproval.update', $department_caretaker_approval->id), 'method' => 'PUT', 'id' => 'department-caretaker-approval-form')) }}
                @else
                    <form id="department-caretaker-approval-form" method="POST"
                          action="{{ action('ActivityApprovalController@store') }}">
                        @endif
                        @csrf
                        <input type="hidden" name="year" value="{{ isset($_GET['year'])? $_GET['year'] : date('Y') }}"/>
                        <input type="hidden" name="promotion_circle_id"
                               value="{{ isset($promotion_cirlce)? $promotion_cirlce->id : null }}"/>
                        <input type="hidden" name="approver_classification"
                               value="{{ App\Enums\RoleIndicatorEnum::DEPARTMENT_CARETAKER }}"/>
                        <input type="hidden" name="user_approved"
                               value="{{ isset($department_caretaker_approval->user_approved)? $department_caretaker_approval->user_approved : null }}"
                               id="user-department-caretaker-approved"/>
                        <input type="hidden" name="date_approved"
                               value="{{ isset($department_caretaker_approval->date_approved)? $department_caretaker_approval->date_approved : null }}"
                               id="date-department-caretaker-approved"/>
                        <input type="hidden" name="user_jan"
                               value="{{ isset($department_caretaker_approval->user_jan)? $department_caretaker_approval->user_jan : null }}"
                               id="user-department-caretaker-jan"/>
                        <input type="hidden" name="date_jan"
                               value="{{ isset($department_caretaker_approval->date_jan)? $department_caretaker_approval->date_jan : null }}"
                               id="date-department-caretaker-jan"/>
                        <input type="hidden" name="user_feb"
                               value="{{ isset($department_caretaker_approval->user_feb)? $department_caretaker_approval->user_feb : null }}"
                               id="user-department-caretaker-feb"/>
                        <input type="hidden" name="date_feb"
                               value="{{ isset($department_caretaker_approval->date_feb)? $department_caretaker_approval->date_feb : null }}"
                               id="date-department-caretaker-feb"/>
                        <input type="hidden" name="user_mar"
                               value="{{ isset($department_caretaker_approval->user_mar)? $department_caretaker_approval->user_mar : null }}"
                               id="user-department-caretaker-mar"/>
                        <input type="hidden" name="date_mar"
                               value="{{ isset($department_caretaker_approval->date_mar)? $department_caretaker_approval->date_mar : null }}"
                               id="date-department-caretaker-mar"/>
                        <input type="hidden" name="user_apr"
                               value="{{ isset($department_caretaker_approval->user_apr)? $department_caretaker_approval->user_apr : null }}"
                               id="user-department-caretaker-apr"/>
                        <input type="hidden" name="date_apr"
                               value="{{ isset($department_caretaker_approval->date_apr)? $department_caretaker_approval->date_apr : null }}"
                               id="date-department-caretaker-apr"/>
                        <input type="hidden" name="user_may"
                               value="{{ isset($department_caretaker_approval->user_may)? $department_caretaker_approval->user_may : null }}"
                               id="user-department-caretaker-may"/>
                        <input type="hidden" name="date_may"
                               value="{{ isset($department_caretaker_approval->date_may)? $department_caretaker_approval->date_may : null }}"
                               id="date-department-caretaker-may"/>
                        <input type="hidden" name="user_jun"
                               value="{{ isset($department_caretaker_approval->user_jun)? $department_caretaker_approval->user_jun : null }}"
                               id="user-department-caretaker-jun"/>
                        <input type="hidden" name="date_jun"
                               value="{{ isset($department_caretaker_approval->date_jun)? $department_caretaker_approval->date_jun : null }}"
                               id="date-department-caretaker-jun"/>
                        <input type="hidden" name="user_jul"
                               value="{{ isset($department_caretaker_approval->user_jul)? $department_caretaker_approval->user_jul : null }}"
                               id="user-department-caretaker-jul"/>
                        <input type="hidden" name="date_jul"
                               value="{{ isset($department_caretaker_approval->date_jul)? $department_caretaker_approval->date_jul : null }}"
                               id="date-department-caretaker-jul"/>
                        <input type="hidden" name="user_aug"
                               value="{{ isset($department_caretaker_approval->user_aug)? $department_caretaker_approval->user_aug : null }}"
                               id="user-department-caretaker-aug"/>
                        <input type="hidden" name="date_aug"
                               value="{{ isset($department_caretaker_approval->date_aug)? $department_caretaker_approval->date_aug : null }}"
                               id="date-department-caretaker-aug"/>
                        <input type="hidden" name="user_sep"
                               value="{{ isset($department_caretaker_approval->user_sep)? $department_caretaker_approval->user_sep : null }}"
                               id="user-department-caretaker-sep"/>
                        <input type="hidden" name="date_sep"
                               value="{{ isset($department_caretaker_approval->date_sep)? $department_caretaker_approval->date_sep : null }}"
                               id="date-department-caretaker-sep"/>
                        <input type="hidden" name="user_oct"
                               value="{{ isset($department_caretaker_approval->user_oct)? $department_caretaker_approval->user_oct : null }}"
                               id="user-department-caretaker-oct"/>
                        <input type="hidden" name="date_oct"
                               value="{{ isset($department_caretaker_approval->date_oct)? $department_caretaker_approval->date_oct : null }}"
                               id="date-department-caretaker-oct"/>
                        <input type="hidden" name="user_nov"
                               value="{{ isset($department_caretaker_approval->user_nov)? $department_caretaker_approval->user_nov : null }}"
                               id="user-department-caretaker-now"/>
                        <input type="hidden" name="date_nov"
                               value="{{ isset($department_caretaker_approval->date_nov)? $department_caretaker_approval->date_nov : null }}"
                               id="date-department-caretaker-now"/>
                        <input type="hidden" name="user_dec"
                               value="{{ isset($department_caretaker_approval->user_dec)? $department_caretaker_approval->user_dec : null }}"
                               id="user-department-caretaker-dec"/>
                        <input type="hidden" name="date_dec"
                               value="{{ isset($department_caretaker_approval->date_dec)? $department_caretaker_approval->date_dec : null }}"
                               id="date-department-caretaker-dec"/>
                    </form>
            </tr>
            <tr data-line-id="{{$department_manager_approval->id}}">
                <td>部門責任者</td>
                <td>{{ isset($department_manager)? $department_manager->position.' '.$department_manager->name : null }}</td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_approved))
                        <?php
						$user_approved = \App\User::find($department_manager_approval->user_approved);
						$name_approved_explode = explode("　", $user_approved->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_approved}}">&times;</div>
                        <span class="d-none month-number">0</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_approved_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_approved)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-approved">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_jan))
                        <?php
						$user_jan = \App\User::find($department_manager_approval->user_jan);
						$name_jan_explode = explode("　", $user_jan->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_jan}}">&times;</div>
                        <span class="d-none month-number">1</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_jan_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_jan)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-jan">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_feb))
                        <?php
						$user_feb = \App\User::find($department_manager_approval->user_feb);
						$name_feb_explode = explode("　", $user_feb->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_feb}}">&times;</div>
                        <span class="d-none month-number">2</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $name_feb_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_feb)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-feb">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_mar))
                        <?php
						$user_mar = \App\User::find($department_manager_approval->user_mar);
						$user_mar_explode = explode("　", $user_mar->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_mar}}">&times;</div>
                        <span class="d-none month-number">3</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_mar_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_mar)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-mar">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_apr))
                        <?php
						$user_apr = \App\User::find($department_manager_approval->user_apr);
						$user_apr_explode = explode("　", $user_apr->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_apr}}">&times;</div>
                        <span class="d-none month-number">4</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_apr_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_apr)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-apr">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_may))
                        <?php
						$user_may = \App\User::find($department_manager_approval->user_may);
						$user_may_explode = explode("　", $user_may->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_may}}">&times;</div>
                        <span class="d-none month-number">5</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{  $user_may_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_may)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-may">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_jun))
                        <?php
						$user_jun = \App\User::find($department_manager_approval->user_jun);
						$user_jun_explode = explode("　", $user_jun->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_jun}}">&times;</div>
                        <span class="d-none month-number">6</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jun_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_jun)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-jun">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_jul))
                        <?php
						$user_jul = \App\User::find($department_manager_approval->user_jul);
						$user_jul_explode = explode("　", $user_jul->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_jul}}">&times;</div>
                        <span class="d-none month-number">7</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_jul_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_jul)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-jul">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_aug))
                        <?php
						$user_aug = \App\User::find($department_manager_approval->user_aug);
						$user_aug_explode = explode("　", $user_aug->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_aug}}">&times;</div>
                        <span class="d-none month-number">8</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_aug_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_aug)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-aug">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_sep))
                        <?php
						$user_sep = \App\User::find($department_manager_approval->user_sep);
						$user_sep_explode = explode("　", $user_sep->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_sep}}">&times;</div>
                        <span class="d-none month-number">9</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_sep_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_sep)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-sep">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_oct))
                        <?php
						$user_oct = \App\User::find($department_manager_approval->user_oct);
						$user_oct_explode = explode("　", $user_oct->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_oct}}">&times;</div>
                        <span class="d-none month-number">10</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_oct_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_oct)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-oct">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_nov))
                        <?php
						$user_nov = \App\User::find($department_manager_approval->user_nov);
						$user_nov_explode = explode("　", $user_nov->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_nov}}">&times;</div>
                        <span class="d-none month-number">11</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_nov_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_nov)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-now">（印）</span>
                    @endif
                </td>
                <td class="stamp-box">
                    @if(isset($department_manager_approval->user_dec))
                        <?php
						$user_dec = \App\User::find($department_manager_approval->user_dec);
						$user_dec_explode = explode("　", $user_dec->name);
						?>
                        <div class="remove-stamp" data-user="{{$department_manager_approval->user_dec}}">&times;</div>
                        <span class="d-none month-number">12</span>
                        <div class="stamp">
                            <div class="stamp-upper">{{ $user_dec_explode[0] }}</div>
                            <div
                                class="stamp-lower">{{ date('m/d', strtotime($department_manager_approval->date_dec)) }}</div>
                        </div>
                    @else
                        <span class="stamp-only" onclick="DemartmentManagerSign(this);"
                              data-content="department-manager-dec">（印）</span>
                    @endif
                </td>
                @if(isset($department_manager_approval->id))
                    {{ Form::model($department_manager_approval, array('route' => array('activityapproval.update', $department_manager_approval->id), 'method' => 'PUT', 'id' => 'department-manager-approval-form')) }}
                @else
                    <form id="department-manager-approval-form" method="POST"
                          action="{{ action('ActivityApprovalController@store') }}">
                        @endif
                        @csrf
                        <input type="hidden" name="year" value="{{ isset($_GET['year'])? $_GET['year'] : date('Y') }}"/>
                        <input type="hidden" name="promotion_circle_id"
                               value="{{ isset($promotion_cirlce)? $promotion_cirlce->id : null }}"/>
                        <input type="hidden" name="approver_classification"
                               value="{{ App\Enums\RoleIndicatorEnum::DEPARTMENT_MANAGER }}"/>
                        <input type="hidden" name="user_approved"
                               value="{{ isset($department_manager_approval->user_approved)? $department_manager_approval->user_approved : null }}"
                               id="user-department-manager-approved"/>
                        <input type="hidden" name="date_approved"
                               value="{{ isset($department_manager_approval->date_approved)? $department_manager_approval->date_approved : null }}"
                               id="date-department-manager-approved"/>
                        <input type="hidden" name="user_jan"
                               value="{{ isset($department_manager_approval->user_jan)? $department_manager_approval->user_jan : null }}"
                               id="user-department-manager-jan"/>
                        <input type="hidden" name="date_jan"
                               value="{{ isset($department_manager_approval->date_jan)? $department_manager_approval->date_jan : null }}"
                               id="date-department-manager-jan"/>
                        <input type="hidden" name="user_feb"
                               value="{{ isset($department_manager_approval->user_feb)? $department_manager_approval->user_feb : null }}"
                               id="user-department-manager-feb"/>
                        <input type="hidden" name="date_feb"
                               value="{{ isset($department_manager_approval->date_feb)? $department_manager_approval->date_feb : null }}"
                               id="date-department-manager-feb"/>
                        <input type="hidden" name="user_mar"
                               value="{{ isset($department_manager_approval->user_mar)? $department_manager_approval->user_mar : null }}"
                               id="user-department-manager-mar"/>
                        <input type="hidden" name="date_mar"
                               value="{{ isset($department_manager_approval->date_mar)? $department_manager_approval->date_mar : null }}"
                               id="date-department-manager-mar"/>
                        <input type="hidden" name="user_apr"
                               value="{{ isset($department_manager_approval->user_apr)? $department_manager_approval->user_apr : null }}"
                               id="user-department-manager-apr"/>
                        <input type="hidden" name="date_apr"
                               value="{{ isset($department_manager_approval->date_apr)? $department_manager_approval->date_apr : null }}"
                               id="date-department-manager-apr"/>
                        <input type="hidden" name="user_may"
                               value="{{ isset($department_manager_approval->user_may)? $department_manager_approval->user_may : null }}"
                               id="user-department-manager-may"/>
                        <input type="hidden" name="date_may"
                               value="{{ isset($department_manager_approval->date_may)? $department_manager_approval->date_may : null }}"
                               id="date-department-manager-may"/>
                        <input type="hidden" name="user_jun"
                               value="{{ isset($department_manager_approval->user_jun)? $department_manager_approval->user_jun : null }}"
                               id="user-department-manager-jun"/>
                        <input type="hidden" name="date_jun"
                               value="{{ isset($department_manager_approval->date_jun)? $department_manager_approval->date_jun : null }}"
                               id="date-department-manager-jun"/>
                        <input type="hidden" name="user_jul"
                               value="{{ isset($department_manager_approval->user_jul)? $department_manager_approval->user_jul : null }}"
                               id="user-department-manager-jul"/>
                        <input type="hidden" name="date_jul"
                               value="{{ isset($department_manager_approval->date_jul)? $department_manager_approval->date_jul : null }}"
                               id="date-department-manager-jul"/>
                        <input type="hidden" name="user_aug"
                               value="{{ isset($department_manager_approval->user_aug)? $department_manager_approval->user_aug : null }}"
                               id="user-department-manager-aug"/>
                        <input type="hidden" name="date_aug"
                               value="{{ isset($department_manager_approval->date_aug)? $department_manager_approval->date_aug : null }}"
                               id="date-department-manager-aug"/>
                        <input type="hidden" name="user_sep"
                               value="{{ isset($department_manager_approval->user_sep)? $department_manager_approval->user_sep : null }}"
                               id="user-department-manager-sep"/>
                        <input type="hidden" name="date_sep"
                               value="{{ isset($department_manager_approval->date_sep)? $department_manager_approval->date_sep : null }}"
                               id="date-department-manager-sep"/>
                        <input type="hidden" name="user_oct"
                               value="{{ isset($department_manager_approval->user_oct)? $department_manager_approval->user_oct : null }}"
                               id="user-department-manager-oct"/>
                        <input type="hidden" name="date_oct"
                               value="{{ isset($department_manager_approval->date_oct)? $department_manager_approval->date_oct : null }}"
                               id="date-department-manager-oct"/>
                        <input type="hidden" name="user_nov"
                               value="{{ isset($department_manager_approval->user_nov)? $department_manager_approval->user_nov : null }}"
                               id="user-department-manager-now"/>
                        <input type="hidden" name="date_nov"
                               value="{{ isset($department_manager_approval->date_nov)? $department_manager_approval->date_nov : null }}"
                               id="date-department-manager-now"/>
                        <input type="hidden" name="user_dec"
                               value="{{ isset($department_manager_approval->user_dec)? $department_manager_approval->user_dec : null }}"
                               id="user-department-manager-dec"/>
                        <input type="hidden" name="date_dec"
                               value="{{ isset($department_manager_approval->date_dec)? $department_manager_approval->date_dec : null }}"
                               id="date-department-manager-dec"/>
                    </form>
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
              action="{{ action('ActivityApprovalController@removeStamp') }}">
            @csrf
            <input type="hidden" id="line-id" name="line_id" value=""/>
            <input type="hidden" id="month-stamp" name="month_stamp" value=""/>
            <input type="hidden" id="user-stamp" name="user_stamp" value=""/>
        </form>
    </div>
    <script>
        function CirclePromoterSign(own) {
                @if(isset($promotion_cirlce->id))
            let id = own.dataset.content;
            $('#user-' + id).val('{{ Auth::id() }}');
            $('#date-' + id).val('{{ date("Y-m-d") }}');
            markStamping();
            $("#circle-promoter-approval-form").submit();
            @else
            alert('{{ \App\Enums\StaticConfig::$Promotion_Circle_Not_Exist }}');
            @endif
        }

        function PlaceCaretakerSign(own) {
                @if(isset($promotion_cirlce->id))
            let id = own.dataset.content;
            $('#user-' + id).val('{{ Auth::id() }}');
            $('#date-' + id).val('{{ date("Y-m-d") }}');
            markStamping();
            $("#place-caretaker-approval-form").submit();
            @else
            alert('{{ \App\Enums\StaticConfig::$Promotion_Circle_Not_Exist }}');
            @endif
        }

        function DemartmentCaretakerSign(own) {
                @if(isset($promotion_cirlce->id))
            let id = own.dataset.content;
            $('#user-' + id).val('{{ Auth::id() }}');
            $('#date-' + id).val('{{ date("Y-m-d") }}');
            markStamping();
            $("#department-caretaker-approval-form").submit();
            @else
            alert('{{ \App\Enums\StaticConfig::$Promotion_Circle_Not_Exist }}');
            @endif
        }

        function DemartmentManagerSign(own) {
                @if(isset($promotion_cirlce->id))
            let id = own.dataset.content;
            $('#user-' + id).val('{{ Auth::id() }}');
            $('#date-' + id).val('{{ date("Y-m-d") }}');
            markStamping();
            $("#department-manager-approval-form").submit();
            @else
            alert('{{ \App\Enums\StaticConfig::$Promotion_Circle_Not_Exist }}');
            @endif
        }

        function nextYear() {
            let year = parseInt($('#currentYear').text());
            $('#currentYear').text('' + (year + 1) + '')
            location.replace('?year=' + (year + 1));
        }

        function prevYear() {
            let year = parseInt($('#currentYear').text());
            $('#currentYear').text('' + (year - 1) + '')
            location.replace('?year=' + (year - 1));
        }

        function firstYear() {
            let year = parseInt($('#currentYear').text());
            let firstYear = {{ $first_year }};
            if (year != firstYear) {
                location.replace('?year=' + firstYear);
            }
        }

        function lastYear() {
            let year = parseInt($('#currentYear').text());
            let lastYear = {{ $last_year }};
            if (year != lastYear) {
                location.replace('?year=' + lastYear);
            }
        }

        @if ($errors->has('promotion_circle_id'))
        alert('{{ \App\Enums\StaticConfig::$Promotion_Circle_Not_Exist }}');
        @endif
        $('#table5 tr td:nth-child(1)').css('text-align', 'left');
        $('#table5 tr td:nth-child(2)').css('text-align', 'left');
        $('#table5 thead tr td').css('text-align', 'center');
        $('#table4 tr td:first-child').css('text-align', 'left');
        $('#table4 thead td').css('text-align', 'center');

        function markStamping() {
            sessionStorage.setItem('justStamped', 'yes');
        }

    </script>
    <script>
        $(function () {
            if (sessionStorage.getItem('stampMode')) {
                $('#toggle-remove-stamp').trigger('click');
            }

            <?php
$is_member = in_array(Auth::id(), $unique_members->toArray()) ? 1 : null;
?>
            let isMembers = "{{$is_member}}";
            @if(Auth::user()->access_authority == \App\Enums\AccessAuthority::ADMIN)
            if ($('.remove-stamp').length === 0)
            @else
                if ($('.remove-stamp').length === 0 || ($('.remove-stamp').length !== 0 && !isMembers && $('.remove-stamp[data-user="{{Auth::id()}}"]').length === 0))
            @endif
                {
                    $('.stamp-remove-toggle').hide();
                } else {
                    $('.stamp-remove-toggle').show();
                }
            restoreScroll();
            initializeDropdownlistKaizen();
        });

        function restoreScroll() {
            if (sessionStorage.getItem('justStamped')) {
                window.scrollTo(0, document.body.scrollHeight);
                sessionStorage.removeItem('justStamped');
            }
        }

        @if(Auth::user()->access_authority == \App\Enums\AccessAuthority::ADMIN)
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
        @else
        @if(in_array(Auth::id(), $unique_members->toArray()))
        $('#toggle-remove-stamp').change(function () {
            if (this.checked) {
                @foreach($unique_members as $member)
                $('.remove-stamp[data-user="{{$member}}"]').show('fade', 100);
                @endforeach
                sessionStorage.setItem('stampMode', 'on');
            } else {
                @foreach($unique_members as $member)
                $('.remove-stamp[data-user="{{$member}}"]').hide('fade', 100);
                @endforeach
                if (sessionStorage.getItem('stampMode')) {
                    sessionStorage.removeItem('stampMode');
                }
            }
        });
        @else
        $('#toggle-remove-stamp').change(function () {
            if (this.checked) {
                $('.remove-stamp[data-user="{{Auth::id()}}"]').show('fade', 100);
                sessionStorage.setItem('stampMode', 'on');
            } else {
                $('.remove-stamp[data-user="{{Auth::id()}}"]').hide('fade', 100);
                if (sessionStorage.getItem('stampMode')) {
                    sessionStorage.removeItem('stampMode');
                }
            }
        });
        @endif
        @endif



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

        @if ($errors->any())
        alert("{{$errors->first()}}");
        @endif
        function initializeDropdownlistKaizen()
        {
            initializeDropdownlistKaizen_month_1();
            initializeDropdownlistKaizen_month_2();
            initializeDropdownlistKaizen_month_3();
            initializeDropdownlistKaizen_month_4();
            initializeDropdownlistKaizen_month_5();
            initializeDropdownlistKaizen_month_6();
            initializeDropdownlistKaizen_month_7();
            initializeDropdownlistKaizen_month_8();
            initializeDropdownlistKaizen_month_9();
            initializeDropdownlistKaizen_month_10();
            initializeDropdownlistKaizen_month_11();
            initializeDropdownlistKaizen_month_12();
        }
        function initializeDropdownlistKaizen_month_1()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_1 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_1').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_2()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_2 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_2').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_3()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_3 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_3').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_4()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_4 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_4').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_5()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_5 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_5').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_6()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_6 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_6').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_7()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_7 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_7').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_8()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_8 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_8').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_9()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_9 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_9').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_10()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_10 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_10').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_11()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_11 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_11').get(0).options.add(newOption);
            }
        }
        function initializeDropdownlistKaizen_month_12()
        {
            let default_kaizen = <?php echo $kaizen_editinline_month_12 ?>;
            var newOption;
            for(var i = 0; i <= 20;i++)
            {
                if(default_kaizen === i)
                {
                    newOption = new Option(i,i,true,true);
                }
                else
                {
                    newOption = new Option(i,i);
                }
                $('#kaizen_month_12').get(0).options.add(newOption);
            }
        }
        //20200120 NTQ MODIFIED
        $('#btnSaveKaizen').click(function(){
            let actual_kaizen_month_id = <?php echo $actual_kaizen_month_id ?>;
            console.log(actual_kaizen_month_id);
            console.log($('#kaizen_month_1').val());
            $.ajax({
                url: "{{route('activityapproval.saveKaizenEditInLine')}}",
                type: "POST",
                data: {
                    actual_kaizen_month_id: actual_kaizen_month_id,
                    kaizen_month_1: $('#kaizen_month_1').val() ? $('#kaizen_month_1').val() : 0,
                    kaizen_month_2: $('#kaizen_month_2').val() ? $('#kaizen_month_2').val() : 0,
                    kaizen_month_3: $('#kaizen_month_3').val() ? $('#kaizen_month_3').val() : 0,
                    kaizen_month_4: $('#kaizen_month_4').val() ? $('#kaizen_month_4').val() : 0,
                    kaizen_month_5: $('#kaizen_month_5').val() ? $('#kaizen_month_5').val() : 0,
                    kaizen_month_6: $('#kaizen_month_6').val() ? $('#kaizen_month_6').val() : 0,
                    kaizen_month_7: $('#kaizen_month_7').val() ? $('#kaizen_month_7').val() : 0,
                    kaizen_month_8: $('#kaizen_month_8').val() ? $('#kaizen_month_8').val() : 0,
                    kaizen_month_9: $('#kaizen_month_9').val() ? $('#kaizen_month_9').val() : 0,
                    kaizen_month_10: $('#kaizen_month_10').val() ? $('#kaizen_month_10').val() : 0,
                    kaizen_month_11: $('#kaizen_month_11').val() ? $('#kaizen_month_11').val() : 0,
                    kaizen_month_12: $('#kaizen_month_12').val() ? $('#kaizen_month_12').val() : 0,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    location.reload();
                },
                error:function(error){
                    alert(error);
                }
            });
        });
        $(".txtKaizen").dblclick(function(){
            $($(this).parent().children([0])).toggle();
            if($('#btnSaveKaizen').css('display') === 'none'){
                $('#btnSaveKaizen').toggle();
            }
        });
    </script>
@endsection
