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
            $trail->push('編集');
         })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid bottom-fix">
        <form id="form-register" method="POST" action="{{ action('PlanByYearController@store') }}">
            @csrf
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
                        <span>{{ $year }} 年</span>
                        <input id="year" type="hidden" class="qc-form-input qc-form-input-50" name="year" value="{{ $year }}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">基本理念</td>
                    <td>
                        <!--<textarea id="vision" class="qc-form-area-5line qc-form-input-50" name="vision" >{{ old('vision') }}</textarea>-->
                        <input type="hidden" value="{{ old('vision')}}"
                               name="vision" id="vision">
                        <div id="vision-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">活動目標</td>
                    <td>
                        <!--<textarea id="target" class="qc-form-area-5line qc-form-input-50" name="target" >{{ old('target') }}</textarea>-->
                        <input type="hidden" value="{{ old('target') }}"
                               name="target" id="target">
                        <div id="target-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会社方針</td>
                    <td>
                        <!--<textarea id="motto" class="qc-form-area-5line qc-form-input-50" name="motto" >{{ old('motto') }}</textarea>-->
                        <input type="hidden" value="{{ old('motto')}}"
                               name="motto" id="motto">
                        <div id="motto-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項１</td>
                    <td>
                        <input id="prioritize_1" type="text" class="qc-form-input input-m" name="prioritize_1" value="{{ old('prioritize_1') }}" />
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('prioritize_1'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_1') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項２</td>
                    <td>
                        <input id="prioritize_2" type="text" class="qc-form-input input-m" name="prioritize_2" value="{{ old('prioritize_2') }}" />
                        @if ($errors->has('prioritize_2'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_2') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項３</td>
                    <td>
                        <input id="prioritize_3" type="text" class="qc-form-input input-m" name="prioritize_3" value="{{ old('prioritize_3') }}" />
                        @if ($errors->has('prioritize_3'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_3') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項４</td>
                    <td>
                        <input id="prioritize_4" type="text" class="qc-form-input input-m" name="prioritize_4" value="{{ old('prioritize_4') }}" />
                        @if ($errors->has('prioritize_4'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_4') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項５</td>
                    <td>
                        <input id="prioritize_5" type="text" class="qc-form-input input-m" name="prioritize_5" value="{{ old('prioritize_5') }}" />
                        @if ($errors->has('prioritize_5'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_5') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項６</td>
                    <td>
                        <input id="prioritize_6" type="text" class="qc-form-input input-m" name="prioritize_6" value="{{ old('prioritize_6') }}" />
                        @if ($errors->has('prioritize_6'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_6') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項７</td>
                    <td>
                        <input id="prioritize_7" type="text" class="qc-form-input input-m" name="prioritize_7" value="{{ old('prioritize_7') }}" />
                        @if ($errors->has('prioritize_7'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_7') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項８</td>
                    <td>
                        <input id="prioritize_8" type="text" class="qc-form-input input-m" name="prioritize_8" value="{{ old('prioritize_8') }}" />
                        @if ($errors->has('prioritize_8'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_8') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項９</td>
                    <td>
                        <input id="prioritize_9" type="text" class="qc-form-input input-m" name="prioritize_9" value="{{ old('prioritize_9') }}" />
                        @if ($errors->has('prioritize_9'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_9') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項１０</td>
                    <td>
                        <input id="prioritize_10" type="text" class="qc-form-input input-m" name="prioritize_10" value="{{ old('prioritize_10') }}" />
                        @if ($errors->has('prioritize_10'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_10') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合回数目標</td>
                    <td>
                        <input id="meeting_times" type="text" class="qc-form-input input-s" name="meeting_times" value="{{ old('meeting_times') }}" /> 回／月
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('meeting_times'))
                            <span class="qc-form-error-message">{{ $errors->first('meeting_times') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合時間目標</td>
                    <td>
                        <input id="meeting_hour" type="text" class="qc-form-input input-s" name="meeting_hour" value="{{ old('meeting_hour') }}" /> ｈ／月
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('meeting_hour'))
                            <span class="qc-form-error-message">{{ $errors->first('meeting_hour') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ﾃｰﾏ完了件数目標</td>
                    <td>
                        <input id="case_number_complete" type="text" class="qc-form-input input-s" name="case_number_complete" value="{{ old('case_number_complete') }}" /> 件／年
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('case_number_complete'))
                            <span class="qc-form-error-message">{{ $errors->first('case_number_complete') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">改善(個人)件数</td>
                    <td>
                        <input id="case_number_improve" type="text" class="qc-form-input input-s" name="case_number_improve" value="{{ old('case_number_improve') }}" /> 件／年
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('case_number_improve'))
                            <span class="qc-form-error-message">{{ $errors->first('case_number_improve') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">勉強会開催目標</td>
                    <td>
                        <input id="classes_organizing_objective" type="text" class="qc-form-input input-s" name="classes_organizing_objective" value="{{ old('classes_organizing_objective') }}" /> 回／年
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('classes_organizing_objective'))
                            <span class="qc-form-error-message">{{ $errors->first('classes_organizing_objective') }}</span>
                        @endif
                    </td>
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
        var vision_Editor = new Quill('#vision-editor', {
            bounds: '#vision-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });  
        vision_Editor.root.innerHTML = '<?php echo old('vision') ?>';        
        
        var target_Editor = new Quill('#target-editor', {
            bounds: '#target-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        target_Editor.root.innerHTML = '<?php echo old('target') ?>';
        
        var motto_Editor = new Quill('#motto-editor', {
            bounds: '#motto-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        motto_Editor.root.innerHTML = '<?php echo old('motto') ?>';       
</script>
    <script>
        $("#btn-register").click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\"";          
            $('#vision').val(vision_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#target').val(target_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#motto').val(motto_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $("#form-register").submit()
        });
    </script>
@endsection
