@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('planbyyear', function ($trail) {
            $trail->parent('toppage');
            $trail->push('事務局年間計画',URL::to('planbyyear'));
        }),
        Breadcrumbs::for('add', function ($trail) {
            $trail->parent('planbyyear');
            $trail->push('月間活動登録');
        })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-add" id="btn-delete" disabled>削除</a>
        <a class="btn btn-back" href="{{ URL::to('planbyyear') }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('PlanByMonthController@store') }}">
            @csrf
            <input type="hidden" value="{{ $planbyyear->id }}" name="plan_by_year_id" />
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">登録年度</td>
                    <td>
                        <span>{{ $planbyyear->year }}年</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項</td>
                    <td>
                        <select name="execution_order_no" class="qc-form-select">
                            @if(isset($planbyyear->prioritize_1)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY1 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY1 ? "selected":"") }}>{{ $planbyyear->prioritize_1 }}</option> @endif
                            @if(isset($planbyyear->prioritize_2)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY2 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY2 ? "selected":"") }}>{{ $planbyyear->prioritize_2 }}</option> @endif
                            @if(isset($planbyyear->prioritize_3)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY3 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY3 ? "selected":"") }}>{{ $planbyyear->prioritize_3 }}</option> @endif
                            @if(isset($planbyyear->prioritize_4)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY4 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY4 ? "selected":"") }}>{{ $planbyyear->prioritize_4 }}</option> @endif
                            @if(isset($planbyyear->prioritize_5)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY5 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY5 ? "selected":"") }}>{{ $planbyyear->prioritize_5 }}</option> @endif
                            @if(isset($planbyyear->prioritize_6)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY6 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY6 ? "selected":"") }}>{{ $planbyyear->prioritize_6 }}</option> @endif
                            @if(isset($planbyyear->prioritize_7)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY7 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY7 ? "selected":"") }}>{{ $planbyyear->prioritize_7 }}</option> @endif
                            @if(isset($planbyyear->prioritize_8)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY8 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY8 ? "selected":"") }}>{{ $planbyyear->prioritize_8 }}</option> @endif
                            @if(isset($planbyyear->prioritize_9)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY9 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY9 ? "selected":"") }}>{{ $planbyyear->prioritize_9 }}</option> @endif
                            @if(isset($planbyyear->prioritize_10)) <option value="{{ App\Enums\PrioritizeEnum::PRIORITY10 }}" {{ (old("execution_order_no") == App\Enums\PrioritizeEnum::PRIORITY10 ? "selected":"") }}>{{ $planbyyear->prioritize_10 }}</option> @endif
                        </select>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('execution_order_no'))
                            <span class="qc-form-error-message">{{ $errors->first('execution_order_no') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">内容</td>
                    <td>
                        <input id="contents" type="text" class="qc-form-input qc-form-input-50" value="{{ old('contents') }}" name="contents">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('contents'))
                            <span class="qc-form-error-message">{{ $errors->first('contents') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順</td>
                    <td>
                        <input id="display_order" type="text" class="qc-form-input qc-form-input-10" value="{{ old('display_order', '999') }}" name="display_order">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">担当</td>
                    <td>
                        <!--<textarea id="in_charge" class="qc-form-area qc-form-input-50" name="in_charge" >{{ old('in_charge') }}</textarea>-->
                        <input type="hidden" value="{{ old('in_charge')}}"
                               name="in_charge" id="in_charge">
                        <div id="in_charge-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <!--<textarea id="note" class="qc-form-area qc-form-input-50" name="note" >{{ old('note') }}</textarea>-->
                        <input type="hidden" value="{{ old('note')}}"
                               name="note" id="note">
                        <div id="note-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
            </table>
            <div style="margin:5px 0px;">
                月間活動登録
            </div>
            <table class="table-form table-plan-by-month" border="1">
                <tr>
                    <td class="td-first">{{ __('月') }}</td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                    <td>11</td>
                    <td>12</td>
                </tr>
                <tr>
                    <td class="td-first">{{ __('期間') }}</td>
                    <td>
                        <input type="radio" value="1" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 1 ? "checked":"") }}/> |
                        <input type="radio" value="1" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 1 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="2" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 2 ? "checked":"") }}/> |
                        <input type="radio" value="2" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 2 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="3" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 3 ? "checked":"") }}/> |
                        <input type="radio" value="3" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 3 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="4" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 4 ? "checked":"") }}/> |
                        <input type="radio" value="4" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 4 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="5" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 5 ? "checked":"") }}/> |
                        <input type="radio" value="5" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 5 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="6" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 6 ? "checked":"") }}/> |
                        <input type="radio" value="6" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 6 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="7" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 7 ? "checked":"") }}/> |
                        <input type="radio" value="7" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 7 ? "checked":"") }}/>
                    </td><td>
                        <input type="radio" value="8" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 8 ? "checked":"") }}/> |
                        <input type="radio" value="8" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 8 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="9" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 9 ? "checked":"") }}/> |
                        <input type="radio" value="9" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 9 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="10" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 10 ? "checked":"") }}/> |
                        <input type="radio" value="10" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 10 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="11" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 11 ? "checked":"") }}/> |
                        <input type="radio" value="11" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 11 ? "checked":"") }}/>
                    </td>
                    <td>
                        <input type="radio" value="12" name="month_start" onchange="checkStart(this)" {{ (old("month_start") == 12 ? "checked":"") }}/> |
                        <input type="radio" value="12" name="month_end" onchange="checkEnd(this)" {{ (old("month_end") == 12 ? "checked":"") }}/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">{{ __('内容') }}</td>
                    <td><input class="qc-form-input" type="text" name="content_jan" value="{{ old('content_jan') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_feb" value="{{ old('content_feb') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_mar" value="{{ old('content_mar') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_apr" value="{{ old('content_apr') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_may" value="{{ old('content_may') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_jun" value="{{ old('content_jun') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_jul" value="{{ old('content_jul') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_aug" value="{{ old('content_aug') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_sep" value="{{ old('content_sep') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_oct" value="{{ old('content_oct') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_nov" value="{{ old('content_nov') }}" size="14" /></td>
                    <td><input class="qc-form-input" type="text" name="content_dec" value="{{ old('content_dec') }}" size="14" /></td>
                </tr>
            </table>

        </form>
    </div>
