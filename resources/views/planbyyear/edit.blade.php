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
        <a class="btn btn-back" href="{{ route('planbyyear.index', ['year' => $planbyyear->year]) }}">戻る</a>
    </div>
    <div class="container-fluid bottom-fix">
        {{ Form::model($planbyyear, array('route' => array('planbyyear.update', $planbyyear->id), 'method' => 'PUT', 'id' => 'form-register')) }}
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($planbyyear)? \App\Enums\Common::show_id($planbyyear['id']) : null }}</span>
                        <input type="hidden" name="id" value="{{ old('id', isset($planbyyear)? $planbyyear['id'] : null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">登録年度</td>
                    <td>
                        <span>{{ isset($planbyyear)?$planbyyear['year']:null }}</span>
                        <input type="hidden" name="year" value="{{ old('year', isset($planbyyear)?$planbyyear['year']:null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">基本理念</td>
                    <td>
                        <!--<textarea id="vision" class="qc-form-area-5line qc-form-input-50" name="vision" >{{ old('vision', isset($planbyyear)?$planbyyear['vision']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('vision', isset($planbyyear)?$planbyyear['vision']:null)}}"
                               name="vision" id="vision">
                        <div id="vision-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">活動目標</td>
                    <td>
                        <!--<textarea id="target" class="qc-form-area-5line qc-form-input-50" name="target" >{{ old('target', isset($planbyyear)?$planbyyear['target']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('target', isset($planbyyear)?$planbyyear['target']:null)}}"
                               name="target" id="target">
                        <div id="target-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会社方針</td>
                    <td>
                        <!--<textarea id="motto" class="qc-form-area-5line qc-form-input-50" name="motto" >{{ old('motto', isset($planbyyear)?$planbyyear['motto']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('motto', isset($planbyyear)?$planbyyear['motto']:null)}}"
                               name="motto" id="motto">
                        <div id="motto-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項１</td>
                    <td>
                        <input id="prioritize_1" type="text" class="qc-form-input input-m" name="prioritize_1" value="{{ old('prioritize_1', isset($planbyyear)?$planbyyear['prioritize_1']:null)}}" />
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('prioritize_1'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_1') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項２</td>
                    <td>
                        <input id="prioritize_2" type="text" class="qc-form-input input-m" name="prioritize_2" value="{{ old('prioritize_2', isset($planbyyear)?$planbyyear['prioritize_2']:null)}}" />
                        @if ($errors->has('prioritize_2'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_2') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項３</td>
                    <td>
                        <input id="prioritize_3" type="text" class="qc-form-input input-m" name="prioritize_3" value="{{ old('prioritize_3', isset($planbyyear)?$planbyyear['prioritize_3']:null)}}" />
                        @if ($errors->has('prioritize_3'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_3') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項４</td>
                    <td>
                        <input id="prioritize_4" type="text" class="qc-form-input input-m" name="prioritize_4" value="{{ old('prioritize_4', isset($planbyyear)?$planbyyear['prioritize_4']:null)}}" />
                        @if ($errors->has('prioritize_4'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_4') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項５</td>
                    <td>
                        <input id="prioritize_5" type="text" class="qc-form-input input-m" name="prioritize_5" value="{{ old('prioritize_5', isset($planbyyear)?$planbyyear['prioritize_5']:null)}}" />
                        @if ($errors->has('prioritize_5'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_5') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項６</td>
                    <td>
                        <input id="prioritize_6" type="text" class="qc-form-input input-m" name="prioritize_6" value="{{ old('prioritize_6', isset($planbyyear)?$planbyyear['prioritize_6']:null)}}" />
                        @if ($errors->has('prioritize_6'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_6') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項７</td>
                    <td>
                        <input id="prioritize_7" type="text" class="qc-form-input input-m" name="prioritize_7" value="{{ old('prioritize_7', isset($planbyyear)?$planbyyear['prioritize_7']:null)}}" />
                        @if ($errors->has('prioritize_7'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_7') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項８</td>
                    <td>
                        <input id="prioritize_8" type="text" class="qc-form-input input-m" name="prioritize_8" value="{{ old('prioritize_8', isset($planbyyear)?$planbyyear['prioritize_8']:null)}}" />
                        @if ($errors->has('prioritize_8'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_8') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項９</td>
                    <td>
                        <input id="prioritize_9" type="text" class="qc-form-input input-m" name="prioritize_9" value="{{ old('prioritize_9', isset($planbyyear)?$planbyyear['prioritize_9']:null)}}" />
                        @if ($errors->has('prioritize_9'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_9') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">重点実施事項１０</td>
                    <td>
                        <input id="prioritize_10" type="text" class="qc-form-input input-m" name="prioritize_10" value="{{ old('prioritize_10', isset($planbyyear)?$planbyyear['prioritize_10']:null)}}" />
                        @if ($errors->has('prioritize_10'))
                            <span class="qc-form-error-message">{{ $errors->first('prioritize_10') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合回数目標</td>
                    <td>
                        <input id="meeting_times" type="text" class="qc-form-input input-s" name="meeting_times" value="{{ old('meeting_times', isset($planbyyear)?$planbyyear['meeting_times']:null)}}" /> 回／月
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('meeting_times'))
                            <span class="qc-form-error-message">{{ $errors->first('meeting_times') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合時間目標</td>
                    <td>
                        <input id="meeting_hour" type="text" class="qc-form-input input-s" name="meeting_hour" value="{{ old('meeting_hour', isset($planbyyear)?$planbyyear['meeting_hour']:null)}}" /> ｈ／月
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('meeting_hour'))
                            <span class="qc-form-error-message">{{ $errors->first('meeting_hour') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ﾃｰﾏ完了件数目標</td>
                    <td>
                        <input id="case_number_complete" type="text" class="qc-form-input input-s" name="case_number_complete" value="{{ old('case_number_complete', isset($planbyyear)?$planbyyear['case_number_complete']:null)}}" /> 件／年
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('case_number_complete'))
                            <span class="qc-form-error-message">{{ $errors->first('case_number_complete') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">改善(個人)件数</td>
                    <td>
                        <input id="case_number_improve" type="text" class="qc-form-input input-s" name="case_number_improve" value="{{ old('case_number_improve', isset($planbyyear)?$planbyyear['case_number_improve']:null)}}" /> 件／年
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('case_number_improve'))
                            <span class="qc-form-error-message">{{ $errors->first('case_number_improve') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">勉強会開催目標</td>
                    <td>
                        <input id="classes_organizing_objective" type="text" class="qc-form-input input-s" name="classes_organizing_objective" value="{{ old('classes_organizing_objective', isset($planbyyear)?$planbyyear['classes_organizing_objective']:null)}}" /> 回／年
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('classes_organizing_objective'))
                            <span class="qc-form-error-message">{{ $errors->first('classes_organizing_objective') }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        {{ Form::close() }}
    </div>
<script>
        <?php 
            function encodeURI($uri)
            {
                return preg_replace_callback("{[^0-9a-z_.!~*'();,/?:@&=+$#-]}i", function ($m) {
                    return sprintf('%%%02X', ord($m[0]));
                }, $uri);

            }
        ?>
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
        var vision_Text = decodeURIComponent('<?php echo old('vision', isset($planbyyear)?  encodeURI($planbyyear['vision']):'') ?>');        
        if(vision_Text.indexOf("<p") == -1 && vision_Text.indexOf("<p/") == -1)
        {
            vision_Editor.root.innerHTML = "<p>"+vision_Text+"</p>";
        }
        else
        {
            vision_Editor.root.innerHTML = vision_Text;
        }
        
        var target_Editor = new Quill('#target-editor', {
            bounds: '#target-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        var target_Text = decodeURIComponent('<?php echo old('target', isset($planbyyear)? encodeURI($planbyyear['target']):'') ?>');
        if(target_Text.indexOf("<p") == -1 && target_Text.indexOf("<p/") == -1)
        {
            target_Editor.root.innerHTML = "<p>"+target_Text+"</p>";
        }
        else
        {
            target_Editor.root.innerHTML = target_Text;
        }        
        
        var motto_Editor = new Quill('#motto-editor', {
            bounds: '#motto-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        var motto_Text = decodeURIComponent('<?php echo old('motto', isset($planbyyear)? encodeURI($planbyyear['motto']):'') ?>');
        if(motto_Text.indexOf("<p") == -1 && motto_Text.indexOf("<p/") == -1)
        {
            motto_Editor.root.innerHTML = "<p>" + motto_Text + "</p>";
        }
        else
        {
            motto_Editor.root.innerHTML = motto_Text;
        }      
</script>
    <script>
        $("#btn-register").click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\""; 
            $('#vision').val(vision_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#target').val(target_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#motto').val(motto_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $("#form-register").submit()
        });
    </script>
@endsection
