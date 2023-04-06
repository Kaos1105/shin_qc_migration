@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
                })
    }}

    {{
        Breadcrumbs::for('promotioncircle', function ($trail, $year) {
                $trail->parent('toppage');
                $trail->push('サークル推進計画', URL::to('promotion-circle?year='.$year));
            })
    }}

    {{
        Breadcrumbs::for('add', function ($trail, $year) {
            $trail->parent('promotioncircle', $year);
            $trail->push('編集');
            })
    }}

    {{ Breadcrumbs::render('add', $promotion_cirlce->year) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ URL::to('promotion-circle?year='.$promotion_cirlce->year) }}">戻る</a>
    </div>
    <div class="container-fluid bottom-fix">
        {{ Form::model($promotion_cirlce, array('route' => array('promotioncircle.update', $promotion_cirlce->id), 'method' => 'PUT', 'id' => 'form-register')) }}
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($promotion_cirlce)?$promotion_cirlce['id']:null }}</span>
                        <input type="hidden" name="id" value="{{ old('id', isset($promotion_cirlce)?$promotion_cirlce['id']:null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">{{ __('サークル名') }}</td>
                    <td>
                        <span>{{ $circle_name }}</span>
                        <input id="circle_id" type="hidden" class="qc-form-input qc-form-input-50" name="circle_id" value="{{ old('circle_id', isset($promotion_cirlce)?$promotion_cirlce['circle_id']:null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">登録年度</td>
                    <td>
                        <span>{{ isset($promotion_cirlce)?$promotion_cirlce['year']:null }}</span>
                        <input id="year" type="hidden" class="qc-form-input qc-form-input-50" name="year" value="{{ old('year', isset($promotion_cirlce)?$promotion_cirlce['year']:null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">前年度の反省</td>
                    <td>
                        <span class="line-brake-preserve">{!!$review_last_year!!}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">職場方針</td>
                    <td>
