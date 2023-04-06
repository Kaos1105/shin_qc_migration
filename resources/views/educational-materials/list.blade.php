@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
           }),
       Breadcrumbs::for('index', function ($trail) {
           $trail->parent('toppage');
           $trail->push('QC教育資料',route('educational-materials.index'));
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
        <a class="btn btn-add" href="{{ route('educational-materials.create') }}">追加</a>
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
            @foreach ($paginate as $educational_material)
                <?php
                $stt++;
                $user_updated_by = DB::table('users')->where('id', $educational_material->updated_by)->first();
                ?>
                <tr>
                    <th class="font-weight-normal">{{$stt}}</th>
                    <td>
                        <span>{{$educational_material->educational_materials_type}}</span>
                    </td>
                    <td>
                        <a class="a-black font-weight-bold"
                           href="{{ URL::to('/educational-materials?holdsort=yes', [$educational_material->id]) }}">
                            <span class="line-brake-preserve">{{$educational_material->title}}</span>
                        </a>
                    </td>
                    <td>
                        @if ($educational_material->date_start != null)
                            <span>{{ date_format(date_create($educational_material->date_start),"Y/m/d H:i") }}</span>
                            @if ($educational_material->date_end != null)
                                <span>- {{ date_format(date_create($educational_material->date_end),"Y/m/d H:i") }}</span>
                            @else <span>- </span>
                            @endif
                        @else
                            @if ($educational_material->date_end != null)
                                <span>- {{ date_format(date_create($educational_material->date_end),"Y/m/d H:i") }}</span>
                            @endif
                        @endif
                        @if($educational_material->use_classification == \App\Enums\UseClassificationEnum::USES && $educational_material->now_post == 1)
                            <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                        @endif
                    </td>
                    <td>
                        @if ($educational_material->use_classification == 1) <span>未使用</span> @else <span>使用</span> @endif
                    </td>
                    <td>
                        <span>{{$educational_material->display_order}}</span>
                    </td>
                    <td>
                        <span>{{date_format(date_create($educational_material->updated_at),"Y/m/d")}} {{ isset($user_updated_by) ? $user_updated_by->name : ''}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        var columns = ["no", "educational_materials_type", "title", "date_start", "use_classification", "display_order", "updated_at"];
        var index_old = 6;
        var view = 'educational-materials';
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
@endsection

