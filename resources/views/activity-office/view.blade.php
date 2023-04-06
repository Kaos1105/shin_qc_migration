@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
            }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('活動記録管理',URL::to('activity-office/list'));
        }),
        Breadcrumbs::for('show', function ($trail) {
            $trail->parent('list');
            $trail->push('表示');
        })
    }}

    {{ Breadcrumbs::render('show') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-primary" id="btnEditSecretariat" style="color: #212529; background: #D2D2F4;border-radius: 5px;border: 1px solid #0c0c0c;box-shadow: 1px 1px 10px #ccc;margin-left: 5px;">事務局記入欄編集</button>
        <button class="btn btn-back" id="btnSaveSecretariat" style="display: none">保存</button>
        @if(isset($holdsort) || isset($callback))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ URL('activity') }}">戻る</a>
        @endif

    </div>
    <div class="container-fluid bottom-fix">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ \App\Enums\Common::show_id($activity->id) }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">{{ __('サークル名') }}</td>
                <td>
                    <span>{{ isset($circle->circle_name) ? $circle->circle_name : '' }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">活動区分</td>
                <td>
                        <span>
                            @if($activity->activity_category == App\Enums\Activity::MEETING)
                                会合
                            @elseif($activity->activity_category == App\Enums\Activity::STUDY_GROUP)
                                勉強会
                            @elseif($activity->activity_category == App\Enums\Activity::KAIZEN)
                                改善提案
                            @else
                                その他
                            @endif
                        </span>
                </td>
            </tr>
            <tr>
                <td class="td-first">タイトル</td>
                <td>
                    <span>{{ $activity->activity_title }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">予定日</td>
                <td>
                    <span>{{ date('Y/m/d', strtotime($activity->date_intended)) }} </span>
                </td>
            </tr>
            <tr>
                <td class="td-first">予定時刻</td>
                <td>
                    <span>{{ date("H:i", strtotime($activity->time_intended)) }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">実施日</td>
                <td>
                    <span>{{ isset($activity->date_execution)? date('Y/m/d', strtotime($activity->date_execution)) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">実施時刻</td>
                <td>
                    @if(isset($activity->time_start) && isset($activity->time_finish))
                        <?php $time = \App\Enums\Common::total_time($activity->time_start, $activity->time_finish); ?>
                        <span>{{ date("H:i", strtotime($activity->time_start)) }} ～ {{ date("H:i", strtotime($activity->time_finish)) }} ( {{ \App\Enums\Common::two_number_after_comma($time) }} h )</span>
                    @elseif(isset($activity->time_start))
                        <span>{{ date("H:i", strtotime($activity->time_start)) }} ～ </span>
                    @elseif(isset($activity->time_finish))
                        <span>～ {{ date("H:i", strtotime($activity->time_finish)) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">参加者数</td>
                <td>
                    <span>{{ $activity->participant_number }} 人</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">場所</td>
                <td>
                    <span>{{ $activity->location }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">参加者名</td>
                <td>
                    <span class="line-brake-preserve">{{$activity->content1}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">内容</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$activity->content2!!}</span></span>
                    @foreach ($ContentFiles as $contentfile)                         
                        <span><a href="{{route("uploadfile.downloadFileActivityManagement",$contentfile->id)}}">{{$contentfile->FileNameOriginal}}</a><br/></span> 
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="td-first">上司への要望</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$activity->content3!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">上司記入欄</td>
                <td>
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$activity->content4!!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">事務局記入欄</td>
                <td>
                    <span class="ql-editor" id="txtSecretariatEntry">{!!$activity->content5!!}</span>
                    <div style="display: none;margin-bottom: 10px;min-height: 50px;background-color: #ffffff;margin-right: 5px;" id="inputSecretariatEntry"></div>
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
                <td style="width: 15%">工数</td>
            </tr>
            @foreach($activity_other as $item)
                <tr>
                    <td>{{ $item->theme_name }}</td>
                    <td>{{ $item->content }} </td>
                    <td class="text-center">{{ \App\Enums\Common::two_number_after_comma($item->time) }}h</td>
                </tr>
            @endforeach
        </table>
    </div>
    {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['activity.destroy', $activity->id]]) !!}
    {!! Form::close() !!}
<script type="text/javascript">
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
        var SecretariatEntryTitleEditor = new Quill('#inputSecretariatEntry', {
            bounds: '#inputSecretariatEntry',
            modules: {
                'toolbar': toolbar,
            },
            placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
            theme: 'snow'
        });
        $('#inputSecretariatEntry').prev().hide();
        var SecretariatEntryTitle_Text = decodeURIComponent('<?php echo encodeURI($activity->content5) ?>');        
        if(SecretariatEntryTitle_Text.indexOf("<p") == -1 && SecretariatEntryTitle_Text.indexOf("<p/") == -1)
        {
            SecretariatEntryTitleEditor.root.innerHTML = "<p>"+SecretariatEntryTitle_Text+"</p>";
        }
        else
        {
            SecretariatEntryTitleEditor.root.innerHTML = SecretariatEntryTitle_Text;
        }
</script>
    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Activity }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
        //enable edit Secretariat
        $('#btnEditSecretariat').click(function(){
            $(this).hide();
            $('#btnSaveSecretariat').show();
            $('#txtSecretariatEntry').hide();
            $('#inputSecretariatEntry').show();
            $('#inputSecretariatEntry').prev().show();
        });
        //save data Secretariat
        $('#btnSaveSecretariat').click(function(){
            var style = "\"margin-top: 0; margin-bottom: 0;\"";
           $.ajax({
                url: "{{route('activityoffice.updateSecretariatEntry')}}",
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    activity_id: {{$activity->id}},
                    secretariat_entry: SecretariatEntryTitleEditor.root.innerHTML.replace(/'/g,"\"").replace(/<p>/g,"<p style=" + style +">").trim(),                    
                },
                success:function(result){                
                    location.reload();
                },
                error:function(xhr, status, error){                      
                    alert(xhr.responseText);
                }
            });
        });
    </script>
@endsection
