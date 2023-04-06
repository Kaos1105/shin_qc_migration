@extends('layouts.app')
@section('content')
    <div class="unauthorized-msg">
        <img class="img-fluid" src="{{asset('/images/no-entry.png')}}" alt="no entry">
        <h1>アクセスできません。</h1>
        <p>サークル所属メンバーしかアクセスできない。</p>
        <button type="button" class="btn btn-dark float-right" style="margin-top: 4rem; width: 100px" onclick="window.history.back()">戻る</button>
    </div>
@endsection