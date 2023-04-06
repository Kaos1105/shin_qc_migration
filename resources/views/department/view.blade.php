@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('department', function ($trail) {
            $trail->parent('toppage');
            $trail->push('部門マスタ',route('department.index'));
        }),
        Breadcrumbs::for('show', function ($trail) {
            $trail->parent('department');
            $trail->push('部門情報', route('department.index'));
        })
    }}

    {{ Breadcrumbs::render('show') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(isset($callback))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-add" href="{{ URL::to('/department/'.$department->id.'/edit') }}">修正</a>
            <a class="btn btn-add" id="btn-delete">削除</a>
            @if(isset($holdsort))
                <button class="btn btn-back" onclick="window.history.back()">戻る</button>
            @else
                <a class="btn btn-back" href="{{ URL::to('department'.session('departmentKeys')) }}">戻る</a>
            @endif
        @endif
    </div>
    <div class="container-fluid">
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ \App\Enums\Common::show_id($department->id) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">部門名</td>
                    <td>
                        <span class="font-weight-bold">{{$department->department_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">部門責任者</td>
                    <td>
                        <span><a class="a-black" href="{{ URL::to('user/'.$user_manager->id.'?callback=yes') }}">@if(isset($user_manager->position)) ({{ $user_manager->position }}) @endif {{ $user_manager->name }}</a></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">部門世話人</td>
                    <td>
                        <span><a class="a-black" href="{{ URL::to('user/'.$user_caretaker->id.'?callback=yes') }}">@if(isset($user_caretaker->position)) ({{$user_caretaker->position }}) @endif {{$user_caretaker->name }}</a></span>
                    </td>
                </tr>

                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <span>{{$department->display_order }}</span>
                    </td>
                </tr>

                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <span class="line-brake-preserve">{{$department->note}}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        @if($department->statistic_classification == 1)
                            <span>集計しない</span>
                        @else
                            <span>集計対象とする</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        @if($department->use_classification == 1)
                            <span>使用しない</span>
                        @else
                            <span>使用する</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($department->created_at)) }} {{ $user_created_by_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($department->updated_at)) }} {{ $user_updated_by }}</span>
                    </td>
                </tr>
            </table>
    </div>
    {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['department.destroy', $department->id]]) !!}
    {!! Form::close() !!}

    <script>
        @if ($errors->has('delete_using'))
        alert('{{ $errors->first('delete_using')}}');
        @endif
        $("#btn-delete").click(function(){
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Department }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
