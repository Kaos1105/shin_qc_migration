@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('サークル推進計画');
        })
    }}
    {{ Breadcrumbs::render('list') }}
@endsection

@section('content')
    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" style="margin-left: 217px" onchange="changeFilterYear(this);">
            <option value="all">全て</option>
            @for($year = $min_year; $year <= $max_year; $year++)
                <option value="{{$year}}" @if($year == $current_year) selected @endif>{{$year}}年度</option>
            @endfor
        </select>
        <select class="btn btn-back filter-btn" style="width: 200px" onchange="changeFilterCircle(this);">
            <option value="all">全て</option>
            @foreach($circle_list as $circle_list_item)
                <option value="{{$circle_list_item->id}}"
                        @if($circle_list_item->id == $current_circle) selected @endif>{{$circle_list_item->circle_name}}</option>
            @endforeach
        </select>
        <a class="btn btn-back float-right" href="{{ URL::to('top-page') }}">戻る</a>
    </div>
    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">サークル</th>
                <th scope="col">年度</th>
                <th scope="col">会合回数</th>
                <th scope="col">会合時間</th>
                <th scope="col">テーマ完了件数</th>
                <th scope="col">改善件数</th>
                <th scope="col">勉強会開催数</th>
                <th scope="col">レベル　Ｘ軸</th>
                <th scope="col">レベル　Ｙ軸</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; ?>
            @foreach ($paginate as $item)
                <?php
                $stt++;
                $circle = \App\Models\Circle::find($item->circle_id);
                $meeting_list = DB::table('activities')->where('circle_id', $item->circle_id)->where('activity_category', \App\Enums\Activity::MEETING)->whereYear('date_execution', $item->year)->get();
                $total_time = 0.0;
                if (isset($meeting_list)) {
                    $total_time = DB::table('activities')
                        ->where('circle_id', $item->circle_id)
                        ->where('activity_category', \App\Enums\Activity::MEETING)
                        ->whereYear('date_execution', $item->year)
                        ->sum('time_span');
                }
                $theme_complete = DB::table('activities')->where('circle_id', $item->circle_id)->whereYear('date_execution', $item->year)->get();
                $study_list = DB::table('activities')->where('circle_id', $item->circle_id)->where('activity_category', \App\Enums\Activity::STUDY_GROUP)->whereYear('date_execution', $item->year)->get();
                $kaizen_list = DB::table('activities')->where('circle_id', $item->circle_id)->where('activity_category', \App\Enums\Activity::KAIZEN)->whereYear('date_execution', $item->year)->get();
                ?>
                <tr>
                    <td>{{$stt}}</td>
                    <td>{{$circle->circle_name}}サークル</td>
                    <td>
                        <a class="a-black font-weight-bold"
                           href="{{ URL::to('/promotion-circle-office/'.$item->id.'?year='.$item->year) }}">{{$item->year}}</a>
                    </td>
                    <td>
                        計画 {{ $item->target_number_of_meeting }}回<br/>
                        実績 {{ isset($meeting_list)? count($meeting_list) : 0 }}回
                    </td>
                    <td>
                        計画 {{  isset($item->target_hour_of_meeting)? $item->target_hour_of_meeting : 0 }}ｈ<br/>
                        実績 {{ number_format(round($total_time, 2), 2) }}ｈ
                    </td>
                    <td>
                        計画 {{ $item->target_case_complete }}件<br/>
                        実績 {{ isset($theme_complete)? count($theme_complete) : 0 }}件
                    </td>
                    <td>
                        計画 {{ $item->improved_cases }}件<br/>
                        実績 {{ isset($kaizen_list)? count($kaizen_list) : 0 }}件
                    </td>
                    <td>
                        計画 {{ $item->objectives_of_organizing_classe }}回<br/>
                        実績 {{ isset($study_list)? count($study_list) : 0 }}回
                    </td>
                    <td>{{ round($item->axis_x, 1) }}</td>
                    <td>{{ round($item->axis_y, 1) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        var url = document.location.href;
        var currentUrl;
        if (url.indexOf("year=") == -1 || url.indexOf("circle=") == -1) {
            if(url.indexOf('page') == -1) {
                currentUrl = url + '?year=all&circle=all&sort=year&sortType=asc';
            } else {
                currentUrl = url.slice(0, url.indexOf('page=')-1) + '?year=all&circle=all&sort=year&sortType=asc';
            }
        } else {
            if(url.indexOf('page') == -1) {
                currentUrl = url;
            } else {
                let page = url.slice(url.indexOf('page='), url.indexOf('&')+1);
                currentUrl = url.replace(page, "");
            }
        }

        function changeFilterYear(own) {
            var index = currentUrl.indexOf('year=all');
            if (index != -1) {
                currentUrl = currentUrl.replace("year=all", "year=" + own.value);
            } else {
                index = currentUrl.indexOf('year=');
                var textReplace = currentUrl.substr(index + 5, 4);
                currentUrl = currentUrl.replace(textReplace, own.value);
            }
            window.location.href = currentUrl;
        }

        function changeFilterCircle(own) {
            var index = currentUrl.indexOf('circle=all');
            if (index != -1) {
                currentUrl = currentUrl.replace("circle=all", "circle=" + own.value);
            } else {
                index = currentUrl.indexOf('circle=');
                var textReplace = currentUrl.substr(index, currentUrl.length - index);
                currentUrl = currentUrl.replace(textReplace, 'circle=' + own.value);
            }
            window.location.href = currentUrl;
        }

        function changeSort(field) {
            var index = currentUrl.lastIndexOf('sort=');
            if (index == -1) {
                currentUrl = currentUrl + '&sort=' + field + '&sortType=asc';
            } else {
                let ampersand = currentUrl.lastIndexOf('&');
                let textReplace = currentUrl.substr(index, ampersand - index);
                currentUrl = currentUrl.replace(textReplace, 'sort=' + field);
            }
        }

        function changeSortType(sortType) {
            var index = currentUrl.lastIndexOf('sortType=');
            if (index != -1) {
                let textReplace = currentUrl.substr(index, currentUrl.length - index);
                currentUrl = currentUrl.replace(textReplace, "sortType=" + sortType);
            }
        }

        var columns = ["no", "circle_name", "year", "target_number_of_meeting", "target_hour_of_meeting", "target_case_complete", "improved_cases", "objectives_of_organizing_classe", "axis_x", "axis_y"];
        var index_old = 3;
        var sortUrl = getUrlParameter('sort');
        var sortTypeUrl = getUrlParameter('sortType');

        $(function () {
            if (sortUrl !== undefined && sortTypeUrl !== undefined) {
                var indexUrl = columns.indexOf(sortUrl);
                if (indexUrl > 0) {
                    if (sortTypeUrl == 'asc') {
                        $('#myTable tr:first-child th:nth-child(' + (indexUrl + 1) + ')').append(sort_asc);
                    } else {
                        $('#myTable tr:first-child th:nth-child(' + (indexUrl + 1) + ')').append(sort_desc);
                    }
                    index_old = indexUrl + 1;
                    if (sortTypeUrl == 'desc') {
                        isAsc = false;
                    }
                } else {
                    $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_asc);
                }
            } else {
                $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_asc);
            }

            $('#myTable thead tr th').click(function () {
                let index = $(this).index();
                if (index != 0) {
                    let sortType = "asc";
                    if (index == (index_old - 1)) {
                        isAsc = !isAsc;
                    } else {
                        isAsc = true;
                    }
                    if (!isAsc) { sortType = "desc"; }
                    var filed =  columns[index];
                    changeSort(filed);
                    changeSortType(sortType);
                    window.location.href = currentUrl;
                }
            });
        });
    </script>
    <script>
        $(function () {
            if (sessionStorage.getItem('stampMode')) {
                sessionStorage.removeItem('stampMode');
            }
        });
    </script>
@endsection
