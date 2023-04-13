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
        Breadcrumbs::for('add', function ($trail) {
            $trail->parent('user');
            $trail->push('新規登録', route('user.create'));
        })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid bottom-fix">
        <form id="form-register" method="POST" action="{{ action('UserController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">利用者コード</td>
                    <td>
                        <input id="user_code" type="text" class="qc-form-input input-s" name="user_code" value="{{ old('user_code') }}" autofocus>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('user_code'))
                            <span class="qc-form-error-message">{{ $errors->first('user_code') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">氏名</td>
                    <td>
                        <input id="name" type="text" class="qc-form-input input-m" name="name" value="{{ old('name') }}" required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('name'))
                            <span class="qc-form-error-message">{{ $errors->first('name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">役割区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            @foreach(\App\Enums\RoleIndicatorEnum::asSelectArray() as $key => $text)
                                @if($key == \App\Enums\RoleIndicatorEnum::PROMOTION_OFFICER)
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>推進責任者</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::PROMOTION_COMMITTEE)
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }} /><span>推進委員</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::DEPARTMENT_MANAGER)
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>部門責任者</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::DEPARTMENT_CARETAKER)
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>部門世話人</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::PLACE_CARETAKER)
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>職場世話人</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::CIRCLE_PROMOTER)
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>サークル推進者</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::MEMBER)
                                    <label class="form-check-label" for="{{'role_member'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               checked value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>メンバー</span>
                                    </label>
                                @elseif ($key == \App\Enums\RoleIndicatorEnum::EXECUTIVE_DIRECTOR)
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>事務局長</span>
                                    </label>
                                @else
                                    <label class="form-check-label" for="{{'role_indicator'.$loop->iteration }}">
                                        <input type="radio" class="form-check-input" id="{{ 'role_indicator'.$loop->iteration }}" name="role_indicator"
                                               value="{{ $key }}" {{ (old("role_indicator") == $key ? "checked":"") }}/><span>事務局員</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">役職</td>
                    <td>
                        <input id="position" type="text" class="qc-form-input input-m" value="{{ old('position') }}" name="position">
                        @if ($errors->has('position'))
                            <span class="qc-form-error-message">{{ $errors->first('position') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input id="display_order" type="number" class="qc-form-input input-s" value="{{ old('display_order', \App\Enums\StaticConfig::$Default_Display_Order) }}" name="display_order">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">メールアドレス</td>
                    <td>
                        <input id="email" type="email" class="qc-form-input input-l" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="qc-form-error-message">{{ $errors->first('email') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">電話番号</td>
                    <td>
                        <input id="phone" type="text" class="qc-form-input input-m" name="phone" value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            <span class="qc-form-error-message">{{ $errors->first('phone') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ログインＩＤ</td>
                    <td>
                        <input id="login_id" type="text" class="qc-form-input qc-form-input-20" name="login_id" value="{{ old('login_id') }}">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('login_id'))
                            <span class="qc-form-error-message">{{ $errors->first('login_id') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">パスワード</td>
                    <td>
                        <input id="password" type="password" class="qc-form-input qc-form-input-20" name="password">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('password'))
                            <span class="qc-form-error-message">{{ $errors->first('password') }}</span>
                            </span>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td class="td-first">アクセス権限</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="access_authority1" name="access_authority"
                                       checked value="{{ \App\Enums\AccessAuthority::USER }}" {{ (old("access_authority") == \App\Enums\AccessAuthority::USER ? "checked":"") }}><span>一般利用者</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="access_authority2" name="access_authority"
                                       value="{{ \App\Enums\AccessAuthority::ADMIN }}" {{ (old("access_authority") == \App\Enums\AccessAuthority::ADMIN ? "checked":"") }}><span>管理者</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note" >{{ old('note') }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label" >
                                <input type="radio" class="form-check-input" id="statistic_classification1" name="statistic_classification"
                                       value="1" {{ (old("statistic_classification") == 1 ? "checked":"") }}><span>集計しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="statistic_classification2" name="statistic_classification"
                                       checked value="2" {{ (old("statistic_classification") == 2 ? "checked":"") }}><span>集計対象とする</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1" name="use_classification"
                                       value="1" {{ (old("use_classification") == 1 ? "checked":"") }}><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2" name="use_classification"
                                       checked value="2" {{ (old("use_classification") == 2 ? "checked":"") }}><span>使用する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span></span>
                        <input id="created_at" type="hidden" name="created_at">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span></span>
                        <input id="updated_at" type="hidden" name="updated_at">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit()
        });
    </script>
@endsection
