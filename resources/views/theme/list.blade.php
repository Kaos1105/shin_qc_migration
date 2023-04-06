@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('テーマ登録');
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
        <a class="btn btn-add" href="{{ URL::to('theme/create') }}">追加</a>
        @endif
        <a class="btn btn-back float-right"  href="{{ URL::to('top-page') }}">戻る</a>
    </div>
    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">テーマ</th>
                    <th scope="col">特性値</th>
                    <th scope="col">目標値</th>
                    <th scope="col">開始予定日 </th>
                    <th scope="col">進捗区分</th>
                    <th scope="col">完了予定日</th>
                    <th scope="col">完了実績日</th>
                    <th scope="col">会合回数</th>
                    <th scope="col">会合時間</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = ($paginate->currentPage() - 1)*20 +1; ?>
                @foreach ($paginate as $theme)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td>
                            <a class="font-weight-bold a-black" href="{{ URL::to('theme/'.$theme->id.'?holdsort=yes') }}">{{ $theme->theme_name }}</a>
                        </td>
                        <td>{{ $theme->value_property }}</td>
                        <td>{{ $theme->value_objective }}</td>
                        <td>{{ date('Y/m/d', strtotime($theme->date_start)) }}</td>
                        <td>
                        @if(isset($theme->progress))
                            @if($theme->progress == 1)
                                1.テーマ選定
                            @elseif($theme->progress == 2)
                                2.現状把握と目標設定
                            @elseif($theme->progress == 3)
                                3.活動計画の作成
                            @elseif($theme->progress == 4)
                                4.要因解析
                            @elseif($theme->progress == 5)
                                5.対策検討と実施
                            @elseif($theme->progress == 6)
                                6.効果確認
                            @else
                                7.標準化と管理の定着
                            @endif
                        @endif
                        </td>
                        <td>{{ date('Y/m/d', strtotime($theme->date_expected_completion)) }}</td>
                        <td>@if(isset($theme->date_actual_completion)) {{ date('Y/m/d', strtotime($theme->date_actual_completion)) }} @endif</td>
                        <td>{{ $theme->num_meeting }}</td>
                        <td>@if($theme->hours_meeting){{number_format($theme->hours_meeting, 2)}} @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
@endsection
@section('scripts')
    <script>
        var columns = ["no", "theme_name", "value_property", "value_objective", "date_start", "progress", "date_expected_completion", "date_actual_2", "num_meeting", "hours_meeting"];
        var index_old = 5;
        var view = 'theme';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

