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
        Breadcrumbs::for('edit', function ($trail, $theme_id) {
            $trail->parent('show');
            $trail->push('進捗登録');
        })
    }}

    {{ Breadcrumbs::render('edit', $theme->id) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-back" id="btn-delete">削除</a>
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ URL::to('theme/'.$theme->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($promotion_theme, array('route' => array('promotiontheme.update', $promotion_theme->id), 'method' => 'PUT', 'id' => 'form-register')) }}
        @csrf
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ App\Enums\Common::show_id($promotion_theme->id) }}</span>
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
                </td>
            </tr>
            <tr>
                <td class="td-first">進捗区分</td>
                <td>
                        <span>
                            @if($promotion_theme->progression_category == 1)
                                1.テーマ選定
                            @elseif($promotion_theme->progression_category == 2)
                                2.現状把握と目標設定
                            @elseif($promotion_theme->progression_category == 3)
                                3.活動計画の作成
                            @elseif($promotion_theme->progression_category == 4)
                                4.要因解析
                            @elseif($promotion_theme->progression_category == 5)
                                5.対策検討と実施
                            @elseif($promotion_theme->progression_category == 6)
                                6.効果確認
                            @elseif($promotion_theme->progression_category == 7)
                                7.標準化と管理の定着
                            @endif
                        </span>
                </td>
            </tr>
            <tr>
                <td class="td-first">開始予定日</td>
                <td>
                    <input type="text" class="qc-form-input input-s qc-datepicker" id="date_expected_start"
                           value="{{ old('date_expected_start', isset($promotion_theme->date_expected_start)? date("Y/m/d", strtotime($promotion_theme->date_expected_start)) : null) }}"
                           name="date_expected_start" autocomplete="off">
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
                           value="{{ old('date_expected_completion', isset($promotion_theme->date_expected_completion)? date("Y/m/d", strtotime($promotion_theme->date_expected_completion)) : null) }}"
                           name="date_expected_completion" autocomplete="off">
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
                           value="{{ old('date_actual_start', isset($promotion_theme->date_actual_start)? date("Y/m/d", strtotime($promotion_theme->date_actual_start)) : null) }}"
                           name="date_actual_start" autocomplete="off">
                    <img id="date-icon-3" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                </td>
            </tr>
            <tr>
                <td class="td-first">完了実績日</td>
                <td>
                    <input type="text" class="qc-form-input input-s qc-datepicker" id="date_actual_completion"
                           value="{{ old('date_actual_completion', isset($promotion_theme->date_actual_completion)? date("Y/m/d", strtotime($promotion_theme->date_actual_completion)) : null) }}"
                           name="date_actual_completion" autocomplete="off">
                    <img id="date-icon-4" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon">
                </td>
            </tr>
            <tr>
                <td class="td-first">備考</td>
                <td>
                    <textarea id="note" type="text" class="qc-form-area qc-form-input-50"
                              name="note">{{ old('note', isset($promotion_theme)? $promotion_theme->note : null) }}</textarea>
                </td>
            </tr>
        </table>
        {{ Form::close() }}
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['promotiontheme.destroy', $promotion_theme->id]]) !!}
        {!! Form::close() !!}
    </div>
    <script>
        $(document).ready(function () {
            $(".qc-datepicker").datepicker(options);
            $("#date_expected_start").datepicker("option", "maxDate", $("#date_expected_completion").val());
            $("#date_expected_completion").datepicker("option", "minDate", $("#date_expected_start").val());
            $("#date_actual_start").datepicker("option", "maxDate", $("#date_actual_completion").val());
            $("#date_actual_completion").datepicker("option", "minDate", $("#date_actual_start").val());
            $("#date_expected_start").on('change', function () {
                $("#date_expected_completion").datepicker("option", "minDate", $(this).val())
            });
            $("#date_expected_completion").on('change', function () {
                $("#date_expected_start").datepicker("option", "maxDate", $(this).val())
            });
            $("#date_actual_start").on('change', function () {
                $("#date_actual_completion").datepicker("option", "minDate", $(this).val())
            });
            $("#date_actual_completion").on('change', function () {
                $("#date_actual_start").datepicker("option", "maxDate", $(this).val())
            });

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
        $("#btn-register").click(function () {
            $("#form-register").submit()
        });
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Promotion_Theme }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
