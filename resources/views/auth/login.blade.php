@extends('layouts.app')

@section('content')
    <div class="login-board login-content">
        <div class="card login-panel" style="background: #d5d5f4">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h3>システムログイン</h3>
                    <div class="form-group login-group">
                        <div>
                            <label for="login_id" class="login-label">{{ __('ユーザーID') }}</label>
                            <input id="login_id" type="text" style="font-size: 1.3rem"
                                   class="login-input form-control{{ $errors->has('login_id') ? ' is-invalid' : '' }}"
                                   name="login_id" value="{{ old('login_id') }}" required autofocus>

                            @if ($errors->has('login_id'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('login_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <br>
                        <div>
                            <label for="password" class="login-label">{{ __('パスワード') }}</label>
                            <input id="password" type="password"
                                   class="login-input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                            @endif
                        </div>
                        <br>
                        <select class="login-input form-control" name="access_authority">
                            @foreach(\App\Enums\AccessAuthority::toSelectArray() as $key => $text)
                                @if ($key === \App\Enums\AccessAuthority::USER)
                                    <option @if((int) old('access_authority') === $key) selected
                                            @endif value="{{ $key }}">サークル用ページにログイン
                                    </option>
                                @elseif($key === \App\Enums\AccessAuthority::ADMIN)
                                    <option @if((int) old('access_authority') === $key) selected
                                            @endif value="{{ $key }}">事務局用ページにログイン
                                    </option>
                                @else
                                    <option @if((int) old('access_authority') === $key) selected
                                            @endif value="{{ $key }}">Login to page for Admin System
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <br>
                        <button type="submit" class="btn btn-primary btn-login">
                            <em class="fa fa-lock"></em> {{ __('ログイン') }}
                        </button>
                    </div>
                    <div class="form-group login-group" style="position: relative">
                        <a class="login-link exclamation-mark" href="{{ URL::to('cannot-login')}}">ログインできない場合</a>
                        <a class="login-link exclamation-mark" href="{{ URL::to('send-question')}}">事務局へのお問合せ</a>
                    </div>
                </form>
            </div>
        </div>


        <div class="card login-notice">
            <span style="padding: 15px">事務局からのお知らせ</span>
            <ul class="login-list">
                <li>2019.01.15 ２月６～８日に東京ビッグサイトで開催される「ものづくりＡＩ／Ｉｏｔ展」に出展決定</li>
                <li>2019.01.01 Step1活動管理機能は４月より運用を開始します</li>
            </ul>
        </div>
    </div>
@endsection
