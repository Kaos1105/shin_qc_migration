@extends('layouts.app')
@section('breadcrumbs')
    {{
        Breadcrumbs::for('login', function ($trail) {
                    $trail->push('ログインページ', route('login'));
                })
    }}

    {{
        Breadcrumbs::for('sendquestion', function ($trail) {
                $trail->parent('login');
                $trail->push('事務局へのお問合せ');
            })
    }}


    {{ Breadcrumbs::render('sendquestion') }}
@endsection
@section('content')
    <div id="send-question">
        <div class="send-question-item row">
            <div class="col-md-12 send-question-item-on send-question-item-on-email">
                <span>メールでのお問合せ</span>
            </div>
            <div class="col-md-12 send-question-item-un">
                <table>
                    <tr>
                        <td>・メールアドレス</td>
                        <td>n_sawada@shinnichiro.o.jp</td>
                    </tr>
                    <tr>
                        <td>・担　当　者　名</td>
                        <td>澤田</td>
                    </tr>
                    <tr>
                        <td>・メールする際の注意</td>
                        <td>メール件名に「QCシステム問合せ」と記入してください</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="send-question-item row">
            <div class="col-md-12 send-question-item-on send-question-item-on-phone">
                <span>電話でのお問合せ</span>
            </div>
            <div class="col-md-12 send-question-item-un">
                <table>
                    <tr>
                        <td style="width: 136px;">電　話　番　号</td>
                        <td>086-448-3405</td>
                    </tr>
                    <tr>
                        <td style="width: 136px;">担　当　者　名</td>
                        <td>澤田、戸摩</td>
                    </tr>
                    <tr>
                        <td style="width: 136px;">時　　間　　帯</td>
                        <td>8:00～17:00</td>
                    </tr>
                    <tr>
                        <td style="width: 136px;">電話する際の注意</td>
                        <td>担当者が不在の場合がありますので、メールアドレスをお持ちの方は、メールにてお問い合わせください。</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
