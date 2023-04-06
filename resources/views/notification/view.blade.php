@extends('layouts.app')

@section('breadcrumbs')
    {{
    Breadcrumbs::for('toppage', function ($trail) {
        $trail->push('トップページ', route('toppageoffice'));
        }),
    Breadcrumbs::for('list', function ($trail) {
        $trail->parent('toppage');
        $trail->push('お知らせ管理',route('notification.index'));
        }),
    Breadcrumbs::for('view', function ($trail, $notification) {
        $trail->parent('list', $notification);
        $trail->push('表示', route('notification.show', $notification->id));
        })
    }}

    {{ Breadcrumbs::render('view', $notification) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-delete">削除</button>
        <a class="btn btn-add" id="btn-copy" href="{{ route('notification.copy', [$notification->id]) }}">複写</a>
        <a class="btn btn-add" id="btn-edit" href="{{ route('notification.edit', [$notification->id]) }}">修正</a>
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('notification.index') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($notification)? \App\Enums\Common::show_id($notification->id) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ページ区分</td>
                <td>
                    @if ($notification->notification_classify == 1) <span>ログインページ</span> @else <span>トップページ</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">メッセージ</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$notification->message!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">掲載期間</td>
                <td>
                    <span>
                        @if(isset($notification->date_start))(開始) {{date_format(date_create($notification->date_start),"Y/m/d H:i")}} @endif
                        @if(isset($notification->date_end)) (終了) {{date_format(date_create($notification->date_end),"Y/m/d H:i")}} @endif
                    </span>
                    @if($notification->use_classification == \App\Enums\UseClassificationEnum::USES && $notification->now_post == 1)
                        <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$notification->display_order}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($notification->use_classification == 1) <span>使用しない</span> @else <span>使用する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{date_format(date_create($notification->updated_at),"Y/m/d H:i:s")}} {{$notification->name}}</span>
                </td>
            </tr>
        </table>
        </form>
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['notification.destroy', $notification->id]]) !!}
        {!! Form::close() !!}
    </div>
    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Notification }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
