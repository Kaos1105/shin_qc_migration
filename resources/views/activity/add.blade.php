@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('活動記録', URL::to('activity'));
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
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid bottom-fix">
        <form id="form-register" method="POST" action="{{ route('activity.store') }}">
            @csrf
            <?php
                $filterYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
            ?>
            <input type="hidden" value="{{ $filterYear }}" name="filterYear">
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">{{ __('サークル名') }}</td>
                    <td>
                        <span>{{ $circle->circle_name }}</span>
                        <input type="hidden" value="{{ session('circle.id') }}" name="circle_id">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">活動区分</td>
                    <td>
                        <select name="activity_category" class="qc-form-select">
                            <option value="{{ App\Enums\Activity::MEETING }}"
                                    @if(old('activity_category') == App\Enums\Activity::MEETING)
                                    selected
                                    @elseif(isset($activity) && $activity->activity_category == App\Enums\Activity::MEETING)
                                    selected
                                @endif
                            >会合
                            </option>
                            <option value="{{ App\Enums\Activity::STUDY_GROUP }}"
                                    @if(old('activity_category') == App\Enums\Activity::STUDY_GROUP)
                                    selected
                                    @elseif(isset($activity) && $activity->activity_category == App\Enums\Activity::STUDY_GROUP)
                                    selected
                                @endif
                            >勉強会
                            </option>
                            <option value="{{ App\Enums\Activity::KAIZEN }}"
                                    @if(old('activity_category') == App\Enums\Activity::KAIZEN)
                                    selected
                                    @elseif(isset($activity) && $activity->activity_category == App\Enums\Activity::KAIZEN)
                                    selected
                                @endif
                            >改善提案
                            </option>
                            <option value="{{ App\Enums\Activity::OTHER }}"
                                    @if(old('activity_category') == App\Enums\Activity::OTHER)
                                    selected
                                    @elseif(isset($activity) && $activity->activity_category == App\Enums\Activity::OTHER)
                                    selected
                                @endif
                            >その他
                            </option>
                        </select>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('activity_category'))
                            <span class="qc-form-error-message">{{ $errors->first('activity_category') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">タイトル</td>
                    <td>
                        <input type="text" class="qc-form-input qc-form-input-50"
                               value="{{ old('activity_title', isset($activity)? $activity->activity_title : null) }}"
                               name="activity_title">
<!--                        <input type="hidden" value="{{ old('activity_title', isset($activity)? $activity->activity_title : null) }}"
                               name="activity_title" id="activity_title">
                        <div id="activity-title-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>-->
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('activity_title'))
                            <span class="qc-form-error-message">{{ $errors->first('activity_title') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">予定日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_intended"
                               value="{{ old('date_intended', isset($activity)? date('Y/m/d', strtotime($activity->date_intended)) : null) }}"
                               name="date_intended" autocomplete="off">
                        <img id="date-icon-1" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}"
                             alt="date-icon">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('date_intended'))
                            <span class="qc-form-error-message">{{ $errors->first('date_intended') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">予定時刻</td>
                    <td>
                        <input type="text" class="qc-form-input qc-form-input-10" id="time_intended"
                               value="{{ old('time_intended', isset($activity)? date("H:i", strtotime($activity->time_intended)) : null) }}"
                               name="time_intended">
                        <span class="qc-form-required">必須</span>
                        <span class="msg-intended text-danger" style="display: none">[予定時刻]時間は無効です。</span>
                        @if ($errors->has('time_intended'))
                            <span class="qc-form-error-message">{{ $errors->first('time_intended') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">実施日</td>
                    <td>
                        <input type="text" class="qc-form-input input-s qc-datepicker" id="date_execution"
                               value="{{ old('date_execution', isset($activity)? date('Y/m/d', strtotime($activity->date_execution)) : null) }}"
                               name="date_execution" autocomplete="off">
                        <img id="date-icon-2" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}"
                             alt="date-icon">
                        @if ($errors->has('date_execution'))
                            <span class="qc-form-error-message">{{ $errors->first('date_execution') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <?php
                    if (isset($activity)) {
                        $time = \App\Enums\Common::total_time($activity->time_start, $activity->time_finish);
                    }
                    ?>
                    <td class="td-first">実施時刻</td>
                    <td>
                        開始<input id="time_start" type="text" class="qc-form-input qc-form-input-10"
                                 value="{{ old('time_start', isset($activity)? date("H:i", strtotime($activity->time_start)) : null) }}"
                                 name="time_start" onchange="checkTime();">
                        終了<input id="time_finish" type="text" class="qc-form-input qc-form-input-10"
                                 value="{{ old('time_finish', isset($activity)? date("H:i", strtotime($activity->time_finish)) : null) }}"
                                 name="time_finish" onchange="checkTime();">
                        時間　<span
                            id="time_total">{{ isset($time)? \App\Enums\Common::two_number_after_comma($time) : '--' }}</span>
                        h
                        <input type="hidden" id="time_span" name="time_span" value=""/>
                        <span class="msg text-danger" style="display: none">[開始]時間は無効です。</span>
                        <span class="msg2 text-danger" style="display: none">[終了]時間は無効です。</span>
                        @if ($errors->has('time_start'))
                            <span class="qc-form-error-message"
                                  id="time-start-msg">{{ $errors->first('time_start') }}</span>
                        @endif
                        @if ($errors->has('time_finish'))
                            <span class="qc-form-error-message"
                                  id="time-finish-msg">{{ $errors->first('time_finish') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">参加者数</td>
                    <td>
                        <input type="text" class="qc-form-input qc-form-input-10"
                               value="{{ old('participant_number', isset($activity)? $activity->participant_number : null) }}"
                               name="participant_number"> 人
                        @if ($errors->has('participant_number'))
                            <span class="qc-form-error-message">{{ $errors->first('participant_number') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">場所</td>
                    <td>
                        <input type="text" class="qc-form-input input-l" id="location"
                               value="{{ old('location', isset($activity)? $activity->location : null) }}"
                               name="location">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">参加者名</td>
                    <td>
                        <input type="text" class="qc-form-input qc-form-input-50"
                               name="content1"
                               value="{{ old('content1', isset($activity)? $activity->content1 : (isset($content1)? $content1 : null)) }}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">内容</td>
                    <td>
                        <span class="d-block">できるだけ5W1Hで記入すること。※会合時間は30分を推奨とし、1時間以内で行う。</span>
<!--                        <textarea type="text" class="qc-form-area-fluid qc-form-input-50" rows="15"
                                  name="content2">{{ old('content2', isset($activity)? $activity->content2 : null) }}</textarea>-->
                        <input type="hidden" value="{{ old('content2', isset($activity)? $activity->content2 : null) }}"
                               name="content2" id="content2">
                        <div id="content2-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                        <input type="file" class="dialog-file-input dialog-l" name="attachmentContent"
                           id="multipleFileContent"/>
                        <div id="previewUploadFile">
                            <?php
                                $fileIds = '';
                                $count = 0;
                            ?>
                            @foreach ($ContentFiles as $contentfile)
                                <span><a href="{{route("uploadfile.downloadFile",$contentfile->id)}}">{{$contentfile->FileNameOriginal}}</a>
                                <i class="fa fa-trash" style="cursor:pointer" onclick="deleteFileOutActivity( {{ $contentfile->id }} )" id="{{$contentfile->id}}"></i><br/></span>
                                <?php
                                    $fileIds .= $contentfile->id;
                                    $count++;
                                    if($count < count($ContentFiles))
                                    {
                                        $fileIds .= ',';
                                    }
                                ?>
                            @endforeach
                        </div>
                        <input type="hidden" name="contentFileIds" id="contentFileIds" value="<?php echo $fileIds; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">上司への要望</td>
                    <td>
                        <span class="d-block">上司に対する要望事項</span>
<!--                        <textarea type="text" class="qc-form-area qc-form-input-50"
                                  name="content3">{{ old('content3', isset($activity)? $activity->content3 : null) }}</textarea>-->
                        <input type="hidden" value="{{ old('content3', isset($activity)? $activity->content3 : null) }}"
                               name="content3" id="content3">
                        <div id="content3-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">上司記入欄</td>
                    <td>
                        <span class="d-block">上司の指導・助言・初見</span>
<!--                        <textarea type="text" class="qc-form-area qc-form-input-50"
                                  name="content4">{{ old('content4', isset($activity)? $activity->content4 : null) }}</textarea>-->
                        <input type="hidden" value="{{ old('content4', isset($activity)? $activity->content4 : null) }}"
                               name="content4" id="content4">
                        <div id="content4-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">事務局記入欄</td>
                    <td>
                        <span class="d-block">ＱＣサークル推進者、事務局</span>
<!--                        <textarea type="text" class="qc-form-area qc-form-input-50"
                                  name="content5">{{ old('content5', isset($activity)? $activity->content5 : null) }}</textarea>-->
                        <input type="hidden" value="{{ old('content5', isset($activity)? $activity->content5 : null) }}"
                               name="content5" id="content5">
                        <div id="content5-editor" style="margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;"></div>
                    </td>
                </tr>
            </table>
            <div style="margin:5px 0px;">
                テーマ別活動記録
            </div>
            <table class="table-form-60" border="1">
                <tr class="text-center">
                    <td>テーマ名</td>
                    <td style="width: 50%">内容</td>
                    <td style="width: 15%; min-width: 120px">工数</td>
                </tr>
                <?php $stt = 0; ?>
                @foreach($theme_list as $item)
                    <?php
                    $content = '';
                    $time = '';
                    if (isset($activity_other)) {
                        foreach ($activity_other as $activity_other_item) {
                            if ($activity_other_item->theme_id == $item->id) {
                                $content = $activity_other_item->content;
                                $time = $activity_other_item->time;
                                break;
                            }
                        }
                    }
                    ?>
                    <input type="hidden" name="theme_id[]" value="{{ $item->id }}"/>
                    <tr>
                        <td>{{ $item->theme_name }}</td>
                        <td><input type="text" name="contents[]" value="{{ old('contents.'.$stt, $content) }}"
                                   class="qc-form-input qc-form-input-100"/></td>
                        <td><input type="number" name="time[]" value="{{ old('time.'.$stt++, $time) }}"
                                   class="qc-form-input qc-form-input-80 d-inline-block"
                                   id="time_{{ $item->id }}"/><span style="padding-left: 6px">h</span>
                            <span class="text-danger" id="msg_time_{{ $item->id }}"></span>
                            <script>
                                $(function () {
                                    $('#time_{{ $item->id }}').on('keyup', function () {
                                        if ($('#time_{{ $item->id }}').val() < 0) {
                                            $('#msg_time_{{ $item->id }}').text('非負のみ');
                                            $('#btn-register').attr('disabled', true);
                                        } else {
                                            $('#msg_time_{{ $item->id }}').text('');
                                            $('#btn-register').attr('disabled', false);
                                        }
                                    });
                                });
                            </script>
                        </td>
                    </tr>

                @endforeach
            </table>
        </form>
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
//        var actitivityTitleEditor = new Quill('#activity-title-editor', {
//            bounds: '#activity-title-editor',
//            modules: {
//                'toolbar': toolbar,
//            },
//            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
//            theme: 'snow'
//        });
//        actitivityTitleEditor.root.innerHTML = '<?php echo old('activity_title', isset($activity)? $activity->activity_title : '') ?>' ;

        var content2Editor = new Quill('#content2-editor', {
            bounds: '#content2-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        var content2_Text = decodeURIComponent('<?php echo old('content2', isset($activity)? encodeURI($activity->content2) : '') ?>');
        if(content2_Text.indexOf("<p") == -1 && content2_Text.indexOf("<p/") == -1)
        {
            content2Editor.root.innerHTML = "<p>"+content2_Text+"</p>";
        }
        else
        {
            content2Editor.root.innerHTML = content2_Text;
        }

        var content3Editor = new Quill('#content3-editor', {
            bounds: '#content3-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        var content3_Text = decodeURIComponent('<?php echo old('content3', isset($activity)? encodeURI($activity->content3) : '') ?>');
        if(content3_Text.indexOf("<p") == -1 && content3_Text.indexOf("<p/") == -1)
        {
            content3Editor.root.innerHTML = "<p>"+content3_Text+"</p>";
        }
        else
        {
            content3Editor.root.innerHTML = content3_Text;
        }

        var content4Editor = new Quill('#content4-editor', {
            bounds: '#content4-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        var content4_Text = decodeURIComponent('<?php echo old('content4', isset($activity)? encodeURI($activity->content4) : '') ?>');
        if(content4_Text.indexOf("<p") == -1 && content4_Text.indexOf("<p/") == -1)
        {
            content4Editor.root.innerHTML = "<p>"+content4_Text+"</p>";
        }
        else
        {
            content4Editor.root.innerHTML = content4_Text;
        }

        var content5Editor = new Quill('#content5-editor', {
            bounds: '#content5-editor',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        var content5_Text = decodeURIComponent('<?php echo old('content5', isset($activity)? encodeURI($activity->content5) : '') ?>') ;
        if(content5_Text.indexOf("<p") == -1 && content5_Text.indexOf("<p/") == -1)
        {
            content5Editor.root.innerHTML = "<p>"+content5_Text+"</p>";
        }
        else
        {
            content5Editor.root.innerHTML = content5_Text;
        }
</script>
    <script>
        $(document).ready(function () {

            $(".qc-datepicker").datepicker(options);
            $("#date_intended").on('change', function () {
                $("#date_execution").datepicker("option", "minDate", $(this).val())
            });
            $("#date_execution").on('change', function () {
                $("#date_intended").datepicker("option", "maxDate", $(this).val())
            });

            $('.qc-form-input-80').on('blur', function () {
                let fixNumber = $(this).val();
                if (fixNumber) {
                    $(this).val(parseFloat(fixNumber).toFixed(2));
                }
            });

            $("#time_intended, #time_start, #time_finish").autocomplete({
                source: timeIntervals,
                minLength: 0,
                search: "",
                classes: {
                    "ui-autocomplete": "activity-time-item"
                },
                change: function (event, ui) {
                    $(this).trigger('change');
                }
            }).bind('focus', function () {
                $(this).autocomplete("search", "");
            });

            let arrayOfOptions = [
                @if(isset($location_pool))
                    @foreach($location_pool as $location_pool_item)
                    '{{$location_pool_item->location}}',
                @endforeach
                @endif
            ];
            $("#location").autocomplete({
                source: arrayOfOptions,
                minLength: 0,
                search: "",
                classes: {
                    "ui-autocomplete": "l-suggestion"
                },
                change: function (event, ui) {
                    $(this).trigger('change');
                }
            }).bind('focus', function () {
                $(this).autocomplete("search", "");
            });
        });
        $("#btn-register").click(function () {
            let totalTime = getTotalTime();
            var total_effort = 0.0;
                @foreach($theme_list as $item)
            var effort = document.getElementById('time_{{ $item->id }}').value;
            if (effort.trim() != "") {
                total_effort += parseFloat(effort.trim());
            }
            @endforeach
            if (total_effort > totalTime) {
                alert('工数の合計は実施時刻以下で設定しください');
            } else if (!timeValid) {
                alert('1つ以上の時間フィールドが無効です。');
            } else {
                var style = "\"margin-top: 0; margin-bottom: 0;\"";
//                if(actitivityTitleEditor.getText().trim() == "")
//                {
//                    $('#activity_title').val(null);
//                }
//                else
//                {
//                    $('#activity_title').val(actitivityTitleEditor.root.innerHTML.replace(/'/g,"\"").replace("<p>","<p style=" + style +">"));
//                }
                $('#content2').val(content2Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                $('#content3').val(content3Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                $('#content4').val(content4Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                $('#content5').val(content5Editor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">"));
                $("#form-register").submit()
            }
        });

        function getTotalTime() {
            let value1 = document.getElementById('time_start').value;
            let value2 = document.getElementById('time_finish').value;
            if (value1 && value2) {
                let val1 = "2000/01/01 " + value1;
                let val2 = "2000/01/01 " + value2;
                if (moment(val1).isValid() && moment(val2).isValid()) {
                    let date1 = new Date(val1);
                    let date2 = new Date(val2);
                    if (date1 - date2 >= 0) {
                        val2 = "2000/01/02 " + value2;
                        date2 = new Date(val2);
                    }
                    let msec = date2.getTime() - date1.getTime();
                    let hh = Math.floor(msec / 1000 / 60 / 60);
                    msec -= hh * 1000 * 60 * 60;
                    let mm = Math.floor(msec / 1000 / 60);
                    return (hh + mm / 60).toFixed(2);
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }

        function checkTime() {
            let timeTotal = getTotalTime();
            if (timeTotal === 0) {
                document.getElementById('time_total').innerHTML = "--";
                document.getElementById('time_span').value = "";
            } else {
                document.getElementById('time_total').innerHTML = getTotalTime();
                document.getElementById('time_span').value = getTotalTime();
            }
        }

        checkTime();

        let timeValid = true;

        function isTime(time) {
            return /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time);
        }

        $('#time_start').bind("change", function () {
            let time = $(this).val();
            let timeT = new Date("2000/01/01 " + time);
            let timeH = timeT.getHours();
            if (time && timeH < 10) {
                if (timeT.getMinutes() < 10) {
                    $(this).val('0' + timeT.getHours() + ':' + timeT.getMinutes() + "0");
                } else {
                    $(this).val('0' + timeT.getHours() + ':' + timeT.getMinutes());
                }
            }
            if (time && !isTime(time)) {
                $('.msg').show();
                timeValid = false;
            } else {
                $('.msg').hide();
                timeValid = true;
            }
        });

        $('#time_finish').bind("change", function () {
            let time = $(this).val();
            let timeT = new Date("2000/01/01 " + time);
            let timeH = timeT.getHours();
            if (time && timeH < 10) {
                if (timeT.getMinutes() < 10) {
                    $(this).val('0' + timeT.getHours() + ':' + timeT.getMinutes() + "0");
                } else {
                    $(this).val('0' + timeT.getHours() + ':' + timeT.getMinutes());
                }
            }
            if (time && !isTime(time)) {
                $('.msg2').show();
                timeValid = false;
            } else {
                $('.msg2').hide();
                timeValid = true;
            }
        });

        $('#time_intended').bind("change", function () {
            let time = $(this).val();
            let timeT = new Date("2000/01/01 " + time);
            let timeH = timeT.getHours();
            if (time && timeH < 10) {
                if (timeT.getMinutes() < 10) {
                    $(this).val('0' + timeT.getHours() + ':' + timeT.getMinutes() + "0");
                } else {
                    $(this).val('0' + timeT.getHours() + ':' + timeT.getMinutes());
                }
            }
            if (time && !isTime(time)) {
                $('.msg-intended').show();
                timeValid = false;
            } else {
                $('.msg-intended').hide();
                timeValid = true;
            }
        });

        $('#date-icon-1').click(function () {
            $('#date_intended').focus();
        });
        $('#date-icon-2').click(function () {
            $('#date_execution').focus();
        });
        $('input[type=file]').bind('change', function () {
            let inputName = this.name;
            let ext = getExt(this.files[0].name).toLowerCase();
            let types = ['xlsx', 'xls', 'csv', 'xlsm', 'xlst', 'ods', 'doc', 'docx', 'txt', 'pdf', 'png', 'jpg', 'gif', 'jpeg', 'tiff', 'psd', 'xml', 'pptx', 'ppt'];
//            if (types.indexOf(ext) === -1) {
//                alert("{{\App\Enums\StaticConfig::$File_Not_Allowed}}");
//                this.value = "";
//            } else
            if (this.files[0].size > 5242880) {
                alert("{{\App\Enums\StaticConfig::$File_Size_5M}}");
                this.value = "";
            }
            else
            {
                if(inputName === "attachmentContent")
                {
                    let formData = new FormData($('#form-register')[0]);
                    $.ajax({
                        url: "{{route('uploadfile.updateContentActivity')}}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        cache: false,
                        contentType: false,
                        success: function (data) { //previewUploadFile
                            $('#multipleFileContent').val('');
                            //add element
                            var routeUrl = '{{route("uploadfile.downloadFile",":fileid")}}';
                            routeUrl = routeUrl.replace(':fileid', data.id);
                            $( "#previewUploadFile" ).append( "<span><a href="+routeUrl+">"+ data.FileNameOriginal +"</a> \n\
                            <i class=\"fa fa-trash\" style=\"cursor:pointer\" onclick=\"deleteFileOutActivity(" + data.id +")\" id="+ data.id +"></i><br/></span>");
                            //add to list id file
                            let fileIds = $('#contentFileIds').val();
                            let arrExistIds = [];
                            if(fileIds != "" && fileIds != null)
                            {
                                arrExistIds = fileIds.split(',');
                            }
                            arrExistIds.push(data.id);
                            $('#contentFileIds').val(arrExistIds.join());
                        },
                        error: function (error) {
                            alert(error);
                        }
                    });
                }
            }
        });

        function deleteFileOutActivity(fileId)
        {
            $('#'+fileId).parent().remove();
            let existIds = $('#contentFileIds').val();
            let arrExistIds = existIds.split(',');
            let arrFilterIds = arrExistIds.filter(function(value, index, arr){
                return parseInt(value) !== fileId;
            });
            $('#contentFileIds').val(arrFilterIds.join());
        }

        // var extension;
        function getExt(fileName) {
            let temp = fileName.slice(0, fileName.lastIndexOf('.') + 1);
            let extension = fileName.replace(temp, '');
            return extension.toLowerCase();
        }
    </script>
@endsection
