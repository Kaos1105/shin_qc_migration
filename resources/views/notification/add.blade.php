@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('お知らせ管理',route('notification.index'));
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
        <a class="btn btn-back" href="{{ route('notification.index') }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('NotificationController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ページ区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="notification_classify1"
                                       name="notification_classify"
                                       value="1"
                                       @if(old('notification_classify') == 1) checked @endif
                                />
                                <span>ログインページ</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="notification_classify2"
                                       name="notification_classify"
                                       checked value="2"
                                       @if(old('notification_classify') == 2 || !old('notification_classify')) checked @endif
                                />
                                <span>トップページ</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">メッセージ</td>
                    <td class="position-relative">
<!--                        <textarea class="qc-form-area qc-form-input-50" name="message"
                                  required>{{ old('message', isset($notification)?$notification['message'] : null) }}</textarea>-->
                        <input type="hidden" value="{{ old('message', isset($notification)?$notification['message'] : null) }}"
                               name="message" id="message">
                        <div id="message-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div> 
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('message'))
                            <span class="qc-form-error-message">{{ $errors->first('message') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">掲載期間</td>
                    <td>
                        <span>開始</span>
                            <input id='date_timepicker_start' type='text'
                                   class="qc-form-input input-s"
                                   name="date_start" autocomplete="off"
                                   value="@if(!isset($notification->date_start)){{old('date_start')}}@else{{date_format(date_create($notification->date_start),"Y/m/d H:i")}}@endif"
                            />
                            <img id="picker-start-icon" class="date-selector-icon" src="{{ asset('images/calendar_24px.png')}}" alt="date"
                                 height="24" width="24" style="margin-bottom: 4px">

                        <span style="margin-left: 20px">終了</span>
                            <input id='date_timepicker_end' type='text'
                                   class="qc-form-input input-s"
                                   name="date_end" autocomplete="off"
                                   value="@if(!isset($notification->date_end)){{old('date_end')}}@else{{date_format(date_create($notification->date_end),"Y/m/d H:i")}}@endif"
                            />
                            <img id="picker-end-icon" class="date-selector-icon" src="{{ asset('images/calendar_24px.png')}}" alt="date"
                                 height="24" width="24" style="margin-bottom: 4px">

                        <span id="invalidMsg" class="text-danger d-none"></span>
                        @if ($errors->has('date_start'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('date_start') }}</span>
                        @endif
                        @if ($errors->has('date_end'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('date_end') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input type="number" id="display_order" name="display_order" class="qc-form-input input-s"
                               value="{{ old('display_order', isset($notification->display_order)? $notification->display_order : '999') }}"/>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1"
                                       name="use_classification"
                                       value="1"
                                       @if(old('use_classification') == 1) checked @endif
                                />
                                <span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2"
                                       name="use_classification"
                                       value="2"
                                       @if(old('use_classification') == 2 || !old('use_classification')) checked @endif
                                />
                                <span>使用する</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span></span>
                        <input id="created_at" type="hidden" name="created_at">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function () {
            let start = moment($('#date_timepicker_start').val());
            let end = moment($('#date_timepicker_end').val());
            if(start >= end) {
                alert('{{\App\Enums\StaticConfig::$Time_Range_Constrain}}');
            } else {
                var style = "\"margin-top: 0; margin-bottom: 0;\"";
                if(messageEditor.getText().trim() == "")
                {
                    $('#message').val(null);
                }
                else
                {
                    $('#message').val(messageEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));  
                }
                $("#form-register").submit();
            }
        });

        $(function () {
            dtpicker();
        });
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
        var messageEditor = new Quill('#message-editor', {
            bounds: '#message-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        var message_Text = decodeURIComponent('<?php echo old('message', isset($notification)? encodeURI($notification['message']) : '') ?>');        
        if(message_Text.indexOf("<p") == -1 && message_Text.indexOf("<p/") == -1)
        {
            messageEditor.root.innerHTML = "<p>"+message_Text+"</p>";
        }
        else
        {
            messageEditor.root.innerHTML = message_Text;
        }
</script>
@endsection
