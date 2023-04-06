@extends('layouts.app')

@section('content')
    @if(Auth::user() -> access_authority === \App\Enums\AccessAuthority::USER)
        @include('top-page.top-page-circle')
    @else
        @include('top-page.top-page-m')
    @endif
@endsection
