@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('活動記録');
        })
    }}

    {{ Breadcrumbs::render('list') }}

@endsection

@section('content')

    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this);">
            <option value="0" @if(isset($_GET['filter']) && $_GET['filter'] == 0) selected @endif>全て年度</option>
            @if(count($list_year) > 0)
                @foreach($list_year as $list_year_item)
                <option value="{{ $list_year_item->year }}" @if((isset($_GET['filter']) && $_GET['filter'] == $list_year_item->year) || (!isset($_GET['filter']) && $list_year_item->year == date('Y'))) selected @endif>{{ $list_year_item->year }}年度</option>
                @endforeach
            @else
                <option value="{{ date('Y') }}" @if(isset($_GET['filter']) && $_GET['filter'] == date('Y')) selected @endif>{{ date('Y') }}</option>
            @endif
        </select>
        @if(session('circle.id'))
        <?php 
            $filterYear = isset($_GET['filter']) ? $_GET['filter'] : date('Y');
        ?>
        <a class="btn btn-add" href="{{ URL::to('activity/create'.'?year='.$filterYear) }}">追加</a>
        @endif
        <a class="btn btn-back float-right"  href="{{ URL::to('top-page') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
                <tr>
                    <th scope="col">No</th>
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
                            <a class="a-black font-weight-bold" href="{{ URL::to('/activity/'.$item->id.'?holdsort=yes&year='.$filterYear)}}">
                            {{$item->activity_title}}
                            </a>
                        </td>
                        <td>
                            {{ date("Y/m/d", strtotime($item->date_intended)) }} {{ date("H:i", strtotime($item->time_intended)) }}
                        </td>
                        <td>
                            @if(isset($item->date_execution))
                            {{ date("Y/m/d", strtotime($item->date_execution)) }}
                                @if (isset($item->time_start)) {{ date("H:i", strtotime($item->time_start)) }} @endif
                                -
                                @if (isset($item->time_finish)) {{ date("H:i", strtotime($item->time_finish)) }} @endif
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
        var columns = ["no", "activity_category", "activity_title", "date_intended", "date_execution", "participant_number"];
        var index_old = 4;
        var view = 'activity';
        var sortInit = true;
        var noClick = [6,7];
        $(document).ready(sortAndFilter(columns, index_old, view, sortInit, noClick));
    </script>
@endsection

