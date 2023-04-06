@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('view', function ($trail) {
            $trail->parent('toppage');
            $trail->push('事務局年間計画');
        })
    }}
    {{ Breadcrumbs::render('view') }}
@endsection
@section('content')
    <div class="btn-form-controlling" style="width: 100%;">
        <div class="btn-area">
            <a class="btn btn-back" href="{{ URL::to('planbyyear/history') }}">サマリー</a>
            @if($planbyyear->id)
                <a class="btn btn-add" href="{{ URL::to('planbyyear/'.$planbyyear->id.'/edit') }}">編集</a>
            @else
                <a class="btn btn-add" href="{{ URL::to('planbyyear/'.$planbyyear->year.'/create') }}">編集</a>
            @endif
            <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
        </div>
        <div class="btn-arrow">
            <button type="button" class="btn btn-back btn-short" onclick="firstYear();">|<</button>
            <button type="button" class="btn btn-back btn-short" onclick="prevYear();"><</button>
            <span class="datePaginate">
                <span id="currentYear">{{$planbyyear->year}}</span> 年
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
                    <span>{{ isset($planbyyear->id)? \App\Enums\Common::show_id($planbyyear->id) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">{{ __('登録年度') }}</td>
                <td>
                    <span class="line-brake-preserve">{{$planbyyear->year}} 年</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">基本理念</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$planbyyear->vision!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">活動目標</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$planbyyear->target!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">会社方針</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$planbyyear->motto!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">会合回数目標</td>
                <td>
                    <span>{{$planbyyear->meeting_times }} 回／月</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">会合時間目標</td>
                <td>
                    <span>{{$planbyyear->meeting_hour }} ｈ／月</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ﾃｰﾏ完了件数目標</td>
                <td>
                    <span>{{$planbyyear->case_number_complete }} 件／年</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">改善件数目標</td>
                <td>
                    <span>{{$planbyyear->case_number_improve }} 件／年</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">勉強会開催目標</td>
                <td>
                    <span>{{$planbyyear->classes_organizing_objective }} 回／年</span>
                </td>
            </tr>
        </table>
        <div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
            <span class="absolute" style="bottom: 0">月間活動計画</span>
            @if($planbyyear->id)
                <a class="btn btn-add float-right" style="margin-right: -15px"
                   href="{{ URL::to('planbymonth/'.$planbyyear->id.'/create') }}">追加</a>
            @else
                <span class="btn btn-add float-right" disabled
                      style="margin-right: -15px; cursor: not-allowed">追加</span>
            @endif
        </div>
        <table class="table-member-list table-special" id="table-plan-by-month" border="1">
            <thead>
            <tr>
                <th style="width: auto">重点実施事項</th>
                <th style="width: auto">内容</th>
                <th class="plan-year-head">１月</th>
                <th class="plan-year-head">２月</th>
                <th class="plan-year-head">３月</th>
                <th class="plan-year-head">４月</th>
                <th class="plan-year-head">５月</th>
                <th class="plan-year-head">６月</th>
                <th class="plan-year-head">７月</th>
                <th class="plan-year-head">８月</th>
                <th class="plan-year-head">９月</th>
                <th class="plan-year-head">１０月</th>
                <th class="plan-year-head">１１月</th>
                <th class="plan-year-head">１２月</th>
                <th style="width: 110px">担当</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($list_plan_by_month)){
            //code caculator merge row
            $is_start_row = true;
            for($i = 0; $i < count($list_plan_by_month); $i++){
            $item = $list_plan_by_month[$i];
            $row_span = 1;
            if ($i == 0) {
                for ($j = $i + 1; $j < count($list_plan_by_month); $j++) {
                    if ($list_plan_by_month[$j]->execution_order_no == $item->execution_order_no) {
                        $row_span = $row_span + 1;
                    }
                }
            } else {
                if ($item->execution_order_no == $list_plan_by_month[$i - 1]->execution_order_no) {
                    $is_start_row = false;
                } else {
                    $is_start_row = true;
                    for ($j = $i + 1; $j < count($list_plan_by_month); $j++) {
                        if ($list_plan_by_month[$j]->execution_order_no == $item->execution_order_no) {
                            $row_span = $row_span + 1;
                        }
                    }
                }
            }
            ?>
            <tr>
                @if($is_start_row)
                    <td rowspan="{{ $row_span }}">
                        @if($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY1)
                            {{ $planbyyear->prioritize_1 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY2)
                            {{ $planbyyear->prioritize_2 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY3)
                            {{ $planbyyear->prioritize_3 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY4)
                            {{ $planbyyear->prioritize_4 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY5)
                            {{ $planbyyear->prioritize_5 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY6)
                            {{ $planbyyear->prioritize_6 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY7)
                            {{ $planbyyear->prioritize_7 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY8)
                            {{ $planbyyear->prioritize_8 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY9)
                            {{ $planbyyear->prioritize_9 }}
                        @elseif($item->execution_order_no == App\Enums\PrioritizeEnum::PRIORITY10)
                            {{ $planbyyear->prioritize_10 }}
                        @endif
                    </td>
                @endif
                <td><a class="a-black" href="{{ URL::to('planbymonth/'.$item->id.'/edit') }}">{{ $item->contents }}</a>
                </td>
                <td class="cell-special">
                    {{ $item->content_jan }}
                </td>
                <td class="cell-special">
                    {{ $item->content_feb }}
                </td>
                <td class="cell-special">
                    {{ $item->content_mar }}
                </td>
                <td class="cell-special">
                    {{ $item->content_apr }}
                </td>
                <td class="cell-special">
                    {{ $item->content_may }}
                </td>
                <td class="cell-special">
                    {{ $item->content_jun }}
                </td>
                <td class="cell-special">
                    {{ $item->content_jul }}
                </td>
                <td class="cell-special">
                    {{ $item->content_aug }}
                </td>
                <td class="cell-special">
                    {{ $item->content_sep }}
                </td>
                <td class="cell-special">
                    {{ $item->content_oct }}
                </td>
                <td class="cell-special">
                    {{ $item->content_nov }}
                </td>
                <td class="cell-special">
                    {{ $item->content_dec }}
                </td>
                <td class="cell-special"><span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$item->in_charge!!}</span></span></td>
            </tr>
            <script>
                function coor{{$item->id}}() {
                    let table = 'table#table-plan-by-month',
                        startRow = {{ $i+1 }},
                        startColumn = {{ !$is_start_row? $item->month_start : $item->month_start + 1 }},
                        endRow = {{ $i+1  }},
                        endColumn = {{ !$is_start_row? $item->month_end :  $item->month_end + 1 }},
                        color = "black",
                        positionTop = 1.3;
                    var coors = [table, startRow, startColumn, endRow, endColumn, color, positionTop, 1, 1, true, true];
                    return coors;
                }

                $(function () {
                    drawArrowOnTable(coor{{$item->id}}());
                    resizehandle(coor{{$item->id}});
                });
            </script>
            <?php } //endfor ?>
            <?php } //end if ?>
            </tbody>
        </table>
    </div>

    <script>
        function nextYear() {
            var year = parseInt($('#currentYear').text());
            $('#currentYear').text('' + (year + 1) + '')
            location.replace('?year=' + (year + 1));
        }

        function prevYear() {
            var year = parseInt($('#currentYear').text());
            $('#currentYear').text('' + (year - 1) + '')
            location.replace('?year=' + (year - 1));
        }

        function firstYear() {
            var year = parseInt($('#currentYear').text());
            var firstYear = {{ $first_year }};
            if (year != firstYear) {
                location.replace('?year=' + firstYear);
            }
        }

        function lastYear() {
            var year = parseInt($('#currentYear').text());
            var lastYear = {{ $last_year }};
            if (year != lastYear) {
                location.replace('?year=' + lastYear);
            }
        }
    </script>
@endsection
