@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ＱＡ画面登録（一覧）',route('qa.getList'));
        }),
         Breadcrumbs::for('view', function ($trail) {
            $trail->parent('list');
            $trail->push('表示');
        })
    }}

    {{ Breadcrumbs::render('view') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if($deletable)
            <button class="btn btn-add" id="btn-delete">削除</button>
        @else
            <button class="btn btn-add" disabled>削除</button>
        @endif
        <a class="btn btn-add" id="btn-edit" href="{{ route('qa.getEdit', [$qa->id]) }}">修正</a>
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('qa.getList') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($qa)? \App\Enums\Common::show_id($qa['id']) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ストーリー</td>
                <td>
                    <span>{{$story->story_name}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">画面番号</td>
                <td>
                    <span class="font-weight-bold">{{$qa->screen_id}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">タイトル</td>
                <td>
                    {{$qa->title}}
                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$qa->note!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$qa->display_order}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($qa->use_classification == 1) <span>使用しない</span> @else <span>使用する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{date_format(date_create($qa->updated_at),"Y/m/d H:i:s")}} {{$user_updated_by}}</span>
                </td>
            </tr>
        </table>
        {!! Form::open(['id'=> 'form-delete','method' => 'POST', 'route' => ['qa.postDelete', $qa->id]]) !!}
        {!! Form::close() !!}
    </div>
    <div class="container-fluid" style="margin-top: 3rem">
        @include('.qa.qa-multi-form')
    </div>
    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_QA }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
