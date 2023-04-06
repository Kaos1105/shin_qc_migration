@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
            }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('書庫管理',route('library.index'));
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
        <a class="btn btn-back" href="{{ URL::to('library/'.$library->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($library, array('route' => array('library.update', $library->id), 'method' => 'PUT', 'id' => 'form-register', 'enctype' => 'multipart/form-data')) }}
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($library)? \App\Enums\Common::show_id($library['id']) : null }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">文書区分</td>
                    <td>
                        <input type="text" class="qc-form-input input-m" id="library_type" name="library_type" value="{{$library->library_type}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">タイトル</td>
                    <td>
                        <input id="title" type="text" class="qc-form-input qc-form-input-50" name="title" value="{{ $library->title}}" required>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('title'))
                            <span class="qc-form-error-message">{{ $errors->first('title') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ファイル</td>
                    <td>
                        <span class="d-block">現行ファイル: {{$fileNameDisp}}</span>
                        <input class="qc-file-input qc-form-input-50" type="file" name="file_upload" id="file_upload" value=""/>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('file_upload'))
                            <span class="qc-form-error-message">{{ $errors->first('file_upload') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
                        <textarea class="qc-form-area qc-form-input-50" name="note">{{$library->note}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">掲載期間</td>
                    <td>
                        <span>開始</span>
                            <input id='date_timepicker_start' type='text'
                                   class="qc-form-input input-s"
                                   name="date_start"
                                   value="@if(isset($library->date_start)){{date_format(date_create($library->date_start),"Y/m/d H:i")}} @endif"
                                   autocomplete="off"
                            />
                            <img id="picker-start-icon" class="date-selector-icon" src="{{ asset('images/calendar_24px.png')}}" alt="date" height="24" width="24" style="margin-bottom: 4px">

                        <span style="margin-left: 20px">終了</span>
                            <input id='date_timepicker_end' type='text'
                                   class="qc-form-input input-s"
                                   name="date_end"
                                   value="@if(isset($library->date_end)){{date_format(date_create($library->date_end),"Y/m/d H:i")}} @endif"
                                   autocomplete="off"
                            />
                            <img id="picker-end-icon" class="date-selector-icon" src="{{ asset('images/calendar_24px.png')}}" alt="date" height="24" width="24" style="margin-bottom: 4px">

                        <span class="qc-form-required" style="top: 35%; margin-left: 32px;">未指定常時掲載</span>
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
                        <input type="number" id="display_order" name="display_order" class="qc-form-input input-s" value="{{$library->display_order}}" />
                        <span class="qc-form-required" style="top: 35%">必須</span>
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
                        <span>{{date_format(date_create($library->updated_at),"Y/m/d H:i:s")}} {{$user_updated_by_name}}</span>
                    </td>
                </tr>
            </table>
        {{ Form::close() }}
    </div>
    <script>
        $("#btn-register").click(function(){
            if($('#file_upload').val()) {
                let file = document.getElementById('file_upload');
                let filesize = file.files[0].size; // On older browsers this can return NULL.
                let filesizeMB = (filesize / (1024 * 1024)).toFixed(2);
                console.log(filesizeMB);
                if (filesizeMB <= 10) {
                    // Allow the form to be submitted here.
                    let start = moment($('#date_timepicker_start').val());
                    let end = moment($('#date_timepicker_end').val());
                    if (start >= end) {
                        alert('{{\App\Enums\StaticConfig::$Time_Range_Constrain}}');
                    } else {
                        $("#form-register").submit();
                    }
                } else {
                    // Don't allow submission of the form here.
                    alert('このフィールドは10MB以下で設定して下さい。');
                }
            } else {
                let start = moment($('#date_timepicker_start').val());
                let end = moment($('#date_timepicker_end').val());
                if (start >= end) {
                    alert('{{\App\Enums\StaticConfig::$Time_Range_Constrain}}');
                } else {
                    $("#form-register").submit();
                }
            }
        });

        $(function () {
            dtpicker();
        });
    </script>
    <script>
        var use_classification1 = $('#use_classification1'),
            use_classification2 = $('#use_classification2');
        if (use_classification1.val() == {{$library->use_classification}}) {
            use_classification1.prop('checked', true);
        } else {
            use_classification2.prop('checked', true);
        }
    </script>
    <script>
        $(document).ready(function() {
            var arrayOfOptions = [
                @if(isset($suggestion))
                    @foreach($suggestion as $suggestion_item)
                    '{{$suggestion_item->library_type}}',
                    @endforeach
                @endif
            ];
            $("#library_type").autocomplete({
                source: arrayOfOptions,
                minLength: 0,
                search: "",
                classes: {
                    "ui-autocomplete": "l-suggestion-item"
                }
            }).bind('focus', function(){ $(this).autocomplete("search"); } );
        });
    </script>
@endsection
