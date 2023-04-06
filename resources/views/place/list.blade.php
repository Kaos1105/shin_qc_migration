@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
         Breadcrumbs::for('place', function ($trail) {
            $trail->parent('toppage');
            $trail->push('職場マスタ');
        })
    }}
    {{ Breadcrumbs::render('place') }}
@endsection

@section('content')

    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this);">
            <option value="0" @if(isset($_GET['filter']) && $_GET['filter'] == 0) selected @endif>全て表示</option>
            <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) || !isset($_GET['filter'])) selected @endif>使用中</option>
            <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
        </select>
        <a class="btn btn-add" href="{{ route('place.create') }}">追加</a>
        <a class="btn btn-back"  href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">職場名</th>
                    <th scope="col">所属部門</th>
                    <th scope="col">職場世話人</th>
                    <th scope="col">サークル数</th>
                    <th scope="col">表示順 </th>
                    <th scope="col">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = ($paginate->currentPage() - 1)*20 + 1; ?>
                @foreach ($paginate as $place)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td><a class="a-black font-weight-bold" href="{{ URL::to('/place/'.$place->id.'?holdsort=yes')}}"> {{$place->place_name}} </a></td>
                        <td>
                            <a class="a-black" href="{{ URL::to('department/'.$place->department_id.'?callback=yes') }}">
                                {{ $place->department_name }}
                            </a></td>
                        <td>
                            <a class="a-black" href="{{ URL::to('user/'.$place->user_id.'?callback=yes') }}">@if(isset($place->position)) ({{ $place->position }}) @endif {{ $place->user_name }}</a>
                        </td>
                        <td>{{$place->numCircle}}</td>
                        <td>{{$place->display_order}}</td>
                        <td><span class="line-brake-preserve">{{$place->note}}</span></td>
                    </tr>

                @endforeach
            </tbody>
        </table>
        @include('common.paginate')

        <form id="keepSort" style="display: none">
            @csrf
            <input type="hidden" name="master" value="place">
            <input type="hidden" id="sort-keys" name="sort_keys" value="">
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        var columns = ["no", "place_name", "department_name", "user_name", "numCircle", "display_order", "note"];
        var index_old = 6;
        var view = 'place';
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

