@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('circle', function ($trail) {
            $trail->parent('toppage');
            $trail->push('サークル',route('circle.index'));
        })
    }}

    {{ Breadcrumbs::render('circle') }}

@endsection

@section('content')

    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this)">
            <option value="0" @if(isset($_GET['filter']) && $_GET['filter'] == 0) selected @endif>全て表示</option>
            <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) || !isset($_GET['filter'])) selected @endif>使用中</option>
            <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
        </select>
        <a class="btn btn-add" href="{{ route('circle.create') }}">追加</a>
        <a class="btn btn-back"  href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">登録番号</th>
                    <th scope="col">サークル名</th>
                    <th scope="col">部門</th>
                    <th scope="col">職場</th>
                    <th scope="col">サークル推進者</th>
                    <th scope="col">登録日</th>
                    <th scope="col">メンバ数</th>
                    <th scope="col">最終活動日</th>
                    <th scope="col">表示順</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = ($paginate->currentPage() - 1)*20 + 1; ?>
                @foreach ($paginate as $circle)
                    <?php
                    $activity_lastest = DB::table('activities')->where('circle_id', $circle->id)->orderBy('date_execution', 'DESC')->first();
                    ?>
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td>{{ $circle->circle_code }}</td>
                        <td>
                            <a class="a-black font-weight-bold" href="{{ URL::to('/circle/'.$circle->id.'?holdsort=yes')}}">
                            {{ $circle->circle_name }}
                            </a>
                        </td>
                        <td>
                            <a class="a-black" href="{{ URL::to('/department/'.$circle->department_id.'?callback=yes')}}">
                                {{ $circle->department_name }}
                            </a>
                        </td>
                        <td>
                            <a class="a-black" href="{{ URL::to('/place/'.$circle->place_id.'?callback=yes')}}">
                            {{ $circle->place_name }}
                            </a>
                        </td>
                        <td> <a class="a-black" href="{{ URL::to('/user/'.$circle->user_id.'?callback=yes')}}">@if(isset($circle->position)) ({{ $circle->position }}) @endif {{ $circle->user_name }}</a></td>
                        <td>{{ isset($circle->date_register)? date("Y/m/d", strtotime($circle->date_register)) : null }}</td>
                        <td>{{$circle->numMember}}</td>
                        <td>{{ isset($activity_lastest)? date("Y/m/d", strtotime($activity_lastest->date_execution)) : null }}</td>
                        <td>{{$circle->display_order}}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
        @include('common.paginate')
        <form id="keepSort" style="display: none">
            @csrf
            <input type="hidden" name="master" value="circle">
            <input type="hidden" id="sort-keys" name="sort_keys" value="">
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        var columns = ["no", "circle_code", "circle_name", "department_name", "place_name", "user_name", "date_register", "numMember","date_execution", "display_order"];
        var index_old = 10;
        var view = 'circle';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
    <script>
        $(function () {
            let url = location.href;
            let sortKeys;
            if (url.indexOf('?') !== -1) {
                sortKeys = url.slice(url.indexOf('?'), url.length);
            } else {
                sortKeys = "?all";
            }
            $('#sort-keys').val(sortKeys);

            $.ajax({
                url: "{{route('keep-sort')}}",
                type: "POST",
                data: $('#keepSort').serialize()
            });
        });
    </script>
@endsection

