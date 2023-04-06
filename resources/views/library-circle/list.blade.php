@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
           }),
       Breadcrumbs::for('index', function ($trail) {
           $trail->parent('toppage');
           $trail->push('書庫');
           })
    }}

    {{ Breadcrumbs::render('index') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 15%">区分</th>
                <th style="width: 20%">タイトル</th>
                <th style="width: 20%">ファイル名</th>
                <th style="width: 30%">説明</th>
                <th style="width: 13%">最終更新</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; ?>
            @foreach ($paginate as $library)
                @if($library->now_post == 1)
                    <?php
                    $stt++;
                    $user_updated_by = DB::table('users')->where('id', $library->updated_by)->first();
                    ?>
                    <tr>
                        <th class="font-weight-normal">{{$stt}}</th>
                        <td>
                            <span>{{$library->library_type}}</span>
                        </td>
                        <td>
                            <span>{{$library->title}}</span>
                        </td>
                        <td>
                            <a class="a-black" href="{{route('library-circle.download', $library->id)}}">
                                <span>{{$library->fileName}}</span>
                            </a>
                        </td>
                        <td>
                            <span class="line-brake-preserve">{{$library->note}}</span>
                        </td>
                        <td>
                            <span>{{date_format(date_create($library->updated_at),"Y/m/d")}} {{isset($user_updated_by)? $user_updated_by->name:''}}</span>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        @if ($errors->has('download_error'))
        alert('{{ $errors->first('download_error')}}');
        @endif
    </script>
    <script>
        var columns = ["no", "library_type", "title", "fileName", "note", "updated_at"];
        var index_old = 3;
        var view = 'library-circle';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

