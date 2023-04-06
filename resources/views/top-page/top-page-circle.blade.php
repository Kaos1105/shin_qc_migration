@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="padding: 15px">
            <div style="display: flex; border: 1px solid black; width: 100%;">
                @include('.top-page.week')
            </div>
        </div>
        <div class="toppage-main">
            <div class="menu-container-c">
                <div class="first-col">
                    <div class="menu-card-c" style="box-shadow: none;width: -webkit-fill-available;word-wrap: break-word;">
                        <div class="menu-card-head-c">事務局からのお知らせ</div>
                        @if(isset($notification_in_card))
                            @foreach($notification_in_card as $item)
                            <span class="menu-link-c-no-click">@if(isset($item->date_start))<span class="text-danger">{{date_format(date_create($item->date_start),"Y/m/d")}}</span> @endif <span class="ql-editor" style="all:unset;"> {!!$item->message!!}</span></span>
                            @endforeach
                        @endif
                    </div>
                    <div class="menu-card-c" style="width: -webkit-fill-available;word-wrap: break-word;">
                        <div class="menu-card-head-c">事務局目標</div>
                        @if(isset($moto))
                        <span class="menu-link-c-no-click line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$moto->target!!}</span></span>
                        @endif
                    </div>
                    <div class="menu-card-c" style="width: -webkit-fill-available;word-wrap: break-word;">
                        <div class="menu-card-head-c">サークル目標</div>
                        @if(isset($moto_circle))
                        <span class="menu-link-c-no-click line-brake-preserve"><span class="ql-editor" style="all:unset;">{!!$moto_circle->motto_of_circle!!}</span></span>
                        @endif
                    </div>
                </div>
                <div class="second-col">
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">年　間　計　画</div>
                        <a class="menu-link-c" href="/promotion-circle">サークル推進計画</a>
                        <a class="menu-link-c" href="/theme">テーマ</a>
                    </div>
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">活　動　状　況</div>
                        <a class="menu-link-c" href="/activity">活動記録</a>
                        <a class="menu-link-c" href="/calendar">ＱＣ活動カレンダー</a>
                        <a class="menu-link-c" href="/activity-approval">推　進　管　理</a>
                    </div>
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">ヘ　ル　プ　デ　ス　ク</div>
                        <a class="menu-link-c" href="/category">
                            @if($noti_mark == 1) <span>(!)</span> @endif
                            掲 示 板
                        </a>
                        <a class="menu-link-c" href="/library-circle">書庫</a>
                    </div>
                    <div class="menu-card-c" style="box-shadow: none">
                        <div class="menu-card-head-c">関　連　リ　ン　ク</div>
                        <ul class="toppage-hp-link">
                            @foreach($hp as $hp_item)
                            <li><a class="a-black text-decoration-none line-brake-preserve" href="{{$hp_item->url}}" target="_blank">&#11162; {{$hp_item->title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="menu-group-3">
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">Ｑ Ｃ 手 法 支 援 ツ ー ル</div>
                        <a class="menu-link-c" href="/">ツールの使い方</a>
                        <a class="menu-link-c" href="/">ツールのダウンロード</a>
                    </div>
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">Ｑ Ｃ 支 援 ナ ビ</div>
                        <a class="menu-link-c" href="/">ナビの使い方</a>
                        <a class="menu-link-c" href="/qc-navi/story-classification">ナビの起動</a>
                    </div>
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">Ｑ Ｃ 理 解 度 テ ス ト</div>
                        <a class="menu-link-c" href="/">理解度テストとは</a>
                        <a class="menu-link-c" target="_blank" href="http://qcsystem.xbiz.jp/Android/viewer/login.php">テスト管理ツール</a>
                    </div>
                    <div class="menu-card-c">
                        <div class="menu-card-head-c">教育資料</div>
                        <a class="menu-link-c" href="/educational-materials-circle">QC教育資料</a>
                    </div>
                </div>
            </div>
            <div class="side-panel-c">
                <div id="progress-control">
                    @include('.top-page.progress-control')
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
