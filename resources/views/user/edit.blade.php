@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', URL::to('top-page'));
        }),
        Breadcrumbs::for('user', function ($trail) {
            $trail->parent('toppage');
            $trail->push('利用者マスタ', URL::to('user'));
        }),
        Breadcrumbs::for('edit', function ($trail) {
            $trail->parent('user');
            $trail->push('修正');
        })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ URL::to('user/'.$user->id) }}">戻る</a>
    </div>
    <div class="container-fluid bottom-fix">
        {{ Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PUT', 'id' => 'form-register')) }}
        @csrf
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($user)? \App\Enums\Common::show_id($user['id']) : null }}</span>
                    <input type="hidden" name="id" value="{{ old('id', isset($user)? $user['id'] : null)}}" />
                </td>
            </tr>
            <tr>
                <td class="td-first">利用者コード</td>
                <td>
                    <input id="user_code" type="text" class="qc-form-input input-s" name="user_code" value="{{ old('user_code', isset($user)?$user['user_code']:null)}}" autofocus>
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('user_code'))
                        <span class="qc-form-error-message">{{ $errors->first('user_code') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">氏名</td>
                <td>
                    <input id="name" type="text" class="qc-form-input input-m" name="name" value="{{ old('name', isset($user)?$user['name']:null)}}">
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
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_OFFICER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::PROMOTION_OFFICER }}" /><span>推進責任者</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_COMMITTEE) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::PROMOTION_COMMITTEE }}" /><span>推進委員</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::DEPARTMENT_MANAGER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::DEPARTMENT_MANAGER }}" /><span>部門責任者</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::DEPARTMENT_CARETAKER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::DEPARTMENT_CARETAKER }}" /><span>部門世話人</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::PLACE_CARETAKER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::PLACE_CARETAKER }}" /><span>職場世話人</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::CIRCLE_PROMOTER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::CIRCLE_PROMOTER }}" /><span>サークル推進者</span>
                        </label>

                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::MEMBER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::MEMBER }}" /><span>メンバー</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::EXECUTIVE_DIRECTOR) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::EXECUTIVE_DIRECTOR }}" /><span>事務局長</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_indicator"
                                   @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::OFFICE_WORKER) checked @endif
                                   value="{{ \App\Enums\RoleIndicatorEnum::OFFICE_WORKER }}" /><span>事務局員</span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-first">役職</td>
                <td>
                    <input id="position" type="text" class="qc-form-input input-m" value="{{ old('position', isset($user)?$user['position']:null)}}" name="position">
                    @if ($errors->has('position'))
                        <span class="qc-form-error-message">{{ $errors->first('position') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <input id="display_order" type="number" class="qc-form-input input-s" value="{{ old('display_order', isset($user)?$user['display_order']:null)}}" name="display_order">
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('display_order'))
                        <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">メールアドレス</td>
                <td>
                    <input id="email" type="email" class="qc-form-input qc-form-input-50" name="email" value="{{ old('email', isset($user)?$user['email']:null)}}">
                    @if ($errors->has('email'))
                        <span class="qc-form-error-message">{{ $errors->first('email') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">電話番号</td>
                <td>
                    <input id="phone" type="text" class="qc-form-input input-m" name="phone" value="{{ old('phone', isset($user)?$user['phone']:null)}}">
                    @if ($errors->has('phone'))
                        <span class="qc-form-error-message">{{ $errors->first('phone') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">ログインＩＤ</td>
                <td>
                    <input id="login_id" type="text" class="qc-form-input input-m" name="login_id" value="{{ old('login_id', isset($user)?$user['login_id']:null)}}">
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('login_id'))
                        <span class="qc-form-error-message">{{ $errors->first('login_id') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">パスワード</td>
                <td>
                    <input id="password" type="password" class="qc-form-input input-m" name="password" value="{{ old('password', isset($user)? base64_decode($user->password_encrypt) : null)}}">
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
                                   @if($user->access_authority == \App\Enums\AccessAuthority::USER) checked @endif value="{{ \App\Enums\AccessAuthority::USER }}"><span>一般利用者</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="access_authority2" name="access_authority"
                                   @if($user->access_authority == \App\Enums\AccessAuthority::ADMIN) checked @endif value="{{ \App\Enums\AccessAuthority::ADMIN }}"><span>管理者</span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-first">備考</td>
                <td>
                    <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note">{{ old('note', isset($user)?$user['note']:null)}}</textarea>
                </td>
            </tr>
            <tr>
                <td class="td-first">集計区分</td>
                <td>
                    <div class="qc-form-radio-group">
                        <label class="form-check-label" >
                            <input type="radio" class="form-check-input" id="statistic_classification1" name="statistic_classification"
                                   @if($user->statistic_classification == 1) checked @endif value="1"><span>集計しない</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="statistic_classification2" name="statistic_classification"
                                   @if($user->statistic_classification == 2) checked @endif  checked value="2"><span>集計対象とする</span>
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
                                   @if($user->use_classification == 1) checked @endif value="1"><span>使用しない</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="use_classification2" name="use_classification"
                                   @if($user->use_classification == 2) checked @endif value="2"><span>使用する</span>
                        </label>

                    </div>
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
        {{ Form::close() }}
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit()
        });
    </script>
@endsection
