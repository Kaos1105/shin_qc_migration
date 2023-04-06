@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
            }),
       Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('掲示板',route('category.index'));
            }),
       Breadcrumbs::for('index', function ($trail) {
            $trail->parent('list');
            $trail->push('スレッド一覧', route('thread.index'));
            })
    }}

    {{ Breadcrumbs::render('index') }}

@endsection
@section('content')
    <div class="float-right btn-area">
        @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)
            <a class="btn btn-add" id="btn-register" href="{{ route('category.edit', [$category->id]) }}">修正</a>
        @endif
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('category.index') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($category)? \App\Enums\Common::show_id($category['id']) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">カテゴリ名</td>
                <td>
                    <span class="line-brake-preserve font-weight-bold">{{$category->category_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$category->note !!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($category->use_classification == 1 )  <span>使用しない</span>  @else <span>使用する</span> @endif
                </td>
            </tr>

            <tr>
                <td class="td-first">登録日</td>
                <td>
                    <span>{{ date_format(date_create($category->created_at),"Y/m/d H:i:s") }} {{$user_created_by_name}}</span>
                </td>
            </tr>


        </table>
    </div>

    <div class="container-fluid">
        <div class="float-right btn-area-list full-width position-relative" style="margin-top: 1rem">
            @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)
                <a class="btn btn-add float-right" style="margin-right: -15px"
                   href="{{ URL::to('thread/add/'.$category->id) }}">追加</a>
            @endif
        </div>
        @if(count($paginate) >0)
            @include('common.paginate')
            <table id="myTable" class="table-frame">
                <thead>
                <tr>
                    <th scope="col" style="width: 2%">No</th>
                    <th scope="col" style="width: 4%">通知</th>
                    <th scope="col" style="width: 30%">スレッド</th>
                    <th scope="col" style="width: 44%">説明</th>
                    <th scope="col" style="width: 10%">最終更新</th>
                    <th scope="col" style="width: 10%">トピック数</th>
                </tr>
                </thead>
                <tbody>
                <?php $stt = ($paginate->currentPage() - 1) * 20; ?>
                @foreach ($paginate as $thread)
                    <?php
                    $stt++;
                    if (!$thread->topic_count) {
                        $thread->topic_count = 0;
                    }
                    ?>
                    <tr>
                        <th scope="row" class="font-weight-normal">{{$stt}}</th>
                        <td>
                            @if ($thread->is_display ==1) <span>&#x2606</span> @else <span>&#x2605</span> @endif
                        </td>
                        <td>
                            <a class="a-black font-weight-bold" href="{{ URL::to('/topic?holdsort=yes', [$thread->id]) }}">
                                {{$thread->thread_name}}
                            </a>
                        </td>
                        <td>
                            <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!! $thread->note !!}</span></span>
                        </td>
                        <td>
                            {{ date_format(date_create($thread->updated_at),"Y/m/d H:i") }}
                        </td>
                        <td>{{$thread->topic_count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('common.paginate')
        @endif
    </div>
    <script>
        var columns = ["no", "is_display", "thread_name", "note", "updated_at", "topic_count"];
        var index_old = 5;
        var view = '';
        var sortInit = true;
        $(document).ready(sortAndFilter(columns, index_old, view, sortInit));
    </script>
@endsection
