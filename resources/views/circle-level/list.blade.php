@extends('layouts.app')
@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
       Breadcrumbs::for('promotion_circle', function ($trail, $year, $circle_name) {
            $trail->parent('toppage');
            $trail->push('サークル推進計画（'.$circle_name.'）（'.$year.'年）', URL::to('promotion-circle?year='.$year));
       }),
       Breadcrumbs::for('list', function ($trail, $year, $circle_name) {
            $trail->parent('promotion_circle', $year, $circle_name);
            $trail->push('サークルレベル');
       })
    }}

    {{ Breadcrumbs::render('list', $year, $circle_name) }}
@endsection
@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-back" href="{{ URL::to('promotion-circle?year='.$year) }}">戻る</a>
    </div>

    <div style="margin: 15px">
        <table class="table circle_level_list" border="1">
            <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">メンバー名</th>
                <th colspan="6">Ｘ軸</th>
                <th colspan="6">Ｙ軸</th>
            </tr>
            <tr>
                <th>イ</th>
                <th>ロ</th>
                <th>ハ</th>
                <th>ニ</th>
                <th>ホ</th>
                <th>平均</th>
                <th>イ</th>
                <th>ロ</th>
                <th>ハ</th>
                <th>ニ</th>
                <th>ホ</th>
                <th>平均</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $stt = 1;
                $total_x_i = 0;
                $total_x_ro = 0;
                $total_x_ha = 0;
                $total_x_ni = 0;
                $total_x_ho = 0;
                $total_y_i = 0;
                $total_y_ro = 0;
                $total_y_ha = 0;
                $total_y_ni = 0;
                $total_y_ho = 0;
                $count = 0;
            ?>

            @foreach($member_list as $item)
            <?php
                $circle_level = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle_id)->where('member_id', $item->id)
                    ->select('axis_x_i', 'axis_x_ro', 'axis_x_ha', 'axis_x_ni', 'axis_x_ho', 'axis_y_i', 'axis_y_ro', 'axis_y_ha', 'axis_y_ni', 'axis_y_ho')->first();
                if($circle_level){
                    $total_x_i += $circle_level->axis_x_i;
                    $total_x_ro += $circle_level->axis_x_ro;
                    $total_x_ha += $circle_level->axis_x_ha;
                    $total_x_ni += $circle_level->axis_x_ni;
                    $total_x_ho += $circle_level->axis_x_ho;
                    $total_y_i += $circle_level->axis_y_i;
                    $total_y_ro += $circle_level->axis_y_ro;
                    $total_y_ha += $circle_level->axis_y_ha;
                    $total_y_ni += $circle_level->axis_y_ni;
                    $total_y_ho += $circle_level->axis_y_ho;
                    $count++;
                }
            ?>
            <tr>
                <td style="text-align: center">
                    @if($item->is_leader == 2) <span class="d-none">{{$stt++}}</span><span>L</span>
                    @else <span>{{ $stt++ }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ URL::to('circlelevel/create?member_id='.$item->id.'&promotion_circle_id='.$promotion_circle_id) }}">
                        {{ $item->department }}@if(isset($item->position)) ({{ $item->position }}) @endif {{ $item->name }}
                    </a>
                </td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_i:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ro:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ha:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ni:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ho:null }}</td>
                <td>{{ isset($circle_level)?
                    number_format(round(($circle_level->axis_x_i + $circle_level->axis_x_ro + $circle_level->axis_x_ha + $circle_level->axis_x_ni + $circle_level->axis_x_ho)/5, 2), 2)
                    :null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_i:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ro:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ha:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ni:null }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ho:null }}</td>
                <td>
                    {{ isset($circle_level)?
                    number_format(round(($circle_level->axis_y_i + $circle_level->axis_y_ro + $circle_level->axis_y_ha + $circle_level->axis_y_ni + $circle_level->axis_y_ho)/5, 2), 2)
                    :null }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2">平均</td>
                <td>{{ number_format(round(($count > 0)? $total_x_i/$count : 0, 2), 2)}}</td>
                <td>{{ number_format(round(($count > 0)? $total_x_ro/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_x_ha/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_x_ni/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_x_ho/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? ($total_x_i/$count + $total_x_ro/$count + $total_x_ha/$count + $total_x_ni/$count + $total_x_ho/$count)/5 : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_y_i/$count : 0, 2), 2)}}</td>
                <td>{{ number_format(round(($count > 0)? $total_y_ro/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_y_ha/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_y_ni/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? $total_y_ho/$count : 0, 2), 2) }}</td>
                <td>{{ number_format(round(($count > 0)? ($total_y_i/$count + $total_y_ro/$count + $total_y_ha/$count + $total_y_ni/$count + $total_y_ho/$count)/5 : 0, 2), 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection