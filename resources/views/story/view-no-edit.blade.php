@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            if(session('toppage') == \App\Enums\AccessAuthority::ADMIN) {
                $trail->push('ストーリー登録（一覧）',route('story.getList'));
            } else {
                $trail->push('ストーリー登録（一覧）');
            }
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
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($story)? \App\Enums\Common::show_id($story['id']) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ストーリー区分</td>
                <td>
                    <span>{{$story->story_classification}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">ストーリー名</td>
                <td>
                    <span class="font-weight-bold line-brake-preserve">{{$story->story_name}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span class="line-brake-preserve">{{$story->description}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$story->display_order}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if ($story->use_classification == 1) <span>使用しない</span> @else <span>使用する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{date_format(date_create($story->updated_at),"Y/m/d H:i:s")}} {{$user_updated_by}}</span>
                </td>
            </tr>
        </table>
        </form>
        {!! Form::open(['id'=> 'form-delete','method' => 'POST', 'route' => ['story.postDelete', $story->id]]) !!}
        {!! Form::close() !!}
    </div>
    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Story }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
        @if ($errors->has('delete_error'))
        alert('{{ $errors->first('delete_error')}}');
        @endif

    </script>
@endsection
