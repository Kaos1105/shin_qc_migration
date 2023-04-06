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
       Breadcrumbs::for('edit', function ($trail) {
           $trail->parent('list');
           $trail->push('カテゴリ修正');
           })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        @if($category->deletable != 0)
        <a class="btn btn-add" id="btn-delete">削除</a>
        @endif
        <a class="btn btn-back" href="{{ URL::to('thread/'.$category->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($category, array('route' => array('category.update', $category->id), 'method' => 'PUT', 'id' => 'form-register')) }}
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($category)? \App\Enums\Common::show_id($category['id']) : null }}</span>
                        <input type="hidden" name="id" value="{{ old('id', isset($category)?$category['id']:null)}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">カテゴリ名</td>
                    <td>
                        <input id="category_name" type="text" class="qc-form-input qc-form-input-50"
                               name="category_name" value="{{ $category->category_name }}" required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('category_name'))
                            <span class="qc-form-error-message">{{ $errors->first('category_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
<!--                        <textarea id="note" class="qc-form-area qc-form-input-50"
                                  name="note">{{ $category->note }}</textarea>-->
                        <input type="hidden" value="{{ $category->note }}" name="note" id="note">
                        <div id="note-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1"
                                       name="use_classification"
                                       value="1"><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2"
                                       name="use_classification"
                                       checked value="2"><span>使用する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span>{{ date_format(date_create($category->updated_at),"Y/m/d H:i:s") }} {{$user_updated_by}}</span>
                    </td>
                </tr>
            </table>
        {{ Form::close() }}
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['category.destroy', $category->id]]) !!}
        {!! Form::close() !!}
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
        var noteEditor = new Quill('#note-editor', {
            bounds: '#note-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        var note_Text = decodeURIComponent('<?php echo encodeURI($category->note) ?>');        
        if(note_Text.indexOf("<p") == -1 && note_Text.indexOf("<p/") == -1)
        {
            noteEditor.root.innerHTML = "<p>"+note_Text+"</p>";
        }
        else
        {
            noteEditor.root.innerHTML = note_Text;
        }
</script>
    <script>
        $("#btn-register").click(function () {
            var style = "\"margin-top: 0; margin-bottom: 0;\"";
            $('#note').val(noteEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $("#form-register").submit()
        });
        $("#btn-delete").click(function(){
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Category }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
        @if ($errors->has('delete_using'))
        alert('{{ $errors->first('delete_using')}}');
        @endif

        var use_classification1 = $('#use_classification1'),
            use_classification2 = $('#use_classification2');
        if (use_classification1.val() == {{$category->use_classification}}) {
            use_classification1.prop('checked', true);
        } else {
            use_classification2.prop('checked', true);
        }

    </script>

@endsection
