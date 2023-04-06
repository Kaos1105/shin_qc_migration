@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
           }),
       Breadcrumbs::for('list', function ($trail) {
           $trail->parent('toppage');
           $trail->push('掲示板',route('category.index'));
           })
    }}

    {{ Breadcrumbs::render('list') }}

@endsection

@section('content')

    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this)">
            <option value="0" @if(isset($_GET['filter']) && $_GET['filter'] == 0) selected @endif>全て表示</option>
            <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) || !isset($_GET['filter'])) selected @endif>使用中</option>
            <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
        </select>
        <a class="btn btn-add" href="{{ route('category.create') }}">追加</a>
        <a class="btn btn-back float-right"  href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col" style="width: 3%">No</th>
                <th scope="col" style="width: 20%">カテゴリ</th>
                <th scope="col" style="width: 60%">説明</th>
                <th scope="col" style="width: 10%">最終更新</th>
                <th scope="col" style="width: 7%">スレッド数</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1)*20; ?>
            @foreach ($paginate as $category)
                <?php
                $stt++;
                if(!$category->thread_count){$category->thread_count = 0;}
                ?>
                <tr>
                    <th scope="row" class="font-weight-normal">{{$stt}}</th>
                    <td>
                        <a class="a-black font-weight-bold" href="{{ URL::to('/thread?holdsort=yes',$category->id)}}">
                            {{$category->category_name}}
                        </a>
                    </td>
                    <td>
                        <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!! $category->note !!}</span></span>
                    </td>
                    <td>
                            {{ date_format(date_create($category->updated_at),"Y/m/d H:i") }}
                    </td>
                    <td>{{ $category->thread_count }}</td>
                </tr>

            @endforeach
            </tbody>

        </table>
        @include('common.paginate')
    </div>
@endsection
@section('scripts')
    <script>
        var columns = ["no", "category_name", "note", "updated_at", "thread_count"];
        var index_old = 4;
        var view = 'category';
        var sortInit = true;
        $(document).ready(sortAndFilter(columns, index_old, view, sortInit));
    </script>
@endsection

