@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ＱＡ画面登録（一覧）',route('qa.getList'));
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
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ route('qa.getList') }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" enctype="multipart/form-data" action="{{ action('QaController@postAdd') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td> </td>
                </tr>
                <tr>
                    <td class="td-first">ストーリー</td>
                    <td>
                        <select class="qc-form-input input-l" name="story_id">
                            @foreach($story as $story_item)
                                <option value="{{$story_item->id}}" @if(old('story_id') == $story_item->id)selected @endif>{{$story_item->story_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">画面番号</td>
                    <td>
                        <input type="text" class="qc-form-input input-s" name="screen_id" value="{{old('screen_id')}}" />
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('screen_id'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('screen_id') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">タイトル</td>
                    <td>
                        <input class="qc-form-input input-l" type="text" id="title" name="title" value="{{old('title')}}"/>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('title'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('title') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
<!--                        <textarea class="qc-form-area input-l" id="note" name="note">{{old('note')}}</textarea>-->
                        <input type="hidden" value="{{old('note')}}" name="note" id="note">
                        <div id="note-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <span>{{$new_display_order}}</span>
                        <input type="hidden" name="display_order" value="{{$new_display_order}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1"
                                       name="use_classification"
                                       value="1" @if(old('use_classification') == 1) checked @endif><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2"
                                       name="use_classification"
                                       value="2" @if(old('use_classification') != 1) checked @endif><span>使用する</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\"";
            $('#note').val(noteEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
            $("#form-register").submit();
        });
    </script>
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
        var noteEditor = new Quill('#note-editor', {
            bounds: '#note-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        noteEditor.root.innerHTML = '<?php echo old('note') ?>' ;
</script>
@endsection
