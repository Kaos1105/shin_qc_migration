@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
                $trail->parent('toppage');
                $trail->push('テーマ登録', URL::to('theme'));
        }),
        Breadcrumbs::for('add', function ($trail) {
            $trail->parent('list');
            $trail->push('新規登録／複写登録');
        })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('ThemeController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">サークル名</td>
                    <td>
                        <span>{{ session('circle.circle_name') }}</span>
                        <input type="hidden" name="circle_id" value="{{ session('circle.id') }}">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">テーマ名</td>
                    <td>
                        <input type="text" class="qc-form-input qc-form-input-50" name="theme_name"
                               value="{{ old('theme_name', isset($theme)?$theme['theme_name']:null) }}" required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('theme_name'))
                            <span class="qc-form-error-message">{{ $errors->first('theme_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">特性値</td>
                    <td>
                        <input type="text" class="qc-form-input input-m" name="value_property"
                               value="{{ old('value_property', isset($theme)?$theme['value_property']:null) }}"
                               required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('value_property'))
                            <span class="qc-form-error-message">{{ $errors->first('value_property') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">目標値</td>
                    <td>
                        <input type="text" class="qc-form-input input-m" name="value_objective"
                               value="{{ old('value_objective', isset($theme)?$theme['value_objective']:null) }}"
                               required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('value_objective'))
                            <span class="qc-form-error-message">{{ $errors->first('value_objective') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">開始予定日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_start"
                               value="{{ old('date_start', isset($theme)? date("Y/m/d", strtotime($theme['date_start'])):null) }}"
                               name="date_start" autocomplete="off">
                        <img id="date-icon-1" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('date_start'))
                            <span class="qc-form-error-message">{{ $errors->first('date_start') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">完了予定日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_expected_completion"
                               value="{{ old('date_expected_completion', isset($theme)? date("Y/m/d", strtotime($theme['date_expected_completion'])):null) }}"
                               name="date_expected_completion" autocomplete="off">
                        <img id="date-icon-2" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('date_expected_completion'))
                            <span class="qc-form-error-message">{{ $errors->first('date_expected_completion') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">完了実績日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_actual_completion"
                               value="{{ old('date_actual_completion')}}" name="date_actual_completion"
                               autocomplete="off">
                        <img id="date-icon-3" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text" class="qc-form-area qc-form-input-50"
                                  name="note">{{ old('note', isset($theme)?$theme['note']:null) }}</textarea>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $(".qc-datepicker").datepicker(options);
            $("#date_start").on('change', function () {
                $("#date_expected_completion").datepicker("option", "minDate", $(this).val())
            });
            $("#date_start").on('change', function () {
                $("#date_actual_completion").datepicker("option", "minDate", $(this).val())
            });
            $("#date_expected_completion").on('change', function () {
                $("#date_start").datepicker("option", "maxDate", $(this).val())
            });

            $("#date_actual_completion").on('change', function () {
                $("#date_start").datepicker("option", "maxDate", $(this).val())
            });

            $('#date-icon-1').click(function () {
                $('#date_start').focus();
            });
            $('#date-icon-2').click(function () {
                $('#date_expected_completion').focus();
            });
            $('#date-icon-3').click(function () {
                $('#date_actual_completion').focus();
            });
        });
        $("#btn-register").click(function () {
            $("#form-register").submit();
        });        
    </script>
@endsection
