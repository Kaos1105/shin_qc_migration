@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
            }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('活動記録',URL::to('activity'));
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
        <?php 
            $filterYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
        ?>
        @if(!isset($callback))
            @if( session('circle.id'))
                <a class="btn btn-back" href="{{ URL::to('activity/create?activity_id='.$activity->id .'&year='.$filterYear) }}">複写</a>
                @if(isset($callback))
                    <a class="btn btn-back"
                       href="{{ URL::to('/activity/'.$activity->id.'/edit?callback='.$callback.'&year='.$filterYear) }}">修正</a>
                @else
                    <a class="btn btn-back" href="{{ URL::to('/activity/'.$activity->id.'/edit'.'?year='.$filterYear) }}">修正</a>
                @endif
                <a class="btn btn-back" id="btn-delete">削除</a>
            @endif
        @endif
        @if(isset($holdsort) || isset($callback))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ URL('activity'.'?filter='.$filterYear) }}">戻る</a>
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
                    <span>{{ $circle->circle_name }}</span>
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
                        <span>{{ date("H:i", strtotime($activity->time_start)) }} ～ {{ date("H:i", strtotime($activity->time_finish)) }}</span>
                        <span id="time_total">({{number_format($activity->time_span, 2)}} h)</span>
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
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$activity->content2!!} </span></span>
                    @foreach ($ContentFiles as $contentfile)                         
                        <span><a href="{{route("uploadfile.downloadFile",$contentfile->id)}}">{{$contentfile->FileNameOriginal}}</a><br/></span> 
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
                    <span class="line-brake-preserve"><span class="ql-editor" style="all:unset;"> {!!$activity->content5!!}</span></span>
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
                    <td style="text-align: center">{{ \App\Enums\Common::two_number_after_comma($item->time) }}h</td>
                </tr>
            @endforeach
        </table>
    </div>
    {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['activity.destroy', $activity->id]]) !!}
        <input type="hidden" name="filterYearDelete" value="{{$filterYear}}">
    {!! Form::close() !!}

    <script>
        $("#btn-delete").click(function () {
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Activity }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
