@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
       Breadcrumbs::for('story-classification', function ($trail) {
           $trail->parent('toppage');
           $trail->push('ＱＣナビ', URL::to('qc-navi/story-classification'));
       }),
       Breadcrumbs::for('story-list', function ($trail, $classification) {
           $trail->parent('story-classification');
           $trail->push($classification);
       }),
       Breadcrumbs::for('story-select', function ($trail, $classification) {
           $trail->parent('story-list', $classification);
           $trail->push('ストーリー選択');
       })
    }}

    {{ Breadcrumbs::render('story-select', $classification) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-back" href="{{ URL::to('qc-navi/story-classification') }}">戻る</a>
    </div>
    <div style="margin: 4rem 10rem">
        <div class="bottom-fix" style="margin-top: 3.3rem; ">
            @foreach($story_list as $story_item)
                <?php
                $qa = DB::table('qas')->where('story_id', $story_item->id)
                    ->where('use_classification', \App\Enums\UseClassificationEnum::USES)
                    ->orderBy('display_order', 'asc')
                    ->select('id', 'screen_id', 'title')
                    ->get();
                ?>
                @if(count($qa) > 0)
                    <div class="story-list-panel">
                        <span class="triangle">&#x25BC;</span>
                        <div class="head-6300">
                            <a class="a-black"
                               href="{{URL::to('/qc-navi/story-navi?newThread=true&qaId='.$qa[0]->id)}}">
                                <h4><strong>{{ $story_item->story_name }}</strong></h4></a>
                            <p class="line-brake-preserve">{{$story_item->description}}</p>
                        </div>
                        <div class="body-6300">
                            <table class="table-6300">
                                @foreach($qa as $qa_item)
                                    <tr>
                                        <td style="width: 10%" class="text-center">{{$qa_item->screen_id}}</td>
                                        <td>
                                            <a class="a-black"
                                               href="{{URL::to('/qc-navi/story-navi?newThread=true&qaId='.$qa_item->id)}}">{{$qa_item->title}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <script>
        $(function () {
            $('.triangle').on('click', function () {
                $(this).next().next().slideToggle(100);
                if ($(this).text() === '▲') {
                    $(this).text('▼');
                } else {
                    $(this).text('▲');
                }
            });
        });
    </script>
@endsection
