@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('circle', function ($trail) {
            $trail->parent('toppage');
            $trail->push('サークル',route('circle.index'));
        }),
        Breadcrumbs::for('show', function ($trail) {
            $trail->parent('circle');
            $trail->push('サークル情報');
        })
    }}

    {{ Breadcrumbs::render('show') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(isset($_GET['callback']))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-add" href="{{ URL::to('/circle/'.$circle->id.'/edit') }}">修正</a>
            <a class="btn btn-add" id="btn-delete">削除</a>
            @if(isset($holdsort))
                <button class="btn btn-back" onclick="window.history.back()">戻る</button>
            @else
                <a class="btn btn-back" href="{{ URL::to('circle'.session('circleKeys')) }}">戻る</a>
            @endif
        @endif
    </div>
    <div class="container-fluid bottom-fix">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ \App\Enums\Common::show_id($circle->id) }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">登録番号</td>
                <td>
                    <span>{{$circle->circle_code }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">所属職場</td>
                <td>
                    <span>
                        <a class="a-black" href="{{ URL::to('department/'.$department->id.'?callback=yes') }}">{{$department->department_name }}</a>
                        <a class="a-black" href="{{ URL::to('place/'.$place->id.'?callback=yes') }}"> {{ $place->place_name }}</a>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル名</td>
                <td>
                    <span class="font-weight-bold">{{$circle->circle_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル推進者</td>
                <td>
                    <?php
                        $user = \App\Models\User::find($circle->user_id);
                    ?>
                    <span>
                        <a class="a-black" href="{{ URL::to('user/'.$user->id.'?callback=yes') }}">({{ $place->place_name }}) @if(isset($user->position)) ({{ $user->position }}) @endif {{ $user->name }}</a>
                        <a href="mailto:{{ $user->email }}&subject=subject&body=body"><img src="{{ asset('images/email.png') }}" alt="email" /> </a>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$circle->display_order }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">登録日</td>
                <td>
                    <span>{{ date("Y/m/d", strtotime($circle->date_register)) }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">最終活動日</td>
                <td>
                    @if(isset($activity_lastest))<span>{{ date("Y/m/d", strtotime($activity_lastest->date_execution)) }}  ＊<a class="a-black" href="{{ URL::to('activity/'.$activity_lastest->id.'?callback=yes') }}">活動シート</a></span>@endif
                </td>
            </tr>
            <tr>
                <td class="td-first">備考</td>
                <td>
                    <span>{{ $circle->note }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">集計区分</td>
                <td>
                    @if($circle->statistic_classification == 1)
                        <span>集計しない</span>
                    @else
                        <span>集計対象とする</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    @if($circle->use_classification == 1)
                        <span>使用しない</span>
                    @else
                        <span>使用する</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">新規登録日</td>
                <td>
                    <span>{{ date("Y/m/d H:i:s", strtotime($circle->created_at)) }} {{ $user_created_by_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{ date("Y/m/d H:i:s", strtotime($circle->updated_at)) }} {{ $user_updated_by }}</span>
                </td>
            </tr>
        </table>
        <div class="float-right btn-area full-width position-relative" style="margin-top: 1rem">
            <span class="absolute" style="bottom: 0">登録メンバー</span>
            <div class="float-right">
                @if(!isset($_GET['callback']))
                    <a class="btn btn-add" style="margin-right: -15px" href="{{ URL::to('member/'.$circle->id.'/create') }}">メンバ追加</a>
                @endif
            </div>
        </div>
        <table class="table-member-list" border="1">
            <thead>
            <tr>
                <td style="width: 5%">No</td>
                <td style="width: 20%">メンバ名</td>
                <td style="width: 20%">所属グループ</td>
                <td style="width: 20%">役割</td>
                <td>備考</td>
            </tr>
            </thead>
            <tbody>
            <?php $sttMembers = 1; ?>
            @foreach($list_member as $item)
            <?php
            $user =  App\Models\User::find($item->user_id);
            ?>
            <tr>
                <td class="text-center">
                    @if($item->is_leader == 2) <span class="d-none">{{$sttMembers++}}</span><span>L</span>
                    @else <span>{{ $sttMembers++ }}</span>
                    @endif
                </td>
                <td>
                    <a class="a-black" href="{{ URL::to('member/'.$item->id.'/edit') }}">@if(isset($user->position)) ({{ $user->position }}) @endif {{ $user->name }}</a>
                </td>
                <td>{{ $item->department }}</td>
                <td>{{ $item->classification }}</td>
                <td><span class="line-brake-preserve">{{$item->note}}</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['circle.destroy', $circle->id]]) !!}
    {!! Form::close() !!}

    <script>
        @if ($errors->has('delete_using'))
        alert('{{ $errors->first('delete_using')}}');
        @endif
        $("#btn-delete").click(function(){
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Circle }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