<script>
        var Font = Quill.import('formats/font');
        Font.whitelist = ['mirza', 'roboto', 'arial', 'MS UI Gothic', 'Meiryo UI', '游ゴシック', 'sans-serif'];
        Quill.register(Font, true);
        var fonts = ['roboto', 'arial', 'MS UI Gothic', 'Meiryo UI', '游ゴシック', 'sans-serif'];
        var toolbar = [
                    [{'size': []}],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{'color': []}, {'background': []}],
                    [{'script': 'super'}, {'script': 'sub'}],
                    [{'header': '1'}, {'header': '2'}, 'blockquote'],
                    [{'list': 'ordered'}, {'list': 'bullet'}, {'indent': '-1'}, {'indent': '+1'}],
                    [{'align': []}],
                    ['link'],
                    ['clean']
                ];
        var in_charge_Editor = new Quill('#in_charge-editor', {
            bounds: '#in_charge-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        in_charge_Editor.root.innerHTML = '<?php echo old('in_charge') ?>';

        var note_Editor = new Quill('#note-editor', {
            bounds: '#note-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        note_Editor.root.innerHTML = '<?php echo old('note') ?>';
</script>
    <script>
        var startValue = 0;
        var endValue = 0;
        $("#btn-register").click(function(){
            startValue = $("input[name='month_start']:checked").val();
            endValue = $("input[name='month_end']:checked").val();
            if(!startValue || !endValue){
                alert('開始月と終了月を設定してください');
            }else{
                var style = "\"margin-top: 0; margin-bottom: 0;\"";
                $('#in_charge').val(in_charge_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                $('#note').val(note_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                $("#form-register").submit();
            }
        });
        function checkStart(start) {
            var valueCur = parseInt(start.value);
            if(endValue != 0){
                if(valueCur > endValue){
                    alert("開始月は終了月以下で設定して下さい");
                    start.checked = false;
                    var radioButtons = document.getElementsByName("month_start");
                    for (var x = 0; x < radioButtons.length; x ++) {
                        if (radioButtons[x].value == startValue) {
                            radioButtons[x].checked = true;
                        }
                    }
                }
                else{
                    startValue = valueCur;
                }
            }
            else{
                startValue = valueCur;
            }
        }
        function checkEnd(end) {
            var valueCur = parseInt(end.value);
            if(startValue != 0){
                if(valueCur < startValue){
                    alert("終了月は開始月以上で設定して下さい")
                    end.checked = false;
                    var radioButtons = document.getElementsByName("month_end");
                    for (var x = 0; x < radioButtons.length; x ++) {
                        if (radioButtons[x].value == endValue) {
                            radioButtons[x].checked = true;
                        }
                    }
                }
                else{
                    endValue = valueCur;
                }
            }
            else{
                endValue = valueCur;
            }
        }
    </script>
@endsection
