@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('書庫管理',route('library.index'));
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
        <a class="btn btn-add" id="btn-edit" href="{{ route('library.edit', [$library->id]) }}">修正</a>
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('library.index') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($library)? \App\Enums\Common::show_id($library->id) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">文書区分</td>
                <td>
                    <span>{{$library->library_type}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">タイトル</td>
                <td>
                    <span class="font-weight-bold line-brake-preserve">{{$library->title}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ファイル</td>
                <td>
                    <span>{{$fileNameDisp}}</span>
                    <a class="btn btn-secondary" style="color: #ffffff"
                       href='{{ route('library.download', [$library->id]) }}'>&#11123; ダウンロード</a>
                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span class="line-brake-preserve">{{$library->note}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">掲載期間</td>
                <td>
                        <span>
                            @if(isset($library->date_start))
                                開始 {{date_format(date_create($library->date_start),"Y/m/d H:i")}} @endif
                            @if(isset($library->date_end))
                                終了 {{date_format(date_create($library->date_end),"Y/m/d H:i")}} @endif
                        </span>
                    @if($library->use_classification == \App\Enums\UseClassificationEnum::USES && $library->now_post == 1)
                        <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$library->display_order}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($library->use_classification == 1) <span>使用しない</span> @else <span>使用する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{date_format(date_create($library->updated_at),"Y/m/d H:i:s")}} {{$library->name}}</span>
                </td>
            </tr>
        </table>
        </form>
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['library.destroy', $library->id]]) !!}
        {!! Form::close() !!}
    </div>
    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Library }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });

        @if ($errors->has('download_error'))
        alert('{{ $errors->first('download_error')}}');
        @endif
    </script>
@endsection
