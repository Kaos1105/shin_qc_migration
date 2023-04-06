@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('view', function ($trail) {
            $trail->parent('toppage');
            $trail->push('サークル推進計画');
        })
    }}

    {{ Breadcrumbs::render('view') }}
@endsection
@section('content')
    <div class="btn-form-controlling" style="width: 100%;">
        <div class="btn-area">
            <a class="btn btn-back" href="{{ URL::to('promotion-circle/history') }}">サマリー</a>
            @if(session('circle.id'))
                @if($promotion_cirlce->id)
                    <a class="btn btn-add"
                       href="{{ URL::to('promotion-circle/'.$promotion_cirlce->id.'/edit') }}">編集</a>
                @else
                    <a class="btn btn-add"
                       href="{{ URL::to('promotion-circle/create?year='.$promotion_cirlce->year) }}">編集</a>
                @endif
            @endif
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
                    <span>{{ isset($promotion_cirlce->id)? \App\Enums\Common::show_id($promotion_cirlce->id) : null }}</span>
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
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$review_last_year!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">職場方針</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$promotion_cirlce->motto_of_the_workplace!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル方針</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$promotion_cirlce->motto_of_circle!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">レベル</td>
                <td>
                    @if($promotion_cirlce->id) <span>Ｘ軸 {{number_format($promotion_cirlce->axis_x, 1)}} 点　／　Ｙ軸 {{number_format($promotion_cirlce->axis_y, 1)}} 点</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">会合回数目標</td>
                <td>
                    @if($promotion_cirlce->id)<span class="width-200">目標　{{$promotion_cirlce->target_number_of_meeting}}回／月</span>
                    <span>実績　{{ number_format(round($count_meeting / 12, 2), 2) }}回／月</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">会合時間目標</td>
                <td>
                    @if($promotion_cirlce->id)<span class="width-200">目標　{{number_format($promotion_cirlce->target_hour_of_meeting, 2)}}ｈ／月</span>
                    <span>実績　{{number_format(round($total_time / 12, 2), 2) }}ｈ／月</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">ﾃｰﾏ完了件数目標</td>
                <td>
                    @if($promotion_cirlce->id)<span
                            class="width-200">目標　{{$promotion_cirlce->target_case_complete}}件／年</span><span>実績　{{ $count_theme_complete }}件／年</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">改善件数目標</td>
                <td>
                    @if($promotion_cirlce->id)<span class="width-200">目標　{{$promotion_cirlce->improved_cases}}件／年</span>
                    <span>実績　{{$count_kaizen}}件／年</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">勉強会開催目標</td>
                <td>
                    @if($promotion_cirlce->id) <span class="width-200">目標　{{$promotion_cirlce->objectives_of_organizing_classe}}回／年</span>
                    <span>実績　{{ $count_meeting_study }}回／年</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">今年度の反省</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$promotion_cirlce->review_this_year !!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">推進者コメント</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$promotion_cirlce->comment_promoter !!}</span></span>
                </td>
            </tr>
        </table>

        <div class="float-right btn-area full-width position-relative" style="margin-top: 2rem">
            <span class="absolute" style="bottom: 0">登録メンバー</span>
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
            <?php $sttMembers = 1; ?>
            @foreach($list_member as $item)
                <?php
                $user = App\User::find($item->user_id);
                ?>
                <tr>
                    <td class="text-center">
                        @if($item->is_leader == 2) <span class="d-none">{{$sttMembers++}}</span><span>L</span>
                        @else <span>{{ $sttMembers++ }}</span>
                        @endif
                    </td>
                    <td>
                        <span>@if(isset($user->position)) ({{ $user->position }}) @endif {{ $user->name }}</span>
                    </td>
                    <td>{{ $item->department }}</td>
                    <td>{{ $item->classification }}</td>
                    <td><span class="line-brake-preserve">{{$item->note}}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
            <span class="absolute" style="bottom: 0">サークルレベル</span>
            @if($promotion_cirlce->id)
                <a class="btn btn-add float-right" style="margin-right: -15px"
                   href="{{ URL::to('circlelevel?promotion_circle_id='.$promotion_cirlce->id) }}">レベル</a>
            @else
                <span class="btn btn-add float-right" disabled
                      style="margin-right: -15px; cursor: not-allowed">レベル</span>
            @endif
        </div>
        <div class="promotion-chart-container">
            <div class="promotion-chart">
                <canvas id="ref-frame-chart"></canvas>
            </div>

            <div class="promotion-chart promotion-chart-radar">
                <canvas id="radar-chart-X"></canvas>
            </div>

            <div class="promotion-chart promotion-chart-radar">
                <canvas id="radar-chart-Y"></canvas>
            </div>
        </div>
    </div>
    <?php
    $avg_x_current_year = round(($arr_x_current_year[0] + $arr_x_current_year[1] + $arr_x_current_year[2] + $arr_x_current_year[3] + $arr_x_current_year[4]) / 5, 1);
    $avg_y_current_year = round(($arr_y_current_year[0] + $arr_y_current_year[1] + $arr_y_current_year[2] + $arr_y_current_year[3] + $arr_y_current_year[4]) / 5, 1);
    $avg_x_last_year = round(($arr_x_last_year[0] + $arr_x_last_year[1] + $arr_x_last_year[2] + $arr_x_last_year[3] + $arr_x_last_year[4]) / 5, 1);
    $avg_y_last_year = round(($arr_y_last_year[0] + $arr_y_last_year[1] + $arr_y_last_year[2] + $arr_y_last_year[3] + $arr_y_last_year[4]) / 5, 1);
    ?>
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

        var Xdata_last_year = [
                    {{ $arr_x_last_year[0] }},
                    {{ $arr_x_last_year[1] }},
                    {{ $arr_x_last_year[2] }},
                    {{ $arr_x_last_year[3] }},
                    {{ $arr_x_last_year[4] }}
            ],
            Xdata_current_year = [
                {{ $arr_x_current_year[0] }},
                {{ $arr_x_current_year[1] }},
                {{ $arr_x_current_year[2] }},
                {{ $arr_x_current_year[3] }},
                {{ $arr_x_current_year[4] }}
            ],
            Ydata_last_year = [
                {{ $arr_y_last_year[0] }},
                {{ $arr_y_last_year[1] }},
                {{ $arr_y_last_year[2] }},
                {{ $arr_y_last_year[3] }},
                {{ $arr_y_last_year[4] }}
            ],
            Ydata__current_year = [
                {{ $arr_y_current_year[0] }},
                {{ $arr_y_current_year[1] }},
                {{ $arr_y_current_year[2] }},
                {{ $arr_y_current_year[3] }},
                {{ $arr_y_current_year[4] }},
            ];


        var last_year_coor = {x: {{ $avg_x_last_year }}, y: {{ $avg_y_last_year }}},
            current_year_coor = {x: {{ $avg_x_current_year }}, y: {{ $avg_y_current_year }}};

        var label_last_year = '{{ $promotion_cirlce->year -1 }}',
            label_current_year = '{{ $promotion_cirlce->year }}';

        var XCanvas = document.getElementById("radar-chart-X");
        var YCanvas = document.getElementById("radar-chart-Y");
        var RefChart = document.getElementById('ref-frame-chart');

        Chart.defaults.global.defaultFontFamily = "MS Gothic";
        Chart.defaults.global.defaultFontSize = 14;

        var dataToFetch = [];

        function fetchData(T) {
            if (T === 'X') {
                dataToFetch = [label_last_year, Xdata_last_year, label_current_year, Xdata_current_year];
            } else if (T === 'Y') {
                dataToFetch = [label_last_year, Ydata_last_year, label_current_year, Ydata__current_year];
            }

            var data = {
                labels: ["イ", "ロ", "ハ", "ニ", "ホ"],
                datasets: [{
                    label: dataToFetch[2],
                    backgroundColor: "transparent",
                    borderColor: "#2e2ecb",
                    fill: false,
                    radius: 0,
                    borderWidth: 5,
                    pointRadius: 0,
                    pointBorderWidth: 3,
                    pointBackgroundColor: "cornflowerblue",
                    pointBorderColor: "rgba(0,0,200,0.6)",
                    pointHoverRadius: 10,
                    data: dataToFetch[3]
                }, {
                    label: dataToFetch[0],
                    backgroundColor: "transparent",
                    borderColor: "#00cc98",
                    fill: false,
                    radius: 0,
                    borderWidth: 5,
                    pointRadius: 0,
                    pointBorderWidth: 3,
                    pointBackgroundColor: "orange",
                    pointBorderColor: "rgba(200,0,0,0.6)",
                    pointHoverRadius: 10,
                    data: dataToFetch[1]
                }
                ]
            };
            return data;
        }

        function radarOptions(Title) {
            var Option = {
                title: {
                    display: true,
                    text: Title,
                    position: 'top',
                    fontColor: '#000',
                    fontSize: 18
                },
                scale: {
                    ticks: {
                        beginAtZero: true,
                        min: 0,
                        max: 5,
                        stepSize: 0.5,
                        fontColor: '#000000',
                        fontStyle: 'normal',
                        fontSize: 12,
                        showLabelBackdrop: false
                    },
                    pointLabels: {
                        fontSize: 16,
                        fontColor: '#000000',
                        fontStyle: 'bold'
                    }
                },
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 1
                    },
                    reverse: true
                },
                animation: {
                    duration: 3000
                }
            };

            return Option;
        }

        var radarChartX = new Chart(XCanvas, {
            type: 'radar',
            data: fetchData('X'),
            options: radarOptions('X')
        });

        var radarChartY = new Chart(YCanvas, {
            type: 'radar',
            data: fetchData('Y'),
            options: radarOptions('Y')
        });

        function scatterData() {
            var lastYear = {
                label: label_last_year,
                backgroundColor: "#00cc98",
                borderColor: "#000000",
                fill: false,
                pointRadius: 10,
                borderWidth: 2,
                pointHoverRadius: 12,
                data: [last_year_coor]
            };

            var currentYear = {
                label: label_current_year,
                backgroundColor: "#2e2ecb",
                borderColor: "#000000",
                fill: false,
                pointRadius: 10,
                borderWidth: 2,
                pointHoverRadius: 12,
                data: [current_year_coor]
            };
            return [lastYear, currentYear];
        }

        var scatterOptions = {
            layout: {
                padding: {
                    left: 0,
                    right: 20,
                    top: 10,
                    bottom: 0
                }
            },
            title: {
                display: true,
                text: 'レベル',
                position: 'top',
                fontColor: '#000',
                fontSize: 18,
                padding: 5
            },
            legend: {
                display: false
            },
            scales: {
                xAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: "良い",
                            fontColor: '#000',
                            fontStyle: 'bold',
                            fontSize: 14
                        },
                        type: "linear",
                        position: "bottom right",
                        ticks: {
                            max: 5,
                            min: 1,
                            stepSize: 1,
                        },
                        gridLines: {
                            display: false
                        }
                    }
                ],
                yAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: "高い",
                            fontColor: '#000',
                            fontStyle: 'bold',
                            fontSize: 14
                        },
                        type: "linear",
                        position: "bottom",
                        ticks: {
                            max: 5,
                            min: 1,
                            stepSize: 1,
                        },
                        gridLines: {
                            display: false
                        }
                    }
                ]
            },
            animation: {
                duration: 3000
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        let xCor = Number(tooltipItem.xLabel).toFixed(1);
                        let yCor = Number(tooltipItem.yLabel).toFixed(1);
                        return xCor + ', ' + yCor;
                    }
                } // end callbacks:
            }
        };

        var scatterChart = new Chart(RefChart, {
            type: "scatter",
            data: {
                datasets: scatterData()
            },
            options: scatterOptions
        });

    </script>
@endsection
