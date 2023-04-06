@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('教育資料',route('educational-materials.index'));
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
        <a class="btn btn-add" id="btn-edit" href="{{ route('educational-materials.edit', [$educational_material->id]) }}">修正</a>
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ route('educational-materials.index') }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($educational_material)? \App\Enums\Common::show_id($educational_material->id) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">文書区分</td>
                <td>
                    <span>{{$educational_material->educational_materials_type}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">タイトル</td>
                <td>
                    <span class="font-weight-bold line-brake-preserve">{{$educational_material->title}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ファイル</td>
                <td>
                    <span>{{$fileNameDisp}}</span>
                    <a class="btn btn-secondary" style="color: #ffffff"
                       href='{{ route('educational-materials.download', [$educational_material->id]) }}'>&#11123; ダウンロード</a>
                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span class="line-brake-preserve">{{$educational_material->note}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">掲載期間</td>
                <td>
                        <span>
                            @if(isset($educational_material->date_start))
                                開始 {{date_format(date_create($educational_material->date_start),"Y/m/d H:i")}} @endif
                            @if(isset($educational_material->date_end))
                                終了 {{date_format(date_create($educational_material->date_end),"Y/m/d H:i")}} @endif
                        </span>
                    @if($educational_material->use_classification == \App\Enums\UseClassificationEnum::USES && $educational_material->now_post == 1)
                        <span>{{\App\Enums\StaticConfig::$Now_Post}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$educational_material->display_order}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($educational_material->use_classification == 1) <span>使用しない</span> @else <span>使用する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{date_format(date_create($educational_material->updated_at),"Y/m/d H:i:s")}} {{$educational_material->name}}</span>
                </td>
            </tr>
        </table>
        </form>
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['educational-materials.destroy', $educational_material->id]]) !!}
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
