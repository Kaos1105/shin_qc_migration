@extends('layouts.app')
@section('breadcrumbs')
    {{
        Breadcrumbs::for('login', function ($trail) {
                    $trail->push('ログインページ', route('login'));
                })
    }}

    {{
        Breadcrumbs::for('cannotlogin', function ($trail) {
                $trail->parent('login');
                $trail->push('ログインできない場合');
            })
    }}


    {{ Breadcrumbs::render('cannotlogin') }}
@endsection
@section('content')
    <div id="cannot-login">
        <div class="cannot-login-item">
            <div class="text-center text-white num-badge">1</div>
            <div class="text-panel text-white">システムにログインできません。</div>
            <div class="text-list">
                <p>
                    事務局から発行されたユーザーIDとパスワードを使用していますか？<br>
                    ユーザーIDとパスワードは、半角英数字で入力して下さい。<br>
                    ログインページは、それぞれの目的に合わせて選択して下さい。<br>
                    どうしてもログインできない場合は、事務局にお問い合わせください。
                </p>
            </div>
        </div>
        <div class="cannot-login-item">
            <div class="text-center text-white num-badge">2</div>
            <div class="text-panel text-white">ログインＩＤ、パスワードを忘れた。</div>
            <div class="text-list">
                <p>
                    ユーザーIDとパスワードを忘れた場合は、事務局までお問合せ下さい。<br>
                    ユーザーIDと、新しいパスワードをお知らせいたします。
                </p>
            </div>
        </div>
        <div class="cannot-login-item">
            <div class="text-center text-white num-badge">3</div>
            <div class="text-panel text-white">新たに利用者を登録したい。</div>
            <div class="text-list">
               <p>
                   システムにログインするには、利用者登録が必要となります。<br>
                   事務局まで、氏名、所属サークルをご連絡ください。
               </p>
            </div>
        </div>
    </div>
@endsection