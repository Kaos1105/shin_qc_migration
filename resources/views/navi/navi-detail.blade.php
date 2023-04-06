@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
            }),
         Breadcrumbs::for('story-classification', function ($trail) {
           $trail->parent('toppage');
           $trail->push('ＱＣナビ', URL::to('qc-navi/story-classification'));
       }),
       Breadcrumbs::for('navi-history', function ($trail) {
           $trail->parent('story-classification');
           $trail->push('ナビ履歴', URL::to('qc-navi/navi-history'));
       }),
        Breadcrumbs::for('navi-detail', function ($trail) {
           $trail->parent('navi-history');
           $trail->push('明細');
       })
    }}

    {{ Breadcrumbs::render('navi-detail') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        @if(isset($resume_qa))
            @if($resume_qa->done_status != 1)
                <a class="btn btn-back" id="resume-qa"
                   href="{{URL::to('qc-navi/story-navi?qaId='.$resume_qa->qa_id.'&historyId='.$resume_qa->history_id)}}">続きから再開</a>
            @else
                <button class="btn btn-back" disabled>続きから再開</button>
            @endif
            <button class="btn btn-back" id="delete-detail">削除</button>
        @else
            <a class="btn btn-back" id="resume-qa"
               href="{{URL::to('qc-navi/story-navi?qaId='.$history->starting_qa.'&historyId='.$history->id)}}">続きから再開</a>
            <button class="btn btn-back" id="delete-detail">削除</button>
        @endif
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>

    <div class="container-fluid">
        <table class="table-form" border="1" style="margin-top: 3rem; margin-bottom: 1rem">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($history)? \App\Enums\Common::show_id($history->id) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">履歴名</td>
                <td>
                    <span>{{$history->historii}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">開始日時</td>
                <td>
                    <span>{{date_format(date_create($history->date_start),"Y/m/d H:i:s")}} {{$history->user_name}}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">利用時間</td>
                <td>
                    <span>{{$history->duration}}</span>
                </td>
            </tr>
        </table>

        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col" style="width: 3%; cursor: default">No</th>
                <th scope="col" style="width: 20%">ストーリー</th>
                <th scope="col" style="width: 30%">ＱＡ画面</th>
                <th scope="col" style="width: 25%">選択回答</th>
                <th scope="col" style="width: 12%">回答日時</th>
                <th scope="col" style="width: 10%">回答時間(s)</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $stt = ($paginate->currentPage() - 1) * 20;
            $detail = $paginate;
            ?>
            @for ($i = 0; $i < count($detail); $i++ )
                <?php
                $stt++;
                ?>
                <tr>
                    <th class="font-weight-normal">{{$stt}}</th>
                    <td>
                        @if(isset($detail[$i]->story_name))
                            <a class="a-black"
                               href="{{URL::to('story/show-story/'.$detail[$i]->story_id)}}">{{$detail[$i]->story_name}}</a>
                        @else
                            <span>削除されています</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($detail[$i]->qaTitle))
                            <a class="a-black"
                               href="{{URL::to('qa/show-qa/'.$detail[$i]->qa_id)}}">{{$detail[$i]->qaTitle}}</a>
                        @else
                            <span>削除されています</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($detail[$i]->answerChosen))
                            <span>{{$detail[$i]->answerChosen}}</span>
                        @else
                            <span>削除されています</span>
                        @endif
                    </td>
                    <td>
                        <span>{{date_format(date_create($detail[$i]->date_answer),"Y/m/d H:i")}}</span>
                    </td>
                    <td>
                        <span>{{number_format(round($detail[$i]->secDiff),0)}}</span>
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
            <?php
            $history_id = isset($resume_qa->history_id) ? $resume_qa->history_id : null;
            ?>
        var historyID = '{{$history_id}}';
        if (historyID) {
            var columns = ["no", "story_name", "qaTitle", "answerChosen", "date_answer", "secDiff"];
            var index_old = 5;
            var view = historyID;
            $(document).ready(sortAndFilter(columns, index_old, view));
        }
    </script>
    <script>
        $('#delete-detail').on('click', function () {
            var r = confirm('{{\App\Enums\StaticConfig::$Delete_Detail}}');
            if (r == true) {
                location.href = "{{URL::to('qc-navi/navi-delete-detail/'.$hist_id)}}";
            }
        });
    </script>
@endsection

