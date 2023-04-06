@extends('layouts.app')

@section('content')
    <div class="login-board login-content">
        <div class="card login-panel" style="background: #d5d5f4">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h3>システムログイン</h3>
                    @if ($errors->has('incorrect'))
                        <span class="qc-form-error-message"
                              style="font-size: 12px">{{ $errors->first('incorrect') }}</span>
                    @endif
                    <div class="form-group login-group">
                        <div>
                            <label for="login_id" class="login-label">{{ __('ユーザーID') }}</label>
                            @if ($errors->has('login_id'))
                                <span class="qc-form-error-message float-right">{{ $errors->first('login_id') }}</span>
                            @endif
                            <input id="login_id" type="text" style="font-size: 1.3rem"
                                   class="qc-form-input-login form-control" name="login_id"
                                   value="{{ old('login_id') }}" autofocus>
                        </div>
                        <br>
                        <div>
                            <label for="password" class="login-label">{{ __('パスワード') }}</label>
                            @if ($errors->has('password'))
                                <span class="qc-form-error-message float-right">{{ $errors->first('password') }}</span>
                            @endif
                            <input id="password" type="password" class="qc-form-input-login form-control"
                                   name="password">
                        </div>
                        <br>
                        <select class="login-input form-control" name="access_authority">
                            @foreach(\App\Enums\AccessAuthority::toSelectArray() as $key => $text)
                                @if ($key === \App\Enums\AccessAuthority::USER)
                                    <option @if((int) old('access_authority') === $key) selected
                                            @endif value="{{ $key }}">サークル用ページにログイン
                                    </option>
                                @else($key === \App\Enums\AccessAuthority::Admin)
                                    <option @if((int) old('access_authority') === $key) selected
                                            @endif value="{{ $key }}">事務局用ページにログイン
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
            <ul class="login-list"><span>事務局からのお知らせ</span>
                @if(isset($notification))
                    @foreach($notification as $item)
                        <li>{{date_format(date_create($item->date_start),"Y/m/d")}} {{$item->message}}</li>
                    @endforeach
                @endif
            </ul>
        </div>

        <div class="card login-notice" style="margin-top: 20px">
            <ul class="login-list"><span>システム概要（三つの特徴）</span>
                <li>① 年間活動計画の作成やサークル活動の進捗管理など、ＱＣ事務局が困っていた問題を一気に解決する統合管理機能 </li>
                <li>② 活動報告書に、特性要因図やパレート図などのＱＣ７つ道具（新ＱＣ７つ道具）を簡単に貼り付けできます</li>
                <li>③ ＱＣ初心者に、課題解決型ストーリーで、ステップ毎に適切なアクションをＡＩ誘導するナビゲーション機能</li>
            </ul>
        </div>
    </div>
@endsection
