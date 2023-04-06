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
        $trail->push('スレッド修正');
        })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)
            <a class="btn btn-add" id="btn-delete">削除</a>
        @endif
        <a class="btn btn-back" href=" {{ URL::to('topic/'.$thread->id) }} ">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($thread, array('route' => array('thread.update', $thread->id), 'method' => 'PUT', 'id' => 'form-register')) }}
        @csrf
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($thread)? \App\Enums\Common::show_id($thread['id']) : null }}</span>
                    <input type="hidden" name="id" value="{{ old('id', isset($thread)?$thread['id']:null)}}"/>
                </td>
            </tr>
            <tr>
                <td class="td-first">カテゴリ名</td>
                <td>
                    <span>{{ $category->category_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">スレッド名</td>
                <td>
                    <input id="thread_name" type="text" class="qc-form-input qc-form-input-50"
                           name="thread_name" value="{{ $thread->thread_name }}" required>
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
                                  name="note">{{ $thread->note }}</textarea>-->
                        <input type="hidden" value="{{ $thread->note }}" name="note" id="note">
                        <div id="note-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                </td>
            </tr>
            <tr>
                <td class="td-first">更新通知</td>
                <td>
                    <div class="qc-form-radio-group">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="is_display1"
                                   name="is_display"
                                   value="1"><span>更新通知(通知しない</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="is_display2"
                                   name="is_display"
                                   checked value="2"><span>通知する</span>
                        </label>

                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-first">新規登録日</td>
                <td>
                    <span>{{ date_format(date_create($thread->updated_at),"Y/m/d H:i:s") }} {{$user_updated_by}}</span>
                </td>
            </tr>
        </table>
        {{ Form::close() }}
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['thread.destroy', $thread->id]]) !!}
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
        var note_Text = decodeURIComponent('<?php echo encodeURI($thread->note) ?>');        
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
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Thread }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
        @if ($errors->has('delete_using'))
        alert('{{ $errors->first('delete_using')}}');
                @endif

        var is_display1 = $('#is_display1'),
            is_display2 = $('#is_display2');
        if (is_display1.val() == {{$thread->is_display}}) {
            is_display1.prop('checked', true);
        } else {
            is_display2.prop('checked', true);
        }
    </script>

@endsection