<!--                        <textarea class="qc-form-area-5line qc-form-input-50" name="motto_of_the_workplace" >{{ old('motto_of_the_workplace', isset($promotion_cirlce)?$promotion_cirlce['motto_of_the_workplace']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('motto_of_the_workplace', isset($promotion_cirlce)?$promotion_cirlce['motto_of_the_workplace']:null)}}"
                               name="motto_of_the_workplace" id="motto_of_the_workplace">
                        <div id="motto_of_the_workplace-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">サークル方針</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="motto_of_circle" >{{ old('motto_of_circle', isset($promotion_cirlce)?$promotion_cirlce['motto_of_circle']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('motto_of_circle', isset($promotion_cirlce)?$promotion_cirlce['motto_of_circle']:null)}}"
                               name="motto_of_circle" id="motto_of_circle">
                        <div id="motto_of_circle-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">レベル</td>
                    <td>
                        <span>Ｘ軸 {{ isset($promotion_cirlce)?$promotion_cirlce['axis_x']:null }} 点　／　Ｙ軸 {{ isset($promotion_cirlce)?$promotion_cirlce['axis_y']:null }} 点</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合回数目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="target_number_of_meeting" value="{{ old('target_number_of_meeting', isset($promotion_cirlce)?$promotion_cirlce['target_number_of_meeting']:null)}}" /> 回／月
                        @if ($errors->has('target_number_of_meeting'))
                            <span class="qc-form-error-message">{{ $errors->first('target_number_of_meeting') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合時間目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="target_hour_of_meeting" value="{{ old('target_hour_of_meeting', isset($promotion_cirlce)?$promotion_cirlce['target_hour_of_meeting']:null)}}" /> ｈ／月
                        @if ($errors->has('target_hour_of_meeting'))
                            <span class="qc-form-error-message">{{ $errors->first('target_hour_of_meeting') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ﾃｰﾏ完了件数目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="target_case_complete" value="{{ old('target_case_complete', isset($promotion_cirlce)?$promotion_cirlce['target_case_complete']:null)}}" /> 件／年
                        @if ($errors->has('target_case_complete'))
                            <span class="qc-form-error-message">{{ $errors->first('target_case_complete') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">改善件数目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="improved_cases" value="{{ old('improved_cases', isset($promotion_cirlce)?$promotion_cirlce['improved_cases']:null)}}" /> 件／年
                        @if ($errors->has('improved_cases'))
                            <span class="qc-form-error-message">{{ $errors->first('improved_cases') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">勉強会開催目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="objectives_of_organizing_classe" value="{{ old('objectives_of_organizing_classe', isset($promotion_cirlce)?$promotion_cirlce['objectives_of_organizing_classe']:null)}}" /> 回／年
                        @if ($errors->has('objectives_of_organizing_classe'))
                            <span class="qc-form-error-message">{{ $errors->first('objectives_of_organizing_classe') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">今年度の反省</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="review_this_year" >{{ old('review_this_year', isset($promotion_cirlce)?$promotion_cirlce['review_this_year']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('review_this_year', isset($promotion_cirlce) ? $promotion_cirlce['review_this_year'] : null)}}"
                               name="review_this_year" id="review_this_year">
                        <div id="review_this_year-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">推進者コメント</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="comment_promoter" >{{ old('comment_promoter', isset($promotion_cirlce)?$promotion_cirlce['comment_promoter']:null)}}</textarea>-->
                        <input type="hidden" value="{{ old('comment_promoter', isset($promotion_cirlce)?$promotion_cirlce['comment_promoter']:null)}}"
                               name="comment_promoter" id="comment_promoter">
                        <div id="comment_promoter-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
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
        var motto_of_the_workplace_Editor = new Quill('#motto_of_the_workplace-editor', {
            bounds: '#motto_of_the_workplace-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });  
        var motto_of_the_workplace_Text = decodeURIComponent('<?php echo old('motto_of_the_workplace', isset($promotion_cirlce)? encodeURI($promotion_cirlce['motto_of_the_workplace']):'') ?>');        
        if(motto_of_the_workplace_Text.indexOf("<p") == -1 && motto_of_the_workplace_Text.indexOf("<p/") == -1)
        {
            motto_of_the_workplace_Editor.root.innerHTML = "<p>"+motto_of_the_workplace_Text+"</p>";
        }
        else
        {
            motto_of_the_workplace_Editor.root.innerHTML = motto_of_the_workplace_Text;
        }
        
        var motto_of_circle_Editor = new Quill('#motto_of_circle-editor', {
            bounds: '#motto_of_circle-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        var motto_of_circle_Text = decodeURIComponent('<?php echo old('motto_of_circle', isset($promotion_cirlce)? encodeURI($promotion_cirlce['motto_of_circle']):'') ?>');
        if(motto_of_circle_Text.indexOf("<p") == -1 && motto_of_circle_Text.indexOf("<p/") == -1)
        {
            motto_of_circle_Editor.root.innerHTML = "<p>"+motto_of_circle_Text+"</p>";
        }
        else
        {
            motto_of_circle_Editor.root.innerHTML = motto_of_circle_Text;
        }        
        
        var review_this_year_Editor = new Quill('#review_this_year-editor', {
            bounds: '#review_this_year-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        var review_this_year_Text = decodeURIComponent('<?php echo old('review_this_year', isset($promotion_cirlce)? encodeURI($promotion_cirlce['review_this_year']):'') ?>');
        if(review_this_year_Text.indexOf("<p") == -1 && review_this_year_Text.indexOf("<p/") == -1)
        {
            review_this_year_Editor.root.innerHTML = "<p>" + review_this_year_Text + "</p>";
        }
        else
        {
            review_this_year_Editor.root.innerHTML = review_this_year_Text;
        }
        
        var comment_promoter_Editor = new Quill('#comment_promoter-editor', {
            bounds: '#comment_promoter-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        var comment_promoter_Text = decodeURIComponent('<?php echo old('comment_promoter', isset($promotion_cirlce)? encodeURI($promotion_cirlce['comment_promoter']):'') ?>');        
        if(comment_promoter_Text.indexOf("<p") == -1 && comment_promoter_Text.indexOf("<p/") == -1)
        {
            comment_promoter_Editor.root.innerHTML = "<p>" + comment_promoter_Text + "</p>";
        }
        else
        {
            comment_promoter_Editor.root.innerHTML = comment_promoter_Text;
        }
</script>
    <script>
        $("#btn-register").click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\"";          
            $('#motto_of_the_workplace').val(motto_of_the_workplace_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#motto_of_circle').val(motto_of_circle_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#review_this_year').val(review_this_year_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#comment_promoter').val(comment_promoter_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $("#form-register").submit()
        });
    </script>
@endsection
