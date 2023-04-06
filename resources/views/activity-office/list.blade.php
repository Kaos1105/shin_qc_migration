@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('活動記録管理');
        })
    }}

    {{ Breadcrumbs::render('list') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" style="margin-left: 217px" onchange="changeFilterYear(this);">
            <option value="all" @if(isset($_GET['year']) && $_GET['year'] == "all") selected @endif>全て年度</option>
            @if(count($list_year) > 0)
                @foreach($list_year as $list_year_item)
                    <option value="{{ $list_year_item->year }}" @if((isset($_GET['year']) && $_GET['year'] == $list_year_item->year) || (!isset($_GET['year']) && $list_year_item->year == date('Y'))) selected @endif>{{ $list_year_item->year }}年度</option>
                @endforeach
            @else
                <option value="{{ date('Y') }}" @if(isset($_GET['year']) && $_GET['year'] == date('Y')) selected @endif>{{ date('Y') }}</option>
            @endif
        </select>
        <select class="btn btn-back filter-btn" style="width: 200px" onchange="changeFilterCircle(this);">
            <option value="all">全て</option>
            @foreach($circle_list as $circle_list_item)
                <option value="{{$circle_list_item->circleID}}" @if(isset($_GET['circle']) && $_GET['circle'] == $circle_list_item->circleID) selected @endif >{{$circle_list_item->circle_name}}</option>
            @endforeach
        </select>
        <a class="btn btn-back float-right"  href="{{ URL::to('top-page') }}">戻る</a>
    </div>
    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">サークル名</th>
                    <th scope="col">活動区分</th>
                    <th scope="col">タイトル</th>
                    <th scope="col">予定日時</th>
                    <th scope="col">実施日時 </th>
                    <th scope="col">参加者数</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = ($paginate->currentPage() - 1)*20 + 1; ?>
                @foreach ($paginate as $item)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td>{{ $item->circle_name }}</td>
                        <td>
                            @if($item->activity_category == App\Enums\Activity::MEETING)
                                会合
                            @elseif($item->activity_category == App\Enums\Activity::STUDY_GROUP)
                                勉強会
                            @elseif($item->activity_category == App\Enums\Activity::KAIZEN)
                                改善提案
                            @else
                                その他
                            @endif
                        </td>
                        <td>
                            <a class="a-black" href="{{ URL::to('/activity-office/show/'.$item->id.'?holdsort=yes')}}">
                            {{$item->activity_title}}
                            </a>
                        </td>
                        <td>
                            {{ date("Y/m/d", strtotime($item->date_intended)) }} {{ date("H:i", strtotime($item->time_intended)) }}
                        </td>
                        <td>
                            @if(isset($item->date_execution))
                                {{ date("Y/m/d", strtotime($item->date_execution)) }} {{ date("H:i", strtotime($item->time_start)) }}-{{ date("H:i", strtotime($item->time_finish)) }}
                            @endif
                        </td>
                        <td>{{$item->participant_number}}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
@endsection
@section('scripts')
    <script>
        var url = document.location.href;
        var currentUrl;
        var year = "{{$year}}";
        var initSort = 'date_intended';
        if (url.indexOf("year=") == -1 || url.indexOf("circle=") == -1) {
            if(url.indexOf('page') == -1) {
                currentUrl = url + '?year=' + year + '&circle=all&sort=' + initSort + '&sortType=asc';
            } else {
                currentUrl = url.slice(0, url.indexOf('page=')-1) + '?year=' + year + '&circle=all&sort=' + initSort + '&sortType=asc';
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
                var indexSort = currentUrl.indexOf('sort=');
                var textReplace = currentUrl.substr(index, indexSort - index - 1);
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

        var columns = ["no", "circle_name", "activity_category", "activity_title", "date_intended", "date_execution", "participant_number"];
        var index_old = 5;
        var sortInit = true;
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
                if (!sortInit) {
                    $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_asc);
                } else {
                    $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_desc);
                }
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
@endsection

