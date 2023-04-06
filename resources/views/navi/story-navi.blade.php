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
       Breadcrumbs::for('story-list', function ($trail, $classification) {
           $trail->parent('story-classification');
           $trail->push($classification, URL::to('qc-navi/story-list/'.$classification));
       }),
       Breadcrumbs::for('story-select', function ($trail, $classification, $story_name) {
           $trail->parent('story-list', $classification);
           $trail->push($story_name);
       }),
       Breadcrumbs::for('story-navi', function ($trail, $classification, $story_name, $qa_info) {
           $trail->parent('story-select', $classification, $story_name);
           $trail->push($qa_info);
       })
    }}

    {{ Breadcrumbs::render('story-navi', $qa->classification, $qa->story_name, $qa->screen_id.' '.$qa->title ) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(isset($prevId))
            <a class="btn btn-back"
               href="{{URL::to('/qc-navi/story-navi?qaId='.$prevId.'&historyId='.$history_id)}}">前のページへ</a>
        @else
            <?php
            $detail = DB::table('navi_details')->where('qa_id', $qa->id)->where('history_id', $history_id)->orderBy('id', 'DESC')->select('id')->first();
            ?>
            @if(isset($detail))
                <?php
                $prev_id = DB::table('navi_details')
                    ->where('history_id', $history_id)
                    ->where('id', '<', $detail->id)
                    ->orderBy('id', 'DESC')
                    ->first();
                ?>
                @if(isset($prev_id->qa_id))
                    <a class="btn btn-back"
                       href="{{URL::to('/qc-navi/story-navi?qaId='.$prev_id->qa_id.'&historyId='.$history_id)}}">前のページへ</a>
                @else
                    <button type="button" class="btn btn-back" disabled>前のページへ</button>
                @endif
            @else
                <button type="button" class="btn btn-back" disabled>前のページへ</button>
            @endif
        @endif
        <a id="end-navi-button" class="btn btn-back" href="{{URL::to('qc-navi/navi-finish/'.$history_id.'/'.$qa->classification)}}">ナビを終わる</a>
    </div>
    <div class="qa-muti-form" style="margin-bottom: 3rem; margin-left: 15px; width: calc(100% - 25px);">
        <div class="qa-q-form" id="qa-q-form">
            <?php
            $question = DB::table('qa_questions')->where('qa_id', $qa->id)->whereNotNull('content')->orderBy('display_order', 'asc')->get();
            ?>
            <div id="qa-card-collection-q">
                @foreach($question as $que)
                    @if($que->content)
                        <div class="qa-card" id="q{{$que->id}}">
                            <?php
                            $type = $que->screen_classification;
                            $align = $que->alignment
                            ?>
                            @switch($type)
                                @case(1)
                                @if(isset($que->content))
                                    <div class="qa-q-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                        <span>{!! $que->content !!}</span>
                                    </div>
                                @endif
                                @break
                                @case(2)
                                @if(isset($que->file_name))
                                    <div class="qa-q-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                        <div>
                                            <?php
                                                $img_name = substr($que->file_name, stripos($que->file_name, '.') + 1, strlen($que->file_name) - stripos($que->file_name, '.'));
                                            ?>
                                            <img src="{{asset(\App\Enums\StaticConfig::$View_Path_QaQuestion.$que->file_name)}}"
                                                 alt="{{$img_name}}"
                                                 height="{{isset($que->height) ? $que->height : ''}}"
                                                 width="{{isset($que->length) ? $que->length : ''}}"
                                            />
                                            <span class="d-block">{{$que->comment}}</span>
                                        </div>
                                    </div>
                                @endif
                                @break
                                @case(3)
                                @if(isset($que->file_name))
                                    <div class="qa-q-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                        <?php
                                        $path = asset('/images/icons8-file-80.png');
                                        ?>
                                        <div>
                                            <div class="qa-icon-holder">
                                                <?php
                                                $extension = strtolower(pathinfo($que->file_name, PATHINFO_EXTENSION));
                                                $fileOrigin = $que->file_name;
                                                $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin) - stripos($fileOrigin, '.'));
                                                ?>
                                                <img src="{{asset('/images/icons8-file-80.png')}}" alt="" width="48px"
                                                     height="48px"
                                                     style="margin-top: -10px"/>
                                                <div class="qa-file-ext"
                                                     style="background-color: {{\App\Enums\Common::generateColor($extension)}}">{{$extension}}</div>
                                            </div>
                                            <div class="content-holder">
                                                <span class="d-block qa-real-content">{{$que->content}}</span>
                                                <span class="d-inline qa-file-dl-name">{{$fileNameDisp}}</span>
                                                <a class="qa-download-button-navi"
                                                   href="{{URL::to('qc-navi/story-navi-download?id='.$que->id)}}"><h4
                                                            class="d-inline">&#11123;</h4> ダウンロード
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @break
                                @case(4)
                                <div class="qa-q-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                    @if(isset($que->content))
                                        <div>
                                        <textarea class="line-brake-preserve fake-textbox qa-real-content"
                                                  rows="{{$que->height}}" cols="{{$que->length}}"
                                        >{{$que->content}}</textarea>
                                        </div>
                                    @endif
                                </div>
                                @break
                                @case(5)
                                @if(isset($que->content))
                                    <div class="qa-q-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                        <div class="file-input-parent" style="width: 50%">
                                            <div class="qa-real-content">{{$que->content}}</div>
                                            <input type="file" class="qc-file-input full-width"
                                                   id="file-input-{{$que->id}}"/>
                                        </div>
                                        <script>
                                            $('#file-input-{{$que->id}}').on('change', function () {
                                                    let ext = getExt(this.files[0].name).toLowerCase();
                                                    let types = ['php', 'exe', 'bat', 'bmp', 'js', 'jar', 'vbs', 'vb', 'dll', 'py', 'tmp'];
                                                    <?php
                                                    $file_size = $que->file_size ? $que->file_size : null;
                                                    ?>
                                                    let allowedSize = {{$file_size * 1048576}};
                                                    if (types.indexOf(ext) !== -1) {
                                                        alert("{{\App\Enums\StaticConfig::$File_Not_Allowed}}");
                                                        this.value = "";
                                                    } else if (this.files[0].size > allowedSize) {
                                                        alert("このフィールドは" + Math.floor(allowedSize / 1048576) + "MB以下で設定して下さい。");
                                                        this.value = "";
                                                    }
                                                }
                                            );
                                        </script>
                                    </div>
                                @endif
                                @break
                            @endswitch
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="qa-a-form">
            <?php
            $answer = DB::table('qa_answers')->where('qa_id', $qa->id)->orderBy('display_order', 'asc')->get();
            ?>
            <div id="qa-card-collection-a">
                @foreach($answer as $ans)
                    @if($ans->content)
                        <?php
                        $linked_qa = DB::table('qas')->where('id', $ans->qa_linked)->first();
                        ?>
                        @if(isset($linked_qa))
                            <div class="d-block qa-answer-link">
                                <div class="qa-card" id="a{{$ans->id}}">
                                    <?php
                                    $type = $ans->screen_classification;
                                    $align = $ans->alignment
                                    ?>
                                    @switch($type)
                                        @case(1)
                                        <div class="qa-a-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                            <span>{{$ans->content}}</span>
                                        </div>
                                        @break
                                        @case(2)
                                        @if(isset($ans->file_name))
                                            <div class="qa-a-content @if($align == 1) qa-align-left @elseif($align == 2) qa-align-center @elseif($align == 3) qa-align-right @endif">
                                                <div>
                                                    <img src="{{asset(\App\Enums\StaticConfig::$View_Path_QaAnswer.$ans->file_name)}}"
                                                         alt=""
                                                         height="{{isset($ans->height) ? $ans->height : ''}}"
                                                         width="{{isset($ans->length) ? $ans->length : ''}}"
                                                    />
                                                    <span class="d-block">{{$ans->comment}}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @break
                                    @endswitch
                                </div>
                                <input type="hidden" class="answer_id" value="{{$ans->id}}"/>
                                <input type="hidden" class="qa-id-info" value="{{$ans->qa_id}}"/>
                                <input type="hidden" class="qa-linked-id-info" value="{{$ans->qa_linked}}"/>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <script>
        var url = "{{ url('/') }}";
        $('.qa-answer-link').bind('click', function () {
            var detailData = new FormData();
            detailData.append("_token", "{{csrf_token()}}");
            detailData.append('history_id', {{$history_id}});
            detailData.append('qa_id', {{$qa->id}});
            detailData.append('answer_id', $(this).find('.answer_id').val());

            detailData.append('prev_id', $(this).find('.qa-id-info').val());
            detailData.append('linked_id', $(this).find('.qa-linked-id-info').val());

            var textbox = document.getElementsByClassName('fake-textbox');
            var fileInput = document.getElementsByClassName('qc-file-input');
            let textboxCount = textbox.length;
            let fileCount = fileInput.length;
            if (textboxCount + fileCount !== 0) {
                for (let i = 0; i < textboxCount; i++) {
                    if (textbox[i].value) {
                        detailData.append("text[]", textbox[i].value);
                    }
                }
                for (let i = 0; i < fileCount; i++) {
                    if (fileInput[i].value) {
                        detailData.append('file[]', fileInput[i].files[0]);
                    }

                }
            }
            $.ajax({
                url: url + "/qc-navi/navi-add-details",
                type: 'POST',
                data: detailData,
                processData: false,
                cache: false,
                contentType: false,
                success: function (data) {
                    location.href = url + '/qc-navi/story-navi?prevId=' + data.prev_id + '&qaId=' + data.qa_id + '&historyId=' + data.history_id;
                },
                error: function () {
                    alert("Bad submit");
                }
            });
        });

        function getExt(fileName) {
            let temp = fileName.slice(0, fileName.lastIndexOf('.') + 1);
            let extension = fileName.replace(temp, '');
            return extension.toLowerCase();
        }
    </script>
@endsection
