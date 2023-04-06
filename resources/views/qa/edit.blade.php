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
        Breadcrumbs::for('edit', function ($trail) {
            $trail->parent('list');
            $trail->push('修正');
            })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ route('qa.getShow', $qa->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{--{{ Form::model($qa, array('route' => array('qa.postEdit', $qa->id), 'method' => 'post', 'id' => 'form-register', 'enctype' => 'multipart/form-data')) }}--}}
        <form id="form-register" method="POST" enctype="multipart/form-data" action="">
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
                            <option value="{{$story_item->id}}" @if(old('story_id') == $story_item->id) selected @elseif ($qa->story_id == $story_item->id)selected @endif>{{$story_item->story_name}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="td-first">画面番号</td>
                <td>
                    <input type="text" class="qc-form-input input-s" name="screen_id" value="{{$qa->screen_id}}" />
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
                    <input class="qc-form-input input-l" type="text" id="title" name="title" value="{{$qa->title}}"/>
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
<!--                    <textarea class="qc-form-area input-l" id="note" name="note">{{$qa->note}}</textarea>-->
                        <input type="hidden" value="{{$qa->note}}" name="note" id="note">
                        <div id="note-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                </td>
            </tr>
            <tr>
                <td class="td-first">表示順番号</td>
                <td>
                    <span>{{$qa->display_order}}</span>
                    <input type="hidden" name="display_order" value="{{$qa->display_order}}"/>
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
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{ date_format(date_create($qa->updated_at),"Y/m/d H:i:s") }} {{$user_updated_by}}</span>
                </td>
            </tr>
        </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\"";
            $('#note').val(noteEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p><br><\/p>/g,"").replace(/<p>/g,"<p style=" + style +">")); 
            $("#form-register").submit();
        });
    </script>
    <script>
        var use_classification1 = $('#use_classification1'),
            use_classification2 = $('#use_classification2');
        if (use_classification1.val() == {{$qa->use_classification}}) {
            use_classification1.prop('checked', true);
        } else {
            use_classification2.prop('checked', true);
        }
    </script>
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
        var note_Text = decodeURIComponent('<?php echo encodeURI($qa->note) ?>');        
        if(note_Text.indexOf("<p") == -1 && note_Text.indexOf("<p/") == -1)
        {
            noteEditor.root.innerHTML = "<p>"+note_Text+"</p>";
        }
        else
        {
            noteEditor.root.innerHTML = note_Text;
        }
</script>
@endsection
