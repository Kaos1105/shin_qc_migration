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
            $trail->push('スレッド新規登録');
       })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ URL::to('thread/'.$category->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('ThreadController@store') }}">
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
                        <span>{{$category -> category_name}}</span>
                        <input id="category_id" type="hidden" name="category_id" value="{{$category->id}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">スレッド名</td>
                    <td>
                        <input id="thread_name" type="text" class="qc-form-input qc-form-input-50" name="thread_name"
                               value="{{ old('thread_name') }}" required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('thread_name'))
                            <span class="qc-form-error-message">{{ $errors->first('thread_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
<!--                        <textarea id="note" class="qc-form-area qc-form-input-50"
                                  name="note">{{ old('note') }}</textarea>-->
                        <input type="hidden" value="{{ old('note') }}" name="note" id="note">
                        <div id="note-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1" name="is_display"
                                       value="1"><span>更新通知(通知しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2" name="is_display"
                                       checked value="2"><span>通知する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span></span>
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
    <script>
        $("#btn-register").click(function () {
            var style = "\"margin-top: 0; margin-bottom: 0;\"";
            $('#note').val(noteEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
            $("#form-register").submit()
        });
    </script>
@endsection
