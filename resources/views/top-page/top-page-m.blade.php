@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="padding: 15px">
            <div style="display: flex; border: 1px solid black; width: 100%;">
                @include('.top-page.week')
            </div>
        </div>
        <div class="toppage-main">
            <div class="menu-container">
                <div class="menu-group">
                    <div class="menu-card">
                        <div class="menu-card-head">マ　ス　タ　登　録</div>
                        <a class="menu-link" href="/user">利用者 マスタ</a>
                        <a class="menu-link" href="/department">部　門 マスタ</a>
                        <a class="menu-link" href="/place">職　場 マスタ</a>
                        <a class="menu-link" href="/circle">サークル マスタ</a>
                        <a class="menu-link" href="/organization">組　　織　　図</a>
                    </div>
                    <div class="menu-card">
                        <div class="menu-card-head">年　間　計　画</div>
                        <a class="menu-link" href="/planbyyear">事務局 年間計画</a>
                        <a class="menu-link" href="/promotion-circle-office">サークル 推進計画</a>
                        <a class="menu-link" href="/theme-office/list">テ ー マ 管 理</a>
                    </div>
                </div>
                <div class="menu-group">
                    <div class="menu-card">
                        <div class="menu-card-head">ヘ　ル　プ　デ　ス　ク</div>
                        <a class="menu-link" href="/category">
                            @if($noti_mark == 1) <span>(!)</span> @endif
                            掲 示 板
                        </a>
                        <a class="menu-link" href="/notification">お知らせ管理</a>
                        <a class="menu-link" href="/library">書 庫 管 理</a>
                    </div>
                    <div class="menu-card">
                        <div class="menu-card-head">関　連　リ　ン　ク</div>
                        <a class="menu-link" href="/homepage">ＨＰリンク管理</a>
                        <ul class="toppage-hp-link">
                            @foreach($hp as $hp_item)
                                <li><a class="a-black text-decoration-none line-brake-preserve" href="{{$hp_item->url}}" target="_blank">&#11162; {{$hp_item->title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="menu-group-3">
                    <div class="menu-card">
                        <div class="menu-card-head">Ｑ Ｃ 手 法 支 援 ツ ー ル</div>
                        <a class="menu-link" href="/">ツール の 使い方</a>
                        <a class="menu-link" href="/">ダ ウ ン ロ ー ド</a>
                    </div>
                    <div class="menu-card">
                        <div class="menu-card-head">Ｑ Ｃ 支 援 ナ ビ</div>
                        <a class="menu-link" href="/story/list">ストーリー登録</a>
                        <a class="menu-link" href="/qa/list">ＱＡ画面登録</a>
                    </div>
                    <div class="menu-card">
                        <div class="menu-card-head">Ｑ Ｃ 理 解 度 テ ス ト</div>
                        <a class="menu-link" href="">理解度テストとは</a>
                        <a class="menu-link" target="_blank" href="http://qcsystem.xbiz.jp/Android/viewer/login.php">テスト管理ツール</a>
                    </div>
                    <div class="menu-card">
                        <div class="menu-card-head">進　捗　管　理</div>
                        <a class="menu-link" href="/activity-office/list">活動記録 管 理</a>
                        <a class="menu-link" href="/calendar">ＱＣ活動カレンダー</a>
                    </div>
                    <div class="menu-card">
                        <div class="menu-card-head">教育資料</div>
                        <a class="menu-link" href="/educational-materials">QC教育資料</a>
                    </div>
                </div>
            </div>
            <div class="side-panel">
                <div class="top-page-statistic">
                    <span><em class="fa fa-square" style="color:black;"></em> 月間活動回数</span>
                    <span class="d-block">先月{{ $activity_last_month }}回　今月{{ $activity_current_month }}回　来月{{ $activity_next_month }}回</span>
                </div>

                <div class="top-page-statistic">
                    <span><em class="fa fa-square" style="color:red;"></em> 45日以上活動していないサークル</span>
                    <ul>
                        @if(count($arr_date_45) > 0)
                            @foreach($arr_date_45 as $arr_date_45_item)
                                <li>
                                    <a class="a-black"
                                       href="{{ URL::to('circle/'.$arr_date_45_item->circle_id.'?callback=yes') }}">{{ $arr_date_45_item->circle_name }}
                                        （{{ \App\Enums\Common::get_number_day(new DateTime($arr_date_45_item->max_date), new DateTime(date('Y-m-d') . "")) }}
                                        日)</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="top-page-statistic">
                    <span><em class="fa fa-square" style="color:yellow;"></em> 25日以上活動していないサークル</span>
                    <ul>
                        @if(count($arr_date_25) > 0)
                            @foreach($arr_date_25 as $arr_date_25_item)
                                <li>
                                    <a class="a-black"
                                       href="{{ URL::to('circle/'.$arr_date_25_item->circle_id.'?callback=yes') }}">{{ $arr_date_25_item->circle_name }}
                                        （{{ \App\Enums\Common::get_number_day(new DateTime($arr_date_25_item->max_date), new DateTime(date('Y-m-d') . "")) }}
                                        日)</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="top-page-statistic">
                    <span><em class="fa fa-square" style="color:black;"></em> 年間計画が未提出(登録)のサークル</span>
                    <ul>
                        @foreach($cirlce_not_exists_promotion as $cirlce_not_exists_promotion_item)
                            <li><a class="a-black"
                                   href="{{ URL::to('circle/'.$cirlce_not_exists_promotion_item->id.'?callback=yes') }}">{{ $cirlce_not_exists_promotion_item->circle_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="top-page-statistic">
                    <span><em class="fa fa-square" style="color:black;"></em> テーマが登録されていないサークル</span>
                    <ul>
                        @foreach($cirlce_not_exists_theme as $cirlce_not_exists_theme_item)
                            <li><a class="a-black"
                                   href="{{ URL::to('circle/'.$cirlce_not_exists_theme_item->id.'?callback=yes') }}">{{ $cirlce_not_exists_theme_item->circle_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            if (sessionStorage.getItem('stampMode')) {
                sessionStorage.removeItem('stampMode');
            }
        });
    </script>
@endsection
