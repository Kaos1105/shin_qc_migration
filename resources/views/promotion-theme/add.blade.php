@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('show', function ($trail) {
                $trail->parent('toppage');
                $trail->push('テーマ登録', URL::to('theme'));
        }),
        Breadcrumbs::for('add', function ($trail) {
            $trail->parent('show');
            $trail->push('進捗登録');
        })
    }}

    {{ Breadcrumbs::render('add', $theme->id) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ URL::to('theme/'.$theme->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('PromotionThemeController@store') }}">
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
                        <span>{{ $circle->circle_name  }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">テーマ名</td>
                    <td>
                        <span>{{ $theme->theme_name  }}</span>
                        <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">進捗区分</td>
                    <td>
                        <span>
                            @if($value == 1)
                                1.テーマ選定
                            @elseif($value == 2)
                                2.現状把握と目標設定
                            @elseif($value == 3)
                                3.活動計画の作成
                            @elseif($value == 4)
                                4.要因解析
                            @elseif($value == 5)
                                5.対策検討と実施
                            @elseif($value == 6)
                                6.効果確認
                            @elseif($value == 7)
                                7.標準化と管理の定着
                            @endif
                        </span>
                        <input type="hidden" name="progression_category" value="{{ $value }}">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">開始予定日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_expected_start"
                               value="{{ old('date_expected_start') }}" name="date_expected_start" autocomplete="off">
                        <img id="date-icon-1" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('date_expected_start'))
                            <span class="qc-form-error-message">{{ $errors->first('date_expected_start') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">完了予定日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_expected_completion"
                               value="{{ old('date_expected_completion') }}" name="date_expected_completion" autocomplete="off">
                        <img id="date-icon-2" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('date_expected_completion'))
                            <span class="qc-form-error-message">{{ $errors->first('date_expected_completion') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">開始実績日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_actual_start"
                               value="{{ old('date_actual_start') }}" name="date_actual_start" autocomplete="off">
                        <img id="date-icon-3" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">完了実績日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_actual_completion"
                               value="{{ old('date_actual_completion') }}" name="date_actual_completion" autocomplete="off">
                        <img id="date-icon-4" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note" >{{ old('note') }}</textarea>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $(".qc-datepicker").datepicker(options);
            $( "#date_expected_start" ).on('change', function(){$( "#date_expected_completion" ).datepicker( "option", "minDate", $(this).val())});
            $( "#date_expected_completion" ).on('change', function(){$( "#date_expected_start" ).datepicker( "option", "maxDate", $(this).val())});
            $( "#date_actual_start" ).on('change', function(){$( "#date_actual_completion" ).datepicker( "option", "minDate", $(this).val())});
            $( "#date_actual_completion" ).on('change', function(){$( "#date_actual_start" ).datepicker( "option", "maxDate", $(this).val())});

            $('#date-icon-1').click(function () {
                $('#date_expected_start').focus();
            });
            $('#date-icon-2').click(function () {
                $('#date_expected_completion').focus();
            });
            $('#date-icon-3').click(function () {
                $('#date_actual_start').focus();
            });
            $('#date-icon-4').click(function () {
                $('#date_actual_completion').focus();
            });

        });
        $("#btn-register").click(function(){
            $("#form-register").submit()
        });
    </script>
@endsection
