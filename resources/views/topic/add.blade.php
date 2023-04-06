@extends('layouts.app')

@section('breadcrumbs')
    {{
      Breadcrumbs::for('toppage', function ($trail) {
          $trail->push('トップページ', route('toppageoffice'));
          }),
      Breadcrumbs::for('list', function ($trail) {
          $trail->parent('toppage');
          $trail->push('掲示板',route('category.index'));
          }),
      Breadcrumbs::for('add', function ($trail) {
          $trail->parent('list');
          $trail->push('トピック新規登録');
          })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ URL::to('topic/'.$thread->id)}}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" enctype="multipart/form-data" action="{{ action('TopicController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">
                        <span>カテゴリ名</span>
                    </td>
                    <td>
                        <span>{{$category->category_name}}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">スレッド名</td>
                    <td>
                        <span>{{$thread->thread_name}}</span>
                        <input id="thread_id" type="hidden" name="thread_id" value="{{$thread->id}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">トピック</td>
                    <td>
                        <!--<textarea id="topic"  class="qc-form-area-no-height qc-form-input-50" name="topic" rows="10" required >{{ old('topic') }}</textarea>-->
                        <input type="hidden" value="{{ old('topic') }}" name="topic" id="topic">
                        <div id="topic-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('topic'))
                            <span class="qc-form-error-message">{{ $errors->first('topic') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ファイル</td>
                    <td>
                        <input type="file" class="qc-file-input qc-form-input-50" name="image" id="image" />
                        @if ($errors->has('image'))
                            <span class="qc-form-error-message" style="vertical-align: middle">{{ $errors->first('image') }}</span>
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
        var noteEditor = new Quill('#topic-editor', {
            bounds: '#topic-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        noteEditor.root.innerHTML = '<?php echo old('topic') ?>' ;
</script>
    <script>
        $("#btn-register").click(function(){
            if($('#image').val()) {
                let file = document.getElementById('image');
                let filesize = file.files[0].size; // On older browsers this can return NULL.
                let filesizeMB = (filesize / (1024 * 1024)).toFixed(2);
                if (filesizeMB <= 5) {
                    var style = "\"margin-top: 0; margin-bottom: 0;\"";              
                    if(noteEditor.getText().trim() == "")
                    {
                        $('#topic').val(null);
                    }
                    else
                    {
                        $('#topic').val(noteEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                    }
                    // Allow the form to be submitted here.
                    $("#form-register").submit();
                } else {
                    // Don't allow submission of the form here.
                    alert('このフィールドは5MB以下で設定して下さい。');
                }
            } else {
                var style = "\"margin-top: 0; margin-bottom: 0;\"";              
                if(noteEditor.getText().trim() == "")
                {
                    $('#topic').val(null);
                }
                else
                {
                    $('#topic').val(noteEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                }
                $("#form-register").submit();
            }
        });
    </script>
@endsection
