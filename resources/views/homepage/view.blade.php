@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ホームページリンク管理',route('homepage.index'));
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
        <button class="btn btn-add" id="btn-delete">削除</button>
        <a class="btn btn-add" id="btn-edit" href="{{ route('homepage.edit', [$homepage->id]) }}">修正</a>
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('homepage.index') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($homepage)? \App\Enums\Common::show_id($homepage->id) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">リンク区分</td>
                <td>
                    <span>{{$homepage->classification}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">タイトル</td>
                <td>
                    <span class="font-weight-bold line-brake-preserve">{{$homepage->title}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">URL</td>
                <td>
                    <span>{{$homepage->url}}</span>
                    @if(strpos( $homepage->url, 'http://' ) !== false || strpos( $homepage->url, 'https://' ) !== false)
                        <a class="btn btn-secondary" style="color: #ffffff" href='{{$homepage->url}}' target="_blank">&#10140;
                            表示</a>
                    @else
                        <a class="btn btn-secondary" style="color: #ffffff" href='http://{{$homepage->url}}'
                           target="_blank">&#10140; 表示</a>
                    @endif

                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span class="line-brake-preserve">{{$homepage->note}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">掲載期間</td>
                <td>
                        <span>
                            @if(isset($homepage->date_start))
                                開始 {{date_format(date_create($homepage->date_start),"Y/m/d H:i")}} @endif
                            @if(isset($homepage->date_end))
                                終了 {{date_format(date_create($homepage->date_end),"Y/m/d H:i")}} @endif
                        </span>
                    @if($homepage->use_classification == \App\Enums\UseClassificationEnum::USES && $homepage->now_post == 1)
                        <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$homepage->display_order}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($homepage->use_classification == 1) <span>使用しない</span> @else <span>使用する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{date_format(date_create($homepage->created_at),"Y/m/d H:i:s")}} {{$homepage->name}}</span>
                </td>
            </tr>
        </table>
        </form>
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['homepage.destroy', $homepage->id]]) !!}
        {!! Form::close() !!}
    </div>
    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Homepage }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
