@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
       Breadcrumbs::for('index', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ストーリー登録（一覧）');
       })
    }}

    {{ Breadcrumbs::render('index') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this)">
            <option value="0"
                    @if((isset($_GET['filter']) && $_GET['filter'] == 0) || !isset($_GET['filter'])) selected @endif>全て
            </option>
            <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) ) selected @endif>使用中</option>
            <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
        </select>
        <a class="btn btn-add" href="{{ URL::to('story/add') }}">追加</a>
        <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col" style="width: 3%">No</th>
                <th scope="col" style="width: 15%">区分</th>
                <th scope="col" style="width: 20%">ストーリー名</th>
                <th scope="col" style="width: 40%">説明</th>
                <th scope="col" style="width: 7%" class="text-center">表示順</th>
                <th scope="col" style="width: 15%">更新</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; $story = $paginate ?>
            @for ($i = 0; $i < sizeof($story); $i++)
                <?php
                $stt++;
                $user_updated_by = DB::table('users')->where('id', $story[$i]->updated_by)->first();
                ?>
                <tr>
                    <th class="font-weight-normal">{{$stt}}</th>
                    <td>
                        <span>{{$story[$i]->story_classification}}</span>
                    </td>
                    <td>
                        <a class="a-black font-weight-bold"
                           href="{{ URL::to('/story/show?holdsort=yes', [$story[$i]->id]) }}">
                            <span class="line-brake-preserve">{{$story[$i]->story_name}}</span>
                        </a>
                    </td>
                    <td>
                        <span class="line-brake-preserve">{{$story[$i]->description}}</span>
                    </td>
                    <td class="text-center">
                        <form id="change_display_order_{{$story[$i]->display_order}}" method="post"
                              action="change">
                            @csrf
                            <input type="hidden" name="id" value="{{$story[$i]->id}}"/>
                            <input type="hidden" name="sort_type" value="{{isset($_GET['sortType']) ? $_GET['sortType'] : null}}"/>
                        @if($sortType == "asc")
                                @if($story[$i]->display_order == $min_display_order)
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @elseif($story[$i]->display_order == $max_display_order)
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                @else
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @endif
                            @elseif($sortType == 'desc')
                                @if($story[$i]->display_order == $max_display_order)
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @elseif($story[$i]->display_order == $min_display_order)
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                @else
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @endif
                            @endif
                            <input type="hidden" class="up-down" name="up_down" value=""/>
                        </form>
                    </td>
                    <td>
                        <span> {{ isset($story[$i]->updated_at) ? date_format(date_create($story[$i]->updated_at),"Y/m/d") : null }} {{ isset($user_updated_by) ? $user_updated_by->name : null }}</span>
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        let sort = '{{$sort}}';
        <?php
        $count = \DB::table('stories')->count();
        ?>
        let count = {{$count}};
        $(function () {
            if (count > 1 && (!sort || sort == 'display_order')) {
                $('.btn-disp-order').prop('disabled', false);
            }
        });
    </script>
    <script>
        var columns = ["no", "story_classification", "story_name", "description", "display_order", "updated_at"];
        var index_old = 5;
        var view = 'list';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
    <script>
        $(function () {
            $('.disp-order-up').on('click', function () {
                $(this).siblings('.up-down').val('up');
                let this_form = $(this).closest('form');
                this_form.submit();
            });
            $('.disp-order-down').on('click', function () {
                $(this).siblings('.up-down').val('down');
                let this_form = $(this).closest('form');
                this_form.submit();
            });
        });
    </script>
@endsection

