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
                $trail->push('事務局年間計画', route('planbyyear.index'));
            })
    }}

    {{
        Breadcrumbs::for('history', function ($trail) {
                $trail->parent('view');
                $trail->push('サマリー');
            })
    }}

    {{ Breadcrumbs::render('history') }}
@endsection

@section('content')
    <div class="float-right btn-area-list">
        <a class="btn btn-back" href="{{ route('planbyyear.index') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th>No</th>
                <th>年度</th>
                <th>活動サークル数</th>
                <th>会合回数</th>
                <th>会合時間</th>
                <th>活動メンバー数</th>
                <th>テーマ完了件数</th>
                <th>改善件数</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 10; ?>
            @foreach ($paginate as $item)
                <?php
                $stt++;
                $activity_list = DB::table('activities')->where('activity_category', \App\Enums\Activity::MEETING)->whereYear('date_execution', $item->year)->get();
                $total_time = 0.0;
                if (isset($activity_list)) {
                    foreach ($activity_list as $activity_list_item) {
                        if (isset($activity_list_item->time_start) && isset($activity_list_item->time_finish)) {
                            $total_time += App\Enums\Common::total_time($activity_list_item->time_start, $activity_list_item->time_finish);
                        }
                    }
                }

                $activity_real = DB::table('activities')->whereYear('date_execution', $item->year)->get();
                $kaizen_list = DB::table('activities')->where('activity_category', \App\Enums\Activity::KAIZEN)->whereYear('date_execution', $item->year)->get();
                ?>
                <tr>
                    <td>{{$stt}}</td>
                    <td>{{$item->year}}</td>
                    <td>{{$item->numCircle}}</td>
                    <td>計画 {{$item->meeting_times}}回　実績 {{ isset($activity_list)? count($activity_list) : 0 }}回</td>
                    <td>計画 {{$item->meeting_hour}}ｈ　実績 {{ number_format(round($total_time, 2), 2) }}ｈ</td>
                    <td>{{$item->totalMem}}</td>
                    <td>計画 {{$item->case_number_complete}}件　実績 {{ isset($activity_real)? count($activity_real) : 0 }}件
                    </td>
                    <td>計画 {{$item->case_number_improve}}件　実績 {{ isset($kaizen_list)? count($kaizen_list) : 0 }}件</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
@endsection
@section('scripts')
    <script>
        var columns = ["no", "year", "numCircle", "meeting_times", "meeting_hour", "totalMem", "case_number_complete", "case_number_improve"];
        var index_old = 2;
        var view = 'history';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

