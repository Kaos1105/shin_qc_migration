<div class="qa-muti-form" style="margin-bottom: 3rem">
    <div class="qa-q-form" id="qa-q-form">
        <div id="qa-card-collection-q">
            @for($i=0; $i<sizeof($qa_question); $i++)
                <div class="qa-card" id="{{$qa_question[$i]->id}}">
                    <?php
                    $align_q = $qa_question[$i]->alignment;
                    ?>
                    <div class="qa-input-field @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif">
                        @switch($qa_question[$i]->screen_classification)
                            @case(1)
                            <div class="dialog-content text-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif ">
                                @if(isset($qa_question[$i]->content))
                                    <div>
                                        <span class="qa-real-content line-brake-preserve">{!!$qa_question[$i]->content!!}</span>
                                    </div>
                                @endif
                            </div>
                            @break
                            @case(2)
                            <div class="dialog-content image-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif ">
                                @if(isset($qa_question[$i]->content))
                                    <div>
                                        <img src="/storage/uploaded-files/qa-question-upload/{{ $qa_question[$i]->file_name }}"
                                             alt="{{ $qa_question[$i]->file_name }}"
                                             width="{{isset($qa_question[$i]->length) ? $qa_question[$i]->length : ''}}"
                                             height="{{isset($qa_question[$i]->height) ? $qa_question[$i]->height : ''}}">
                                        <span class="d-block qa-real-content">{{$qa_question[$i]->comment}}</span>
                                    </div>
                                @endif
                            </div>
                            @break
                            @case(3)
                            <div class="dialog-content file-dl-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif ">
                                <?php
                                $path = asset('/images/icons8-file-80.png');
                                ?>
                                @if(isset($qa_question[$i]->file_name))
                                    <div>
                                        <div class="qa-icon-holder">
                                            <?php
                                            $extension = strtolower(pathinfo($qa_question[$i]->file_name, PATHINFO_EXTENSION));
                                            $fileOrigin = $qa_question[$i]->file_name;
                                            $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin) - stripos($fileOrigin, '.'));
                                            ?>
                                            <img src="{{asset('/images/icons8-file-80.png')}}" width="48px" alt="file-icon"
                                                 height="48px"
                                                 style="margin-top: -10px"/>
                                            <div class="qa-file-ext"
                                                 style="background-color: {{\App\Enums\Common::generateColor($extension)}}">{{$extension}}</div>
                                        </div>
                                        <div class="content-holder">
                                            <span class="d-block qa-real-content">{{$qa_question[$i]->content}}</span>
                                            <span class="d-inline qa-file-dl-name">{{$fileNameDisp}}</span>
                                            <button class="qa-download-button" type="button" disabled>&#11123; ダウンロード
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <div class="qa-icon-holder">
                                            <img src="{{$path}}" width="48px" alt="image"
                                                 height="48px" style="margin-top: -10px"/>
                                            <div class="d-inline-block qa-file-ext"></div>
                                        </div>
                                        <div class="content-holder"><span
                                                    class="d-block qa-real-content">{{$qa_question[$i]->content}}</span><span
                                                    class="d-inline qa-file-dl-name"></span>
                                            <button class="qa-download-button" type="button" disabled>&#11123; ダウンロード
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @break
                            @case(4)
                            <div class="dialog-content textbox-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif ">
                                @if(isset($qa_question[$i]->content))
                                    <div>
                                        <textarea class="line-brake-preserve fake-textbox qa-real-content"
                                                  rows="{{$qa_question[$i]->height}}"
                                                  cols="{{$qa_question[$i]->length}}"
                                                  readonly
                                        >{{$qa_question[$i]->content}}</textarea>
                                        <span class="d-none card-textbox-height">{{$qa_question[$i]->height}}</span>
                                        <span class="d-none card-textbox-length">{{$qa_question[$i]->length}}</span>
                                    </div>
                                @endif
                            </div>
                            @break
                            @case(5)
                            <div class="dialog-content file-ul-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif">
                                @if(isset($qa_question[$i]->content))
                                    <div>
                                        <div class="qa-real-content">{{$qa_question[$i]->content}}</div>
                                        <span class="d-none qa-file-size">{{$qa_question[$i]->file_size}}</span>
                                        <input type="file" class="fake-file" disabled/>
                                    </div>
                                @endif
                            </div>
                            @break
                        @endswitch
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="qa-a-form">
        <div id="qa-card-collection-a">
            @for($i=0; $i<sizeof($qa_answer); $i++)
                <div class="qa-card" id="{{$qa_answer[$i]->id}}-a">
                    <?php
                    $align_a = $qa_answer[$i]->alignment;
                    ?>
                    <div class="qa-input-field">
                        @switch($qa_answer[$i]->screen_classification)
                            @case(1)
                            <div class="dialog-content text-content @if($align_a == 1) qa-align-left @elseif($align_a == 2) qa-align-center @elseif($align_a == 3) qa-align-right @endif ">
                                @if(isset($qa_answer[$i]->content))
                                    <div>
                                        <span class="qa-real-content line-brake-preserve">{{$qa_answer[$i]->content}}</span>
                                        <span class="d-block qa-linked-content">{{$qa_answer[$i]->qaLinked}}</span>
                                    </div>
                                @endif
                            </div>
                            @break
                            @case(2)
                            <div class="dialog-content image-content @if($align_a == 1) qa-align-left @elseif($align_a == 2) qa-align-center @elseif($align_a == 3) qa-align-right @endif ">
                                @if(isset($qa_answer[$i]->content))
                                    <div>
                                        @if(isset($qa_answer[$i]->file_name))
                                            <img src="/storage/uploaded-files/qa-answer-upload/{{ $qa_answer[$i]->file_name }}"
                                                 alt="{{ $qa_answer[$i]->file_name }}"
                                                 width="{{isset($qa_answer[$i]->length) ? $qa_answer[$i]->length : ''}}"
                                                 height="{{isset($qa_answer[$i]->height) ? $qa_answer[$i]->height : ''}}">
                                        @endif
                                        <span class="d-block qa-real-content">{{$qa_answer[$i]->comment}}</span>
                                        <span class="d-block qa-linked-content">{{$qa_answer[$i]->qaLinked}}</span>
                                        @endif
                                    </div>
                            </div>
                            @break
                        @endswitch
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>



