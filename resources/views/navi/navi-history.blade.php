@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
            }),
         Breadcrumbs::for('story-classification', function ($trail) {
           $trail->parent('toppage');
           $trail->push('ＱＣナビ', URL::to('qc-navi/story-classification'));
       }),
       Breadcrumbs::for('navi-history', function ($trail) {
           $trail->parent('story-classification');
           $trail->push('ナビ履歴');
       })
    }}

    {{ Breadcrumbs::render('navi-history') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this)">
            <option value="0" @if($filter == 0) selected @endif >全て</option>
            @foreach($user_list as $userName)
                <option value="{{$userName->userId}}" @if($userName->userId == $filter) selected @endif >{{$userName->user_name}}</option>
            @endforeach
        </select>
        <a class="btn btn-back float-right" href="{{ URL::to('qc-navi/story-classification') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">開始日時</th>
                <th scope="col">開始ストーリー</th>
                <th scope="col">開始ＱＡ画面</th>
                <th scope="col">利用者</th>
                <th scope="col">利用時間</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; ?>
            @foreach ($paginate as $history)
                <?php
                $stt++;
                ?>
                <tr>
                    <th class="font-weight-normal">{{$stt}}</th>
                    <td>
                        <a class="a-black font-weight-bold"
                           href="{{URL::to('/qc-navi/navi-detail/'.$history->id)}}">{{date_format(date_create($history->date_start),"Y/m/d H:i")}}</a>
                    </td>
                    <td>
                        @if(isset($history->storii))
                            <span>{{$history->storii}}</span>
                        @else
                            <span>削除されています</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($history->startQa))
                            <span>{{$history->startQa}}</span>
                        @else
                            <span>削除されています</span>
                        @endif
                    </td>
                    <td>
                        <span>{{$history->user_name}}</span>
                    </td>
                    <td>
                        <span>{{$history->duration}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        var columns = ["no", "date_start", "storii", "startQa", "user_name", "duration"];
        var index_old = 2;
        var view = 'navi-history';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

