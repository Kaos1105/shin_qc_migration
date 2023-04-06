@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
                }),

        Breadcrumbs::for('promotioncircle', function ($trail, $year) {
                $trail->parent('toppage');
                $trail->push('サークル推進計画', URL::to('promotion-circle?year='.$year));
            }),

        Breadcrumbs::for('add', function ($trail, $year) {
            $trail->parent('promotioncircle', $year);
            $trail->push('編集');
            })
    }}
    {{ Breadcrumbs::render('add', $year)}}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid bottom-fix">
        <form id="form-register" method="POST" action="{{ action('PromotionCircleController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">{{ __('サークル名') }}</td>
                    <td>
                        @if(session()->exists('circle'))
                        <span>{{ session('circle.circle_name') }}</span>
                        <input id="circle_id" type="hidden" class="qc-form-input qc-form-input-50" name="circle_id" value="{{ session('circle.id') }}" />
                        @endif
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
                    <td class="td-first">前年度の反省</td>
                    <td>
                        <span class="line-brake-preserve">{!!$review_last_year!!}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">職場方針</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="motto_of_the_workplace" >{{ old('motto_of_the_workplace') }}</textarea>-->
                        <input type="hidden" value="{{ old('motto_of_the_workplace')}}"
                               name="motto_of_the_workplace" id="motto_of_the_workplace">
                        <div id="motto_of_the_workplace-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">サークル方針</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="motto_of_circle" >{{ old('motto_of_circle') }}</textarea>-->
                        <input type="hidden" value="{{ old('motto_of_circle') }}"
                               name="motto_of_circle" id="motto_of_circle">
                        <div id="motto_of_circle-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">レベル</td>
                    <td>
                        <span>Ｘ軸 0.0 点　／　Ｙ軸 0.0 点</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合回数目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="target_number_of_meeting" value="{{ old('target_number_of_meeting') }}" /> 回／月
                        @if ($errors->has('target_number_of_meeting'))
                            <span class="qc-form-error-message">{{ $errors->first('target_number_of_meeting') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">会合時間目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="target_hour_of_meeting" value="{{ old('target_hour_of_meeting') }}" /> ｈ／月
                        @if ($errors->has('target_hour_of_meeting'))
                            <span class="qc-form-error-message">{{ $errors->first('target_hour_of_meeting') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ﾃｰﾏ完了件数目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="target_case_complete" value="{{ old('target_case_complete') }}" /> 件／年
                        @if ($errors->has('target_case_complete'))
                            <span class="qc-form-error-message">{{ $errors->first('target_case_complete') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">改善件数目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="improved_cases" value="{{ old('improved_cases') }}" /> 件／年
                        @if ($errors->has('improved_cases'))
                            <span class="qc-form-error-message">{{ $errors->first('improved_cases') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">勉強会開催目標</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="objectives_of_organizing_classe" value="{{ old('objectives_of_organizing_classe') }}" /> 回／年
                        @if ($errors->has('objectives_of_organizing_classe'))
                            <span class="qc-form-error-message">{{ $errors->first('objectives_of_organizing_classe') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">今年度の反省</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="review_this_year" >{{ old('review_this_year') }}</textarea>-->
                        <input type="hidden" value="{{ old('review_this_year') }}"
                               name="review_this_year" id="review_this_year">
                        <div id="review_this_year-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td class="td-first">推進者コメント</td>
                    <td>
                        <!--<textarea class="qc-form-area-5line qc-form-input-50" name="comment_promoter" >{{ old('comment_promoter') }}</textarea>-->
                        <input type="hidden" value="{{ old('comment_promoter') }}"
                               name="comment_promoter" id="comment_promoter">
                        <div id="comment_promoter-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
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
        var motto_of_the_workplace_Editor = new Quill('#motto_of_the_workplace-editor', {
            bounds: '#motto_of_the_workplace-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });  
        motto_of_the_workplace_Editor.root.innerHTML = '<?php echo old('motto_of_the_workplace', isset($promotion_cirlce)? $promotion_cirlce['motto_of_the_workplace']:'') ?>';        
      
        var motto_of_circle_Editor = new Quill('#motto_of_circle-editor', {
            bounds: '#motto_of_circle-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        motto_of_circle_Editor.root.innerHTML = '<?php echo old('motto_of_circle', isset($promotion_cirlce)? $promotion_cirlce['motto_of_circle']:'') ?>';
        
        var review_this_year_Editor = new Quill('#review_this_year-editor', {
            bounds: '#review_this_year-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        review_this_year_Editor.root.innerHTML = '<?php echo old('review_this_year', isset($promotion_cirlce)? $promotion_cirlce['review_this_year']:'') ?>';
        
        var comment_promoter_Editor = new Quill('#comment_promoter-editor', {
            bounds: '#comment_promoter-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });        
        comment_promoter_Editor.root.innerHTML = '<?php echo old('comment_promoter', isset($promotion_cirlce)? $promotion_cirlce['comment_promoter']:'') ?>';        
        
</script>
    <script>
        $("#btn-register").click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\"";          
            $('#motto_of_the_workplace').val(motto_of_the_workplace_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#motto_of_circle').val(motto_of_circle_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#review_this_year').val(review_this_year_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $('#comment_promoter').val(comment_promoter_Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">")); 
            $("#form-register").submit()
        });
    </script>
@endsection
