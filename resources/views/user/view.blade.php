@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('user', function ($trail) {
            $trail->parent('toppage');
            $trail->push('利用者マスタ',route('user.index'));
        }),
        Breadcrumbs::for('show', function ($trail) {
            $trail->parent('user');
            $trail->push('利用者情報', route('user.index'));
        })
    }}

    {{ Breadcrumbs::render('show') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(isset($callback))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-add" href="/user/{{$user->id }}/edit">修正</a>
            <a class="btn btn-add" id="btn-delete-user">削除</a>
            @if(isset($holdsort))
                <button class="btn btn-back" onclick="window.history.back()">戻る</button>
            @else
                <a class="btn btn-back" href="{{ URL::to('/user'.session('userKeys')) }}">戻る</a>
            @endif
        @endif
    </div>
    <div class="container-fluid">
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ \App\Enums\Common::show_id($user->id) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">利用者コード</td>
                    <td>
                        <span>{{$user->user_code }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">氏名</td>
                    <td>
                        <span class="font-weight-bold">{{$user->name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">役割区分</td>
                    <td>
                        @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_OFFICER)
                            <span>推進責任者</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_COMMITTEE)
                            <span>推進委員</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::DEPARTMENT_MANAGER)
                            <span>部門責任者</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::DEPARTMENT_CARETAKER)
                            <span>部門世話人</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::PLACE_CARETAKER)
                            <span>職場世話人</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::CIRCLE_PROMOTER)
                            <span>サークル推進者</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::MEMBER)
                            <span>メンバー</span>
                        @elseif ($user->role_indicator == \App\Enums\RoleIndicatorEnum::EXECUTIVE_DIRECTOR)
                            <span>事務局長</span>
                        @else
                            <span>事務局員</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">役職</td>
                    <td>
                        <span>{{$user->position }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <span>{{$user->display_order }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">メールアドレス</td>
                    <td>
                        <span>{{$user->email }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">電話番号</td>
                    <td>
                        <span>{{$user->phone }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ログインＩＤ</td>
                    <td>
                        <span>{{$user->login_id }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">パスワード</td>
                    <td>
                        <span id="password-encrypt">{{  str_repeat("*", strlen(base64_decode($user->password_encrypt))) }}</span>   <i id="show-password" class="fa fa-eye-slash" aria-hidden="true"></i>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">アクセス権限</td>
                    <td>
                        @if($user->access_authority == \App\Enums\AccessAuthority::USER)
                            <span>一般利用者</span>
                        @elseif($user->access_authority == \App\Enums\AccessAuthority::ADMIN)
                            <span>管理者</span>
                        @else
                            <span>システム担当者</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <span class="line-brake-preserve">{{$user->note}}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        @if($user->statistic_classification == 1)
                            <span>集計しない</span>
                        @else
                            <span>集計対象とする</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        @if($user->use_classification == 1)
                            <span>使用しない</span>
                        @else
                            <span>使用する</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($user->created_at)) }} {{ $user_created_by_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($user->updated_at)) }} {{ $user_updated_by }}</span>
                    </td>
                </tr>
            </table>
    </div>
    {!! Form::open(['id'=> 'form-delete-user','method' => 'Delete', 'route' => ['user.destroy', $user->id]]) !!}
    {!! Form::close() !!}

    <script>
        $("#btn-delete-user").click(function(){
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_User }}");
            if (r == true) {
                $("#form-delete-user").submit()
            }
        });

        @if ($errors->has('cannotDelete'))
        alert('{{ $errors->first('cannotDelete')}}');
                @endif

        var timeOut = 0;
        $('#show-password').on('mousedown touchstart', function(e) {
            $(this).removeClass('fa-eye-slash');
            $(this).addClass('fa-eye');
            $("#password-encrypt" ).html('{{  base64_decode($user->password_encrypt) }}');
            timeOut = setInterval(function(){
            }, 100);
        }).bind('mouseup mouseleave touchend', function() {
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
            $("#password-encrypt" ).html('{{  str_repeat("*", strlen(base64_decode($user->password_encrypt))) }}');
            clearInterval(timeOut);
        });
    </script>
@endsection
