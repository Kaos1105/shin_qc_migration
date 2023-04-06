@extends('layouts.app')
@section('content')
    <div class="unauthorized-msg">
        <img class="img-fluid" src="{{asset('/images/404-error.png')}}" alt="404">
        <h1>このページは存在しません。</h1>
        <div style="margin-top: 4rem">
            <a class="btn btn-link" href="{{route('toppageoffice')}}">トップページへ</a>
            <span>または</span>
            <button type="button" class="btn btn-link" onclick="window.history.back()">戻る</button>
        </div>
    </div>
@endsection