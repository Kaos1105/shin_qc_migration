@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('place', function ($trail) {
            $trail->parent('toppage');
            $trail->push('職場マスタ ',route('place.index'));
        }),
        Breadcrumbs::for('show', function ($trail) {
            $trail->parent('place');
            $trail->push('職場情報', route('place.index'));
        })
    }}

    {{ Breadcrumbs::render('show') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(isset($callback))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-add" href="{{ URL::to('/place/'.$place->id.'/edit') }}">修正</a>
            <a class="btn btn-add" id="btn-delete">削除</a>
            @if(isset($holdsort))
                <button class="btn btn-back" onclick="window.history.back()">戻る</button>
            @else
                <a class="btn btn-back" href="{{ URL::to('place'.session('placeKeys')) }}">戻る</a>
            @endif
        @endif
    </div>
    <div class="container-fluid">
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ \App\Enums\Common::show_id($place->id) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">職場名</td>
                    <td>
                        <span class="font-weight-bold">{{$place->place_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">所属部門</td>
                    <td>
                        <span><a class="a-black" href="{{ URL::to('department/'.$department->id.'?callback=yes') }}">{{$department->department_name }}</a></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">職場世話人</td>
                    <td>
                        <span><a class="a-black" href="{{ URL::to('user/'.$user->id.'?callback=yes') }}">@if(isset($user->position)) ({{ $user->position }}) @endif {{$user->name }}</a></span>
                    </td>
                </tr>

                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <span>{{$place->display_order }}</span>
                    </td>
                </tr>

                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <span class="line-brake-preserve">{{$place->note}}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        @if($place->statistic_classification == 1)
                            <span>集計しない</span>
                        @else
                            <span>集計対象とする</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        @if($place->use_classification == 1)
                            <span>使用しない</span>
                        @else
                            <span>使用する</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($place->created_at)) }} {{ $user_created_by_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($place->updated_at)) }} {{ $user_updated_by }}</span>
                    </td>
                </tr>
            </table>
    </div>
    {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['place.destroy', $place->id]]) !!}
    {!! Form::close() !!}

    <script>
        @if ($errors->has('delete_using'))
        alert('{{ $errors->first('delete_using')}}');
        @endif
        $("#btn-delete").click(function(){
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Place }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
