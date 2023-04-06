@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('テーマ登録',URL::to('theme'));
        }),
        Breadcrumbs::for('show', function ($trail) {
            $trail->parent('list');
            $trail->push('表示');
        })
    }}
    {{ Breadcrumbs::render('show') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(session('circle.id') && !$callback)
            <a class="btn btn-back" href="{{ URL::to('/theme/report/'.$theme->id) }}">報告書</a>
            <a class="btn btn-back" href="{{ URL::to('theme/create?theme_id='.$theme->id) }}">複写</a>
            <a class="btn btn-back" href="{{ URL::to('theme/'.$theme->id.'/edit') }}">修正</a>
            <a class="btn btn-back" id="btn-delete">削除</a>
        @endif
        @if(isset($holdsort) || isset($callback))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ URL::to('theme') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid bottom-fix" id="77777">
        <table class="table-form" border="1">
            <tr>
                <td class="td-fixed">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ App\Enums\Common::show_id($theme->id) }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-fixed">サークル名</td>
                <td>
                    <span>{{ $circle->circle_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-fixed">テーマ名</td>
                <td>
                    <span class="font-weight-bold">{{$theme->theme_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-fixed">特性値</td>
                <td>
                    <span>{{$theme->value_property }}</span>
                </td>
            </tr>

            <tr>
                <td class="td-fixed">目標値</td>
                <td>
                    <span>{{$theme->value_objective }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-fixed">開始予定日</td>
                <td>
                    <span>{{ isset($theme->date_start)? date('Y/m/d', strtotime($theme->date_start)) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-fixed">完了予定日</td>
                <td>
                    <span>{{ isset($theme->date_expected_completion)? date('Y/m/d', strtotime($theme->date_expected_completion)) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-fixed">完了実績日</td>
                <td>
                    <span>{{ isset($theme->date_actual_completion)? date('Y/m/d', strtotime($theme->date_actual_completion)) : null }}</span>
                </td>
            </tr>

            <tr>
                <td class="td-fixed">備考</td>
                <td>
                    <span class="line-brake-preserve">{{$theme->note}}</span>
                </td>
            </tr>

        </table>
        <div style="margin:5px 0px;">
            <span>進捗状況</span>　　　　　<span id="blue-arrow-legend">計画</span>　　<span id="red-arrow-legend">実績</span>
        </div>
        <?php
        $numberColumFirst = 1;
        $isEndPoint1 = true;
        $isEndPoint2 = true;
        $isEndPoint3 = true;
        $isEndPoint4 = true;
        $isEndPoint5 = true;
        $isEndPoint6 = true;
        $isEndPoint7 = true;
        $isStartPoint1 = true;
        $isStartPoint2 = true;
        $isStartPoint3 = true;
        $isStartPoint4 = true;
        $isStartPoint5 = true;
        $isStartPoint6 = true;
        $isStartPoint7 = true;
        $isDrawPlan1 = false;
        $isDrawPlan2 = false;
        $isDrawPlan3 = false;
        $isDrawPlan4 = false;
        $isDrawPlan5 = false;
        $isDrawPlan6 = false;
        $isDrawPlan7 = false;
        $isDrawReal1 = false;
        $isDrawReal2 = false;
        $isDrawReal3 = false;
        $isDrawReal4 = false;
        $isDrawReal5 = false;
        $isDrawReal6 = false;
        $isDrawReal7 = false;
        ?>

        <table class="table-form-BG" id="table-promotion-theme" border="1" style="width: 100%">
            <tr class="text-center">
                <td class="background-c td-fixed">進捗区分</td>
                @if($number_month > 0)
                    @if($number_year < 1)
                        @for($i = $start_month; $i < ($start_month + $number_month); $i++)
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y').$i }}">
                                <span class="d-block year-on-top">@if($i == $start_month) {{ $date_expected_start->format('Y') }} @endif</span>
                                <span class="d-block month-on-bottom">{{$i}}月</span>
                            </td>
                        @endfor
                    @elseif($number_year == 1)
                        @for($i = $start_month; $i <= 12; $i++)
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y').$i }}">
                                <span class="d-block  year-on-top">@if($i == $start_month) {{ $date_expected_start->format('Y') }} @endif</span>
                                <span class="d-block month-on-bottom">{{$i}}月</span>
                            </td>
                        @endfor
                        @for($i = 1; $i <= $finish_month; $i++)
                            <td class="background-c theme-head col-fixed"
                                id="{{ ((int)$date_expected_start->format('Y') + 1).$i }}">
                                <span class="d-block  year-on-top">@if($i == 1) {{ (int)$date_expected_start->format('Y') + 1 }} @endif</span>
                                <span class="d-block month-on-bottom">{{$i}}月</span>
                            </td>
                        @endfor
                    @else
                        @for($i = $start_month; $i <= 12; $i++)
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y').$i }}">
                                <span class="d-block  year-on-top">@if($i == $start_month) {{ $date_expected_start->format('Y') }} @endif</span>
                                <span class="d-block month-on-bottom">{{$i}}月</span>
                            </td>
                        @endfor
                        @for($i = 0; $i < ($number_year - 1); $i++)
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}1">
                                <span class="d-block  year-on-top">{{ $date_expected_start->format('Y') + $i + 1 }}</span>
                                <span class="d-block month-on-bottom">1月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}2">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">2月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}3">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">3月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}4">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">4月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}5">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">5月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}6">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">6月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}7">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">7月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}8">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">8月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}9">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">9月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}10">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">10月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}11">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">11月</span>
                            </td>
                            <td class="background-c theme-head col-fixed"
                                id="{{ $date_expected_start->format('Y') + $i + 1}}12">
                                <span class="d-block  year-on-top"></span>
                                <span class="d-block month-on-bottom">12月</span>
                            </td>
                        @endfor
                        @for($i = 1; $i <= $finish_month; $i++)
                            <td class="background-c theme-head col-fixed"
                                id="{{ ($date_expected_start->format('Y') + $number_year).$i }}">
                                <span class="d-block  year-on-top">@if($i == 1) {{ $date_expected_start->format('Y') + $number_year }} @endif</span>
                                <span class="d-block month-on-bottom">{{$i}}月</span>
                            </td>
                        @endfor
                    @endif
                @endif
                <td class="background-c" style="min-width: 150px">備考</td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_1)? URL::to('/promotion-theme/'.$promotion_theme_1->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/1/create') }}">1.テーマ選定</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_1))
                        <?php
                        $isDrawPlan1 = true;
                        $date_expected_start_1 = new DateTime($promotion_theme_1->date_expected_start);
                        $start_expected_year_1 = (int)$date_expected_start_1->format('Y');
                        $start_expected_month_1 = (int)$date_expected_start_1->format('m');
                        $start_expected_day_1 = (int)$date_expected_start_1->format('d');

                        $date_expected_completion_1 = new DateTime($promotion_theme_1->date_expected_completion);
                        $finish_expected_year_1 = (int)$date_expected_completion_1->format('Y');
                        $finish_expected_month_1 = (int)$date_expected_completion_1->format('m');
                        $finish_expected_day_1 = (int)$date_expected_completion_1->format('d');
                        ?>
                        <script>
                            var row1 = 1;

                            function coorPlan1() {
                                let indexStartP = {{$start_expected_year_1.$start_expected_month_1}},
                                    indexEndP = {{$finish_expected_year_1.$finish_expected_month_1}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row1,
                                    planStartColumn = findColumnPosition(indexStartP);
                                let startPeriodPlan = getPeriod({{$start_expected_day_1}});

                                let planEndRow = row1,
                                    planEndColumn = findColumnPosition(indexEndP);
                                let endPeriodPlan = getPeriod({{$finish_expected_day_1}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;

                                let coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriodPlan, endPeriodPlan, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_1->date_actual_start) && isset($promotion_theme_1->date_actual_completion))
                            <?php
                            $isDrawReal1 = true;
                            $date_actual_start_1 = new DateTime($promotion_theme_1->date_actual_start);
                            $start_actual_year_1 = (int)$date_actual_start_1->format('Y');
                            $start_actual_month_1 = (int)$date_actual_start_1->format('m');
                            $start_actual_day_1 = (int)$date_actual_start_1->format('d');

                            $date_actual_start_1 = new DateTime($promotion_theme_1->date_actual_start);
                            $start_actual_year_1 = (int)$date_actual_start_1->format('Y');
                            $start_actual_month_1 = (int)$date_actual_start_1->format('m');
                            $start_actual_day_1 = (int)$date_actual_start_1->format('d');

                            $date_actual_completion_1 = new DateTime($promotion_theme_1->date_actual_completion);
                            $finish_actual_year_1 = (int)$date_actual_completion_1->format('Y');
                            $finish_actual_month_1 = (int)$date_actual_completion_1->format('m');
                            $finish_actual_day_1 = (int)$date_actual_completion_1->format('d');

                            $start_date_compare_1 = new DateTime($start_actual_year_1 . '-' . $start_actual_month_1);
                            $finish_date_compare_1 = new DateTime($finish_actual_year_1 . '-' . $finish_actual_month_1);
                            if ($start_date_compare_1 < $date_compare_start) {
                                $isStartPoint1 = false;
                            }
                            if ($finish_date_compare_1 > $date_compare_finish) {
                                $isEndPoint1 = false;
                            }
                            ?>
                            <script>
                                function coorReal1() {
                                    let indexStartR = {{$start_actual_year_1.$start_actual_month_1}},
                                        indexEndR = {{$finish_actual_year_1.$finish_actual_month_1}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = row1,
                                        realStartColumn = findColumnPositionReal(indexStartR, true);
                                    let startPeriodReal = getPeriod({{$start_actual_day_1}});

                                    let realEndRow = row1,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let endPeriodReal = getPeriod({{$finish_actual_day_1}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint1)
                                        startPeriodReal = -1;
                                    hasStartPoind = false;
                                    @endif

                                            @if(!$isEndPoint1)
                                        endPeriodReal = -1;
                                    hasEndPoind = false;
                                            @endif
                                    let coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriodReal, endPeriodReal, hasEndPoind, hasStartPoind];
                                    return coors;
                                }


                            </script>
                        @endif
                    @endif
                @endif
                <td><span>{{ isset($promotion_theme_1)? ($promotion_theme_1->note) : null }}</span> </td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_2)? URL::to('/promotion-theme/'.$promotion_theme_2->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/2/create') }}">2.現状把握と目標設定</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_2))
                        <?php
                        $isDrawPlan2 = true;
                        $date_expected_start_2 = new DateTime($promotion_theme_2->date_expected_start);
                        $start_expected_year_2 = (int)$date_expected_start_2->format('Y');
                        $start_expected_month_2 = (int)$date_expected_start_2->format('m');
                        $start_expected_day_2 = (int)$date_expected_start_2->format('d');

                        $date_expected_completion_2 = new DateTime($promotion_theme_2->date_expected_completion);
                        $finish_expected_year_2 = (int)$date_expected_completion_2->format('Y');
                        $finish_expected_month_2 = (int)$date_expected_completion_2->format('m');
                        $finish_expected_day_2 = (int)$date_expected_completion_2->format('d');
                        ?>
                        <script>
                            var row2 = 2;

                            function coorPlan2() {
                                let indexStartP = {{$start_expected_year_2.$start_expected_month_2}},
                                    indexEndP = {{$finish_expected_year_2.$finish_expected_month_2}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row2,
                                    planStartColumn = findColumnPosition(indexStartP),
                                    planEndRow = row2,
                                    planEndColumn = findColumnPosition(indexEndP);
                                let planStartPeriod = getPeriod({{$start_expected_day_2}});
                                let planEndPeriod = getPeriod({{$finish_expected_day_2}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;
                                let coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, planStartPeriod, planEndPeriod, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_2->date_actual_start) && isset($promotion_theme_2->date_actual_completion))
                            <?php
                            $isDrawReal2 = true;
                            $date_actual_start_2 = new DateTime($promotion_theme_2->date_actual_start);
                            $start_actual_year_2 = (int)$date_actual_start_2->format('Y');
                            $start_actual_month_2 = (int)$date_actual_start_2->format('m');
                            $start_actual_day_2 = (int)$date_actual_start_2->format('d');

                            $date_actual_start_2 = new DateTime($promotion_theme_2->date_actual_start);
                            $start_actual_year_2 = (int)$date_actual_start_2->format('Y');
                            $start_actual_month_2 = (int)$date_actual_start_2->format('m');
                            $start_actual_day_2 = (int)$date_actual_start_2->format('d');

                            $date_actual_completion_2 = new DateTime($promotion_theme_2->date_actual_completion);
                            $finish_actual_year_2 = (int)$date_actual_completion_2->format('Y');
                            $finish_actual_month_2 = (int)$date_actual_completion_2->format('m');
                            $finish_actual_day_2 = (int)$date_actual_completion_2->format('d');

                            $start_date_compare_2 = new DateTime($start_actual_year_2 . '-' . $start_actual_month_2);
                            $finish_date_compare_2 = new DateTime($finish_actual_year_2 . '-' . $finish_actual_month_2);
                            if ($start_date_compare_2 < $date_compare_start) {
                                $isStartPoint2 = false;
                            }
                            if ($finish_date_compare_2 > $date_compare_finish) {
                                $isEndPoint2 = false;
                            }
                            ?>
                            <script>
                                function coorReal2() {
                                    let indexStartR = {{$start_actual_year_2.$start_actual_month_2}},
                                        indexEndR = {{$finish_actual_year_2.$finish_actual_month_2}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = row2,
                                        realStartColumn = findColumnPositionReal(indexStartR, true),
                                        realEndRow = row2,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let realStartPeriod = getPeriod({{$start_actual_day_2}});
                                    let realEndPeriod = getPeriod({{$finish_actual_day_2}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint2)
                                        realStartPeriod = -1;
                                    hasStartPoind = false;
                                    @endif
                                            @if(!$isEndPoint2)
                                        realEndPeriod = -1;
                                    hasEndPoind = false;
                                            @endif
                                    let coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, realStartPeriod, realEndPeriod, hasEndPoind, hasStartPoind];
                                    return coors;
                                }
                            </script>
                        @endif
                    @endif
                @endif
                <td><span class="line-brake-preserve">{{ isset($promotion_theme_2)? ($promotion_theme_2->note) : null }}</span></td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_3)? URL::to('/promotion-theme/'.$promotion_theme_3->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/3/create') }}">3.活動計画の作成</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_3))
                        <?php
                        $isDrawPlan3 = true;
                        $date_expected_start_3 = new DateTime($promotion_theme_3->date_expected_start);
                        $start_expected_year_3 = (int)$date_expected_start_3->format('Y');
                        $start_expected_month_3 = (int)$date_expected_start_3->format('m');
                        $start_expected_day_3 = (int)$date_expected_start_3->format('d');

                        $date_expected_completion_3 = new DateTime($promotion_theme_3->date_expected_completion);
                        $finish_expected_year_3 = (int)$date_expected_completion_3->format('Y');
                        $finish_expected_month_3 = (int)$date_expected_completion_3->format('m');
                        $finish_expected_day_3 = (int)$date_expected_completion_3->format('d');
                        ?>
                        <script>
                            var row3 = 3;

                            function coorPlan3() {
                                let indexStartR = {{$start_expected_year_3.$start_expected_month_3}},
                                    indexEndR = {{$finish_expected_year_3.$finish_expected_month_3}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row3,
                                    planStartColumn = findColumnPosition(indexStartR),
                                    planEndRow = row3,
                                    planEndColumn = findColumnPosition(indexEndR);
                                let startPeriod = getPeriod({{$start_expected_day_3}});
                                let endPeriod = getPeriod({{$finish_expected_day_3}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;
                                let coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_3->date_actual_start) && isset($promotion_theme_3->date_actual_completion))
                            <?php
                            $isDrawReal3 = true;
                            $date_actual_start_3 = new DateTime($promotion_theme_3->date_actual_start);
                            $start_actual_year_3 = (int)$date_actual_start_3->format('Y');
                            $start_actual_month_3 = (int)$date_actual_start_3->format('m');
                            $start_actual_day_3 = (int)$date_actual_start_3->format('d');

                            $date_actual_start_3 = new DateTime($promotion_theme_3->date_actual_start);
                            $start_actual_year_3 = (int)$date_actual_start_3->format('Y');
                            $start_actual_month_3 = (int)$date_actual_start_3->format('m');
                            $start_actual_day_3 = (int)$date_actual_start_3->format('d');

                            $date_actual_completion_3 = new DateTime($promotion_theme_3->date_actual_completion);
                            $finish_actual_year_3 = (int)$date_actual_completion_3->format('Y');
                            $finish_actual_month_3 = (int)$date_actual_completion_3->format('m');
                            $finish_actual_day_3 = (int)$date_actual_completion_3->format('d');

                            $start_date_compare_3 = new DateTime($start_actual_year_3 . '-' . $start_actual_month_3);
                            $finish_date_compare_3 = new DateTime($finish_actual_year_3 . '-' . $finish_actual_month_3);
                            if ($start_date_compare_3 < $date_compare_start) {
                                $isStartPoint3 = false;
                            }
                            if ($finish_date_compare_3 > $date_compare_finish) {
                                $isEndPoint3 = false;
                            }
                            ?>
                            <script>
                                function coorReal3() {
                                    let indexStartR = {{$start_actual_year_3.$start_actual_month_3}},
                                        indexEndR = {{$finish_actual_year_3.$finish_actual_month_3}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = row3,
                                        realStartColumn = findColumnPositionReal(indexStartR, true),
                                        realEndRow = row3,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let startPeriod = getPeriod({{$start_actual_day_3}});
                                    let endPeriod = getPeriod({{$finish_actual_day_3}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint3)
                                        startPeriod = -1;
                                    hasStartPoind = false;
                                    @endif
                                            @if(!$isEndPoint3)
                                        endPeriod = -1;
                                    hasEndPoind = false;
                                            @endif
                                    let coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                    return coors;
                                }
                            </script>
                        @endif
                    @endif
                @endif
                <td><span class="line-brake-preserve">{{ isset($promotion_theme_3)? ($promotion_theme_3->note) : null }}</span></td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_4)? URL::to('/promotion-theme/'.$promotion_theme_4->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/4/create') }}">4.要因解析</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_4))
                        <?php
                        $isDrawPlan4 = true;
                        $date_expected_start_4 = new DateTime($promotion_theme_4->date_expected_start);
                        $start_expected_year_4 = (int)$date_expected_start_4->format('Y');
                        $start_expected_month_4 = (int)$date_expected_start_4->format('m');
                        $start_expected_day_4 = (int)$date_expected_start_4->format('d');

                        $date_expected_completion_4 = new DateTime($promotion_theme_4->date_expected_completion);
                        $finish_expected_year_4 = (int)$date_expected_completion_4->format('Y');
                        $finish_expected_month_4 = (int)$date_expected_completion_4->format('m');
                        $finish_expected_day_4 = (int)$date_expected_completion_4->format('d');
                        ?>
                        <script>
                            var row4 = 4;

                            function coorPlan4() {
                                let indexStartR = {{$start_expected_year_4.$start_expected_month_4}},
                                    indexEndR = {{$finish_expected_year_4.$finish_expected_month_4}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row4,
                                    planStartColumn = findColumnPosition(indexStartR),
                                    planEndRow = row4,
                                    planEndColumn = findColumnPosition(indexEndR);
                                let startPeriod = getPeriod({{$start_expected_day_4}});
                                let endPeriod = getPeriod({{$finish_expected_day_4}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;
                                var coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_4->date_actual_start) && isset($promotion_theme_4->date_actual_completion))
                            <?php
                            $isDrawReal4 = true;
                            $date_actual_start_4 = new DateTime($promotion_theme_4->date_actual_start);
                            $start_actual_year_4 = (int)$date_actual_start_4->format('Y');
                            $start_actual_month_4 = (int)$date_actual_start_4->format('m');
                            $start_actual_day_4 = (int)$date_actual_start_4->format('d');

                            $date_actual_start_4 = new DateTime($promotion_theme_4->date_actual_start);
                            $start_actual_year_4 = (int)$date_actual_start_4->format('Y');
                            $start_actual_month_4 = (int)$date_actual_start_4->format('m');
                            $start_actual_day_4 = (int)$date_actual_start_4->format('d');

                            $date_actual_completion_4 = new DateTime($promotion_theme_4->date_actual_completion);
                            $finish_actual_year_4 = (int)$date_actual_completion_4->format('Y');
                            $finish_actual_month_4 = (int)$date_actual_completion_4->format('m');
                            $finish_actual_day_4 = (int)$date_actual_completion_4->format('d');

                            $start_date_compare_4 = new DateTime($start_actual_year_4 . '-' . $start_actual_month_4);
                            $finish_date_compare_4 = new DateTime($finish_actual_year_4 . '-' . $finish_actual_month_4);
                            if ($start_date_compare_4 < $date_compare_start) {
                                $isStartPoint4 = false;
                            }
                            if ($finish_date_compare_4 > $date_compare_finish) {
                                $isEndPoint4 = false;
                            }
                            ?>
                            <script>
                                function coorReal4() {
                                    let indexStartR = {{$start_actual_year_4.$start_actual_month_4}},
                                        indexEndR = {{$finish_actual_year_4.$finish_actual_month_4}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = 4,
                                        realStartColumn = findColumnPositionReal(indexStartR, true),
                                        realEndRow = 4,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let startPeriod = getPeriod({{$start_actual_day_4}});
                                    let endPeriod = getPeriod({{$finish_actual_day_4}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint4)
                                        startPeriod = -1;
                                    hasStartPoind = false;
                                    @endif
                                            @if(!$isEndPoint4)
                                        endPeriod = -1;
                                    hasEndPoind = false;
                                            @endif
                                    var coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                    return coors;
                                }
                            </script>
                        @endif
                    @endif
                @endif
                <td><span class="line-brake-preserve">{{ isset($promotion_theme_4)? ($promotion_theme_4->note) : null }}</span></td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_5)? URL::to('/promotion-theme/'.$promotion_theme_5->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/5/create') }}">5.対策検討と実施</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_5))
                        <?php
                        $isDrawPlan5 = true;
                        $date_expected_start_5 = new DateTime($promotion_theme_5->date_expected_start);
                        $start_expected_year_5 = (int)$date_expected_start_5->format('Y');
                        $start_expected_month_5 = (int)$date_expected_start_5->format('m');
                        $start_expected_day_5 = (int)$date_expected_start_5->format('d');

                        $date_expected_completion_5 = new DateTime($promotion_theme_5->date_expected_completion);
                        $finish_expected_year_5 = (int)$date_expected_completion_5->format('Y');
                        $finish_expected_month_5 = (int)$date_expected_completion_5->format('m');
                        $finish_expected_day_5 = (int)$date_expected_completion_5->format('d');
                        ?>
                        <script>
                            var row5 = 5;

                            function coorPlan5() {
                                let indexStartR = {{$start_expected_year_5.$start_expected_month_5}},
                                    indexEndR = {{$finish_expected_year_5.$finish_expected_month_5}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row5,
                                    planStartColumn = findColumnPosition(indexStartR),
                                    planEndRow = row5,
                                    planEndColumn = findColumnPosition(indexEndR);
                                let startPeriod = getPeriod({{$start_expected_day_5}});
                                let endPeriod = getPeriod({{$finish_expected_day_5}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;
                                var coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_5->date_actual_start) && isset($promotion_theme_5->date_actual_completion))
                            <?php
                            $isDrawReal5 = true;
                            $date_actual_start_5 = new DateTime($promotion_theme_5->date_actual_start);
                            $start_actual_year_5 = (int)$date_actual_start_5->format('Y');
                            $start_actual_month_5 = (int)$date_actual_start_5->format('m');
                            $start_actual_day_5 = (int)$date_actual_start_5->format('d');

                            $date_actual_start_5 = new DateTime($promotion_theme_5->date_actual_start);
                            $start_actual_year_5 = (int)$date_actual_start_5->format('Y');
                            $start_actual_month_5 = (int)$date_actual_start_5->format('m');
                            $start_actual_day_5 = (int)$date_actual_start_5->format('d');

                            $date_actual_completion_5 = new DateTime($promotion_theme_5->date_actual_completion);
                            $finish_actual_year_5 = (int)$date_actual_completion_5->format('Y');
                            $finish_actual_month_5 = (int)$date_actual_completion_5->format('m');
                            $finish_actual_day_5 = (int)$date_actual_completion_5->format('d');

                            $start_date_compare_5 = new DateTime($start_actual_year_5 . '-' . $start_actual_month_5);
                            $finish_date_compare_5 = new DateTime($finish_actual_year_5 . '-' . $finish_actual_month_5);
                            if ($start_date_compare_5 < $date_compare_start) {
                                $isStartPoint5 = false;
                            }
                            if ($finish_date_compare_5 > $date_compare_finish) {
                                $isEndPoint5 = false;
                            }
                            ?>
                            <script>
                                function coorReal5() {
                                    let indexStartR = {{$start_actual_year_5.$start_actual_month_5}},
                                        indexEndR = {{$finish_actual_year_5.$finish_actual_month_5}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = row5,
                                        realStartColumn = findColumnPositionReal(indexStartR, true),
                                        realEndRow = row5,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let startPeriod = getPeriod({{$start_actual_day_5}});
                                    let endPeriod = getPeriod({{$finish_actual_day_5}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint5)
                                        startPeriod = -1;
                                    hasStartPoind = false;
                                    @endif
                                            @if(!$isEndPoint5)
                                        endPeriod = -1;
                                    hasEndPoind = false;
                                            @endif
                                    var coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                    return coors;
                                }
                            </script>
                        @endif
                    @endif
                @endif
                <td><span class="line-brake-preserve">{{ isset($promotion_theme_5)? ($promotion_theme_5->note) : null }}</span></td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_6)? URL::to('/promotion-theme/'.$promotion_theme_6->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/6/create') }}">6.効果確認</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_6))
                        <?php
                        $isDrawPlan6 = true;
                        $date_expected_start_6 = new DateTime($promotion_theme_6->date_expected_start);
                        $start_expected_year_6 = (int)$date_expected_start_6->format('Y');
                        $start_expected_month_6 = (int)$date_expected_start_6->format('m');
                        $start_expected_day_6 = (int)$date_expected_start_6->format('d');

                        $date_expected_completion_6 = new DateTime($promotion_theme_6->date_expected_completion);
                        $finish_expected_year_6 = (int)$date_expected_completion_6->format('Y');
                        $finish_expected_month_6 = (int)$date_expected_completion_6->format('m');
                        $finish_expected_day_6 = (int)$date_expected_completion_6->format('d');
                        ?>
                        <script>
                            var row6 = 6;

                            function coorPlan6() {
                                let indexStartR = {{$start_expected_year_6.$start_expected_month_6}},
                                    indexEndR = {{$finish_expected_year_6.$finish_expected_month_6}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row6,
                                    planStartColumn = findColumnPosition(indexStartR),
                                    planEndRow = row6,
                                    planEndColumn = findColumnPosition(indexEndR);
                                let startPeriod = getPeriod({{$start_expected_day_6}});
                                let endPeriod = getPeriod({{$finish_expected_day_6}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;
                                var coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_6->date_actual_start) && isset($promotion_theme_6->date_actual_completion))
                            <?php
                            $isDrawReal6 = true;
                            $date_actual_start_6 = new DateTime($promotion_theme_6->date_actual_start);
                            $start_actual_year_6 = (int)$date_actual_start_6->format('Y');
                            $start_actual_month_6 = (int)$date_actual_start_6->format('m');
                            $start_actual_day_6 = (int)$date_actual_start_6->format('d');

                            $date_actual_start_6 = new DateTime($promotion_theme_6->date_actual_start);
                            $start_actual_year_6 = (int)$date_actual_start_6->format('Y');
                            $start_actual_month_6 = (int)$date_actual_start_6->format('m');
                            $start_actual_day_6 = (int)$date_actual_start_6->format('d');

                            $date_actual_completion_6 = new DateTime($promotion_theme_6->date_actual_completion);
                            $finish_actual_year_6 = (int)$date_actual_completion_6->format('Y');
                            $finish_actual_month_6 = (int)$date_actual_completion_6->format('m');
                            $finish_actual_day_6 = (int)$date_actual_completion_6->format('d');

                            $start_date_compare_6 = new DateTime($start_actual_year_6 . '-' . $start_actual_month_6);
                            $finish_date_compare_6 = new DateTime($finish_actual_year_6 . '-' . $finish_actual_month_6);
                            if ($start_date_compare_6 < $date_compare_start) {
                                $isStartPoint6 = false;
                            }
                            if ($finish_date_compare_6 > $date_compare_finish) {
                                $isEndPoint6 = false;
                            }
                            ?>
                            <script>
                                function coorReal6() {
                                    let indexStartR = {{$start_actual_year_6.$start_actual_month_6}},
                                        indexEndR = {{$finish_actual_year_6.$finish_actual_month_6}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = row6,
                                        realStartColumn = findColumnPositionReal(indexStartR, true),
                                        realEndRow = row6,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let startPeriod = getPeriod({{$start_actual_day_6}});
                                    let endPeriod = getPeriod({{$finish_actual_day_6}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint6)
                                        startPeriod = -1;
                                    hasStartPoind = false;
                                    @endif
                                            @if(!$isEndPoint6)
                                        endPeriod = -1;
                                    hasEndPoind = false;
                                            @endif
                                    var coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                    return coors;
                                }
                            </script>
                        @endif
                    @endif
                @endif
                <td><span class="line-brake-preserve">{{ isset($promotion_theme_6)? ($promotion_theme_6->note) : null }}</span></td>
            </tr>
            <tr>
                <td class="background-c"><a class="a-black"
                                            href="{{ isset($promotion_theme_7)? URL::to('/promotion-theme/'.$promotion_theme_7->id.'/edit') : URL::to('/promotion-theme/'.$theme->id.'/7/create') }}">7.標準化と管理の定着</a>
                </td>
                @if($number_month > 0)
                    @for($i = 0; $i < $number_month; $i++)
                        <td></td>
                    @endfor
                    @if(isset($promotion_theme_7))
                        <?php
                        $isDrawPlan7 = true;
                        $date_expected_start_7 = new DateTime($promotion_theme_7->date_expected_start);
                        $start_expected_year_7 = (int)$date_expected_start_7->format('Y');
                        $start_expected_month_7 = (int)$date_expected_start_7->format('m');
                        $start_expected_day_7 = (int)$date_expected_start_7->format('d');

                        $date_expected_completion_7 = new DateTime($promotion_theme_7->date_expected_completion);
                        $finish_expected_year_7 = (int)$date_expected_completion_7->format('Y');
                        $finish_expected_month_7 = (int)$date_expected_completion_7->format('m');
                        $finish_expected_day_7 = (int)$date_expected_completion_7->format('d');
                        ?>
                        <script>
                            var row7 = 7;

                            function coorPlan7() {
                                let indexStartR = {{$start_expected_year_7.$start_expected_month_7}},
                                    indexEndR = {{$finish_expected_year_7.$finish_expected_month_7}};
                                let table = '#table-promotion-theme',
                                    color = 'blue',
                                    positionTop = 3,
                                    planStartRow = row7,
                                    planStartColumn = findColumnPosition(indexStartR),
                                    planEndRow = row7,
                                    planEndColumn = findColumnPosition(indexEndR);
                                let startPeriod = getPeriod({{$start_expected_day_7}});
                                let endPeriod = getPeriod({{$finish_expected_day_7}});
                                let hasEndPoind = true;
                                let hasStartPoind = true;
                                var coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                return coors;
                            }
                        </script>
                        @if(isset($promotion_theme_7->date_actual_start) && isset($promotion_theme_7->date_actual_completion))
                            <?php
                            $isDrawReal7 = true;
                            $date_actual_start_7 = new DateTime($promotion_theme_7->date_actual_start);
                            $start_actual_year_7 = (int)$date_actual_start_7->format('Y');
                            $start_actual_month_7 = (int)$date_actual_start_7->format('m');
                            $start_actual_day_7 = (int)$date_actual_start_7->format('d');

                            $date_actual_start_7 = new DateTime($promotion_theme_7->date_actual_start);
                            $start_actual_year_7 = (int)$date_actual_start_7->format('Y');
                            $start_actual_month_7 = (int)$date_actual_start_7->format('m');
                            $start_actual_day_7 = (int)$date_actual_start_7->format('d');

                            $date_actual_completion_7 = new DateTime($promotion_theme_7->date_actual_completion);
                            $finish_actual_year_7 = (int)$date_actual_completion_7->format('Y');
                            $finish_actual_month_7 = (int)$date_actual_completion_7->format('m');
                            $finish_actual_day_7 = (int)$date_actual_completion_7->format('d');

                            $start_date_compare_7 = new DateTime($start_actual_year_7 . '-' . $start_actual_month_7);
                            $finish_date_compare_7 = new DateTime($finish_actual_year_7 . '-' . $finish_actual_month_7);
                            if ($start_date_compare_7 < $date_compare_start) {
                                $isStartPoint7 = false;
                            }
                            if ($finish_date_compare_7 > $date_compare_finish) {
                                $isEndPoint7 = false;
                            }
                            ?>
                            <script>
                                function coorReal7() {
                                    let indexStartR = {{$start_actual_year_7.$start_actual_month_7}},
                                        indexEndR = {{$finish_actual_year_7.$finish_actual_month_7}};
                                    let table = '#table-promotion-theme',
                                        color = 'red',
                                        positionTop = 1.3,
                                        realStartRow = row7,
                                        realStartColumn = findColumnPositionReal(indexStartR, true),
                                        realEndRow = row7,
                                        realEndColumn = findColumnPositionReal(indexEndR, false);
                                    let startPeriod = getPeriod({{$start_actual_day_7}});
                                    let endPeriod = getPeriod({{$finish_actual_day_7}});
                                    let hasEndPoind = true;
                                    let hasStartPoind = true;
                                    @if(!$isStartPoint7)
                                        startPeriod = -1;
                                    hasStartPoind = false;
                                    @endif
                                            @if(!$isEndPoint7)
                                        endPeriod = -1;
                                    hasEndPoind = false;
                                            @endif
                                    var coors = [table, realStartRow, realStartColumn, realEndRow, realEndColumn, color, positionTop, startPeriod, endPeriod, hasEndPoind, hasStartPoind];
                                    return coors;
                                }
                            </script>
                        @endif
                    @endif
                @endif
                <td><span class="line-brake-preserve">{{ isset($promotion_theme_7)? ($promotion_theme_7->note) : null }}</span></td>
            </tr>
        </table>
        <div style="margin:5px 0px;">
            実績
        </div>
        <table class="table-form-BG" border="1">
            <tr class="text-center">
                <td class="background-c td-fixed">活動区分</td>
                <td class="background-c col-fixed">会合</td>
                <td class="background-c col-fixed">勉強会</td>
                <td class="background-c col-fixed">改善提案</td>
                <td class="background-c col-fixed">その他</td>
                <td class="background-c col-fixed">合計</td>
            </tr>
            <tr>
                <td class="background-c">回数（回）</td>
                <td class="text-center">{{ $meeting_times }}</td>
                <td class="text-center">{{ $study_times }}</td>
                <td class="text-center">{{ $kaizen_times }}</td>
                <td class="text-center">{{ $other_times }}</td>
                <td class="text-center">{{ ($meeting_times + $study_times + $kaizen_times + $other_times) }}</td>
            </tr>
            <tr>
                <td class="background-c">時間（ｈ）</td>
                <td class="text-center">{{ number_format($meeting_total_time, 2) }}</td>
                <td class="text-center">{{ number_format($study_total_time, 2) }}</td>
                <td class="text-center">{{ number_format($kaizen_total_time, 2) }}</td>
                <td class="text-center">{{ number_format($other_total_time, 2) }}</td>
                <td class="text-center">{{ number_format(($meeting_total_time + $study_total_time + $kaizen_total_time + $other_total_time), 2) }}</td>
            </tr>
        </table>


    </div>
    {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['theme.destroy', $theme->id]]) !!}
    {!! Form::close() !!}

    <script>
        @if ($errors->has('delete_using'))
        alert('{{ $errors->first('delete_using')}}');
        @endif
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Theme }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });

        function getPeriod(day) {
            return Math.floor(day / 10);
        }

        @if($isDrawPlan1)
        drawArrowOnTable(coorPlan1());
        resizehandle(coorPlan1);
        @endif
        @if($isDrawReal1)
        drawArrowOnTable(coorReal1());
        resizehandle(coorReal1);
        @endif

        @if($isDrawPlan2)
        drawArrowOnTable(coorPlan2());
        resizehandle(coorPlan2);
        @endif
        @if($isDrawReal2)
        drawArrowOnTable(coorReal2());
        resizehandle(coorReal2);
        @endif

        @if($isDrawPlan3)
        drawArrowOnTable(coorPlan3());
        resizehandle(coorPlan3);
        @endif
        @if($isDrawReal3)
        drawArrowOnTable(coorReal3());
        resizehandle(coorReal3);
        @endif

        @if($isDrawPlan4)
        drawArrowOnTable(coorPlan4());
        resizehandle(coorPlan4);
        @endif
        @if($isDrawReal4)
        drawArrowOnTable(coorReal4());
        resizehandle(coorReal4);
        @endif

        @if($isDrawPlan5)
        drawArrowOnTable(coorPlan5());
        resizehandle(coorPlan5);
        @endif
        @if($isDrawReal5)
        drawArrowOnTable(coorReal5());
        resizehandle(coorReal5);
        @endif

        @if($isDrawPlan6)
        drawArrowOnTable(coorPlan6());
        resizehandle(coorPlan6);
        @endif
        @if($isDrawReal6)
        drawArrowOnTable(coorReal6());
        resizehandle(coorReal6);
        @endif

        @if($isDrawPlan7)
        drawArrowOnTable(coorPlan7());
        resizehandle(coorPlan7);
        @endif
        @if($isDrawReal7)
        drawArrowOnTable(coorReal7());
        resizehandle(coorReal7);
        @endif
    </script>
@endsection
