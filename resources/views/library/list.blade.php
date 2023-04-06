@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
           }),
       Breadcrumbs::for('index', function ($trail) {
           $trail->parent('toppage');
           $trail->push('書庫管理',route('library.index'));
           })
    }}

    {{ Breadcrumbs::render('index') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <select class="btn btn-back filter-btn" onchange="Filter(this)">
            <option value="0"
                    @if(isset($_GET['filter']) && $_GET['filter'] == 0 || !isset($_GET['filter'])) selected @endif>全て表示
            </option>
            <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) ) selected @endif>使用中</option>
            <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
        </select>
        <a class="btn btn-add" href="{{ route('library.create') }}">追加</a>
        <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 20%">区分</th>
                <th style="width: 30%">タイトル</th>
                <th style="width: 20%">掲載期間</th>
                <th style="width: 7%">使用区分</th>
                <th style="width: 10%">表示順</th>
                <th style="width: 10%">最終更新</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; ?>
            @foreach ($paginate as $library)
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
                        <a class="a-black font-weight-bold"
                           href="{{ URL::to('/library?holdsort=yes', [$library->id]) }}">
                            <span class="line-brake-preserve">{{$library->title}}</span>
                        </a>
                    </td>
                    <td>
                        @if ($library->date_start != null)
                            <span>{{ date_format(date_create($library->date_start),"Y/m/d H:i") }}</span>
                            @if ($library->date_end != null)
                                <span>- {{ date_format(date_create($library->date_end),"Y/m/d H:i") }}</span>
                            @else <span>- </span>
                            @endif
                        @else
                            @if ($library->date_end != null)
                                <span>- {{ date_format(date_create($library->date_end),"Y/m/d H:i") }}</span>
                            @endif
                        @endif
                        @if($library->use_classification == \App\Enums\UseClassificationEnum::USES && $library->now_post == 1)
                            <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                        @endif
                    </td>
                    <td>
                        @if ($library->use_classification == 1) <span>未使用</span> @else <span>使用</span> @endif
                    </td>
                    <td>
                        <span>{{$library->display_order}}</span>
                    </td>
                    <td>
                        <span>{{date_format(date_create($library->updated_at),"Y/m/d")}} {{ isset($user_updated_by) ? $user_updated_by->name : ''}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        var columns = ["no", "library_type", "title", "date_start", "use_classification", "display_order", "updated_at"];
        var index_old = 6;
        var view = 'library';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

