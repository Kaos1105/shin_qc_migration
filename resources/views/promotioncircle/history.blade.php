@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('view', function ($trail, $circle_name) {
            $trail->parent('toppage');
            $trail->push('サークル推進計画('.$circle_name.')', URL::to('promotion-circle'));
        }),
        Breadcrumbs::for('history', function ($trail, $circle_name) {
            $trail->parent('view', $circle_name);
            $trail->push('サマリー');
        })
    }}

    {{ Breadcrumbs::render('history',session('circle.circle_name')) }}
@endsection

@section('content')
    <div class="float-right btn-area-list">
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('promotioncircle.index') }}">戻る</a>
        @endif    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col">No</th>
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
            <?php $stt = ($paginate->currentPage() - 1) * 20;?>

            @foreach ($paginate as $item)
                <?php
                $stt++;
                $meeting_list = DB::table('activities')->where('circle_id', $item->circle_id)
                    ->where('activity_category', \App\Enums\Activity::MEETING)
                    ->whereYear('date_execution', $item->year)
                    ->get();
                $total_time = 0.0;
                if (isset($meeting_list)) {
                    $total_time = DB::table('activities')
                        ->where('circle_id', $item->circle_id)
                        ->where('activity_category', \App\Enums\Activity::MEETING)
                        ->whereYear('date_execution', $item->year)
                        ->sum('time_span');
                }
                $meeting_finish = DB::table('activities')->where('circle_id', $item->circle_id)->whereYear('date_execution', $item->year)->get();
                $study_list = DB::table('activities')->where('circle_id', $item->circle_id)->where('activity_category', \App\Enums\Activity::STUDY_GROUP)->whereYear('date_execution', $item->year)->get();
                $kaizen_list = DB::table('activities')->where('circle_id', $item->circle_id)->where('activity_category', \App\Enums\Activity::KAIZEN)->whereYear('date_execution', $item->year)->get();
                ?>
                <tr>
                    <td>{{$stt}}</td>
                    <td>{{$item->year}}</td>
                    <td>
                        計画 {{$item->target_number_of_meeting * 12}}回<br/>
                        実績 {{ isset($meeting_list)? count($meeting_list) : 0 }}回
                    </td>
                    <td>
                        計画 {{number_format($item->target_hour_of_meeting * 12, 2)}}ｈ<br/>
                        実績 {{number_format(round($total_time, 2),2) }}ｈ
                    </td>
                    <td>
                        計画 {{$item->target_case_complete ? : 0}}件<br/>
                        実績 {{ isset($meeting_finish)? count($meeting_finish) : 0 }}件
                    </td>
                    <td>
                        計画 {{$item->improved_cases ? : 0}}件<br/>
                        実績 {{ isset($kaizen_list)? count($kaizen_list) : 0 }}件
                    </td>
                    <td>
                        計画 {{$item->objectives_of_organizing_classe ? : 0}}回<br/>
                        実績 {{ isset($study_list)? count($study_list) : 0 }}回
                    </td>
                    <td>{{number_format($item->axis_x, 1)}}</td>
                    <td>{{number_format($item->axis_y, 1)}}</td>
                </tr>

            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
@endsection
@section('scripts')
    <script>
        var columns = ["no", "year", "target_number_of_meeting", "target_hour_of_meeting", "target_case_complete", "improved_cases", "objectives_of_organizing_classe", "axis_x", "axis_y"];
        var index_old = 2;
        var view = 'history';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

