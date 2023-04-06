@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
           }),
       Breadcrumbs::for('index', function ($trail) {
           $trail->parent('toppage');
           $trail->push('ホームページリンク管理',route('homepage.index'));
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
        <a class="btn btn-add" href="{{ route('homepage.create') }}">追加</a>
        <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">区分</th>
                <th scope="col">タイトル</th>
                <th scope="col">掲載期間</th>
                <th scope="col">使用区分</th>
                <th scope="col">表示順</th>
                <th scope="col">最終更新 更新</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; ?>
            @foreach ($paginate as $homepage)
                <?php
                $stt++;
                $user_updated_by = DB::table('users')->where('id', $homepage->updated_by)->first();
                ?>
                <tr>
                    <th class="font-weight-normal">{{$stt}}</th>
                    <td>
                        <span>{{$homepage->classification}}</span>
                    </td>
                    <td>
                        <a class="a-black font-weight-bold"
                           href="{{ URL::to('/homepage?holdsort=yes', [$homepage->id]) }}">
                            <span class="line-brake-preserve">{{$homepage->title}}</span>
                        </a>
                    </td>
                    <td>
                        @if ($homepage->date_start != null)
                            <span>{{ date_format(date_create($homepage->date_start),"Y/m/d H:i") }}</span>
                            @if ($homepage->date_end != null)
                                <span>- {{ date_format(date_create($homepage->date_end),"Y/m/d H:i") }}</span>
                            @else <span>- </span>
                            @endif
                        @else
                            @if ($homepage->date_end != null)
                                <span>- {{ date_format(date_create($homepage->date_end),"Y/m/d H:i") }}</span>
                            @endif
                        @endif
                        @if($homepage->use_classification == \App\Enums\UseClassificationEnum::USES && $homepage->now_post == 1)
                            <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                        @endif
                    </td>
                    <td>
                        @if ($homepage->use_classification == 1) <span>未使用</span> @else <span>使用</span> @endif
                    </td>
                    <td>
                        <span>{{$homepage->display_order}}</span>
                    </td>
                    <td>
                        <span>{{isset($homepage->updated_at) ? date_format(date_create($homepage->updated_at),"Y/m/d") : null}} {{ isset($user_updated_by) ? $user_updated_by->name : null}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        var columns = ["no", "classification", "title", "date_start", "use_classification", "display_order", "updated_at"];
        var index_old = 6;
        var view = 'homepage';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

