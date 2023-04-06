@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
       Breadcrumbs::for('story-classification', function ($trail) {
           $trail->parent('toppage');
           $trail->push('ＱＣナビ');
       })
    }}

    {{ Breadcrumbs::render('story-classification') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-back" href="{{URL::to('/qc-navi/navi-history')}}">過去の履歴</a>
        <a class="btn btn-back" href="{{ URL::to('top-page') }}">戻る</a>
    </div>
    <div class="story-classification-grid">
        @foreach($story_classification_list as $item)
            <div class="story-classification-grid-item">
                <a href="{{ URL::to('qc-navi/story-list/'.$item) }}">
                    <div class="story-classification-block">
                        {{ $item }}
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
