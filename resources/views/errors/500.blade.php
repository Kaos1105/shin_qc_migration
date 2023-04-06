@extends('layouts.app')
@section('content')
    <div class="unauthorized-msg">
        <img class="img-fluid" src="{{asset('/images/404-error.png')}}" alt="505">
        <h1>このページは存在しません。</h1>
        <button type="button" class="btn btn-dark float-right" style="margin-top: 4rem" onclick="window.history.back()">戻る</button>
    </div>
@endsection