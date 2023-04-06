<div class="qa-muti-form" style="margin-bottom: 3rem">
    <div class="qa-q-form" id="qa-q-form">
        <div id="qa-card-collection-q">
            @for($i=0; $i<sizeof($qa_question); $i++)
                <div class="qa-card" id="{{$qa_question[$i]->id}}">
                    <div class="qa-format-button-group">
                        <button class="qa-f-button qa-align qa-to-left"
                                @if($qa_question[$i]->alignment == 1)disabled @endif>
                            &lt;
                        </button>
                        <button class="qa-f-button qa-align qa-center"
                                @if($qa_question[$i]->alignment == 2)disabled @endif>=
                        </button>
                        <button class="qa-f-button qa-align qa-to-right"
                                @if($qa_question[$i]->alignment == 3)disabled @endif>
                            &gt;
                        </button>
                        <button class="qa-f-button up-down qa-up">△</button>
                        <button class="qa-f-button up-down qa-down">▽</button>
                        <button class="qa-f-button qa-remove">⨯</button>
                    </div>
                    <?php
                    $align_q = $qa_question[$i]->alignment;
                    ?>
                    <div class="qa-input-field @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif">
                        @switch($qa_question[$i]->screen_classification)
                            @case(1)
                            <div class="dialog-content text-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif ">
                                @if(isset($qa_question[$i]->content))
                                    <div class="rich-text-container">
                                        <div class="qa-real-content line-brake-preserve ql-editor">{!! $qa_question[$i]->content !!}</div>
                                        <span class="d-none editable-content">{{$qa_question[$i]->delta}}</span>
                                    </div>
                                @endif
                            </div>
                            @break
                            @case(2)
                            <div class="dialog-content image-content @if($align_q == 1) qa-align-left @elseif($align_q == 2) qa-align-center @elseif($align_q == 3) qa-align-right @endif ">
                                @if(isset($qa_question[$i]->content))
                                    <div>
                                        @if(isset($qa_question[$i]->file_name))
                                            <img src="/storage/uploaded-files/qa-question-upload/{{ $qa_question[$i]->file_name }}"
                                                 alt="file: {{ $qa_question[$i]->file_name }}"
                                                 width="{{isset($qa_question[$i]->length) ? $qa_question[$i]->length : ''}}"
                                                 height="{{isset($qa_question[$i]->height) ? $qa_question[$i]->height : ''}}">
                                        @endif
                                        <span class="d-block qa-real-content">{{$qa_question[$i]->comment}}</span>
                                        <span class="d-none qa-real-required">{{$qa_question[$i]->file_name}}</span>
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
                                            <img src="{{asset('/images/icons8-file-80.png')}}" alt="file" width="48px"
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
                                        <span class="d-none qa-real-required">{{$qa_question[$i]->file_name}}</span>
                                    </div>
                                @else
                                    <div>
                                        <div class="qa-icon-holder">
                                            <img src="{{$path}}" alt="image" width="48px"
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
        <div class="d-none" id="q-form-init">card info when created
            <form id="text-card-info" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="1"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_question_disp_order}}"/>
            </form>
            <form id="image-card-info" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="2"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_question_disp_order}}"/>
            </form>
            <form id="download-card-info" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="3"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_question_disp_order}}"/>
            </form>
            <form id="textbox-card-info" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="4"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_question_disp_order}}"/>
            </form>
            <form id="upload-card-info" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="5"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_question_disp_order}}"/>
            </form>
        </div>
        <div class="qa-input-select">
            <div class="qa-i-button" id="qa-add-btn-text">文字列</div>
            <div class="qa-i-button" id="qa-add-btn-image">貼り付け画像</div>
            <div class="qa-i-button" id="qa-add-btn-file-dl">ＤＬ用ファイル</div>
            <div class="qa-i-button" id="qa-add-btn-textbox">テキストボックス</div>
            <div class="qa-i-button" id="qa-add-btn-file-ul">ＵＬ用ファイル</div>
        </div>
    </div>

    <div class="qa-a-form">
        <div id="qa-card-collection-a">
            @for($i=0; $i<sizeof($qa_answer); $i++)
                <div class="qa-card" id="{{$qa_answer[$i]->id}}-a">
                    <div class="qa-format-button-group">
                        <button class="qa-f-button qa-align qa-to-left"
                                @if($qa_answer[$i]->alignment == 1)disabled @endif>
                            &lt;
                        </button>
                        <button class="qa-f-button qa-align qa-center"
                                @if($qa_answer[$i]->alignment == 2)disabled @endif>=
                        </button>
                        <button class="qa-f-button qa-align qa-to-right"
                                @if($qa_answer[$i]->alignment == 3)disabled @endif>
                            &gt;
                        </button>
                        <button class="qa-f-button up-down qa-up">△</button>
                        <button class="qa-f-button up-down qa-down">▽</button>
                        <button class="qa-f-button qa-remove">⨯</button>
                    </div>
                    <?php
                    $align_a = $qa_answer[$i]->alignment;
                    ?>
                    <div class="qa-input-field">
                        @switch($qa_answer[$i]->screen_classification)
                            @case(1)
                            <div class="dialog-content text-content @if($align_a == 1) qa-align-left @elseif($align_a == 2) qa-align-center @elseif($align_a == 3) qa-align-right @endif ">
                                @if(isset($qa_answer[$i]->content))
                                    <div>
                                        <span class="d-block qa-real-content line-brake-preserve">{{$qa_answer[$i]->content}}</span>
                                        <span class="d-inline qa-linked-content">{{$qa_answer[$i]->qaLinked}}</span>
                                        <input type="hidden" class="linked-id" value="{{$qa_answer[$i]->qaLinkedID}}">
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
                                        <span class="d-none qa-real-required">{{$qa_answer[$i]->file_name}}</span>
                                        <input type="hidden" class="linked-id" value="{{$qa_answer[$i]->qaLinkedID}}">
                                    </div>
                                @endif
                            </div>
                            @break
                        @endswitch
                    </div>
                </div>
            @endfor
        </div>
        <div class="d-none" id="a-form-init">card info when created
            <form id="text-card-info-a" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="1"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_answer_disp_order}}"/>
            </form>
            <form id="image-card-info-a" method="post" action="">
                @csrf
                <input type="hidden" name="qa_id" value="{{$qa->id}}"/>
                <input type="hidden" name="screen_classification" value="2"/>
                <input type="hidden" name="alignment" value="1"/>
                <input type="hidden" name="display_order" value="{{$new_answer_disp_order}}"/>
            </form>
        </div>
        <div class="qa-input-select">
            <div class="qa-i-button" id="qa-add-text-card-a">文字列</div>
            <div class="qa-i-button" id="qa-add-image-card-a">貼り付け画像</div>
        </div>
    </div>
</div>

<div class="qa-BG" id="BG" style="display: none"></div>

<div class="qa-input-dialog" id="qa-text-dialog" style="display: none; width: 45%">
    <div class="drag-header" style="margin-bottom: 10px">文字列の編集</div>
    <form id="qa-text-form" class="qa-input-form" method="post" action="">
        @csrf
        <input type="hidden" id="card_id_text" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="1"/>
        <div id="rich-text-editor"></div>
        <span class="qc-form-required" style="float: left">必須</span>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<div class="qa-input-dialog" id="qa-image-dialog" style="display: none">
    <div class="drag-header">貼り付け画像の編集</div>
    <form id="qa-image-form" class="qa-input-form" enctype="multipart/form-data" method="post" action="">
        @csrf
        <input type="hidden" id="card_id_image" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="2"/>
        <table class="dialog-table-form">
            <tr>
                <td class="dialog-td-first">ファイル</td>
                <td class="dialog-td required-input">
                    <input type="file" accept="image/*" class="dialog-file-input dialog-l" name="image"
                           id="qa-image"
                           onchange="loadFile(event)"/>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">コメント</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input dialog-l" id="image-comment" name="comment"
                           maxlength="100">
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">縦サイズ（px）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="img-height" name="height"
                           pattern="[0-9.]+"/>
                </td>
            </tr>
            </tr>
            <tr>
                <td class="dialog-td-first">横サイズ（px）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="img-width" name="length"
                           pattern="[0-9.]+"/>
                </td>
            </tr>
        </table>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<div class="qa-input-dialog" id="qa-file-dl-dialog" style="display: none">
    <div class="drag-header">ダウンロード用ファイルの編集</div>
    <form id="qa-file-dl-form" class="qa-input-form" method="post" enctype="multipart/form-data" action="">
        @csrf
        <input type="hidden" id="card_id_file_dl" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="3"/>
        <table class="dialog-table-form">
            <tr>
                <td class="dialog-td-first">ファイル</td>
                <td class="dialog-td required-input">
                    <input type="file" class="dialog-file-input dialog-l" id="file-dl" name="file"/>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">コメント</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input dialog-l" id="file-dl-comment" name="comment"
                           maxlength="100" required>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
        </table>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<div class="qa-input-dialog" id="qa-textbox-dialog" style="display: none">
    <div class="drag-header">テキストボックスの編集</div>
    <form id="qa-textbox-form" class="qa-input-form" method="post" action="">
        @csrf
        <input type="hidden" id="card_id_textbox" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="4"/>
        <table class="dialog-table-form">
            <tr>
                <td class="dialog-td-first">初期値</td>
                <td class="dialog-td" style="position: relative">
                    <textarea id="qa-dialog-textarea" class="dialog-text-area" name="contentias" maxlength="100"
                              required></textarea>
                    <span class="qc-form-required" style="position: absolute;left: 315px;top: 35%">必須</span>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">縦（行　数）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="textbox-height"
                           name="height" pattern="[0-9.]+"
                           required>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">横（文字数）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="textbox-length"
                           name="length" pattern="[0-9.]+"
                           required>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
        </table>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<div class="qa-input-dialog" id="qa-file-ul-dialog" style="display: none">
    <div class="drag-header">テキストボックスの編集</div>
    <form id="qa-file-ul-form" class="qa-input-form" method="post" action="">
        @csrf
        <input type="hidden" id="card_id_file_ul" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="5"/>
        <table class="dialog-table-form">
            <tr>
                <td class="dialog-td-first">横（ＭＢ）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="file_size" name="file_size"
                           pattern="[0-9.]+" required>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">コメント</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input dialog-l" id="qa-file-ul-comment" name="comment"
                           maxlength="100" required>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
        </table>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<div class="qa-input-dialog" id="qa-text-dialog-ans" style="display: none">
    <div class="drag-header">文字列の編集</div>
    <form id="qa-text-form-ans" class="qa-input-form" method="post" action="">
        @csrf
        <input type="hidden" id="card_id_text_ans" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="1"/>
        <table class="dialog-table-form">
            <tr>
                <td class="dialog-td-first">横（文字数）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input dialog-l" id="text-content-ans" name="contentias"
                           maxlength="100" required>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">リンク画面</td>
                <td class="dialog-td">
                    <select class="dialog-text-input dialog-l" id="text-qa-linked" name="qa_linked" required>
                        <option value=""></option>
                        @if(isset($qa_linked_dialog))
                            @foreach($story_list as $story)
                                <optgroup label="{{$story->story}}">
                                    @foreach($qa_linked_dialog as $qa_linked_item)--}}
                                    @if($qa_linked_item->story == $story->story)
                                        <option value="{{$qa_linked_item->qaLinkedID}}">{{$qa_linked_item->qaLinked}}</option>
                                    @endif
                                    @endforeach
                                    <option disabled></option>
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
        </table>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<div class="qa-input-dialog" id="qa-image-dialog-ans" style="display: none">
    <div class="drag-header">貼り付け画像の編集</div>
    <form id="qa-image-form-ans" class="qa-input-form" enctype="multipart/form-data" method="post" action="">
        @csrf
        <input type="hidden" id="card_id_image_ans" name="id" value=""/>
        <input type="hidden" name="screen_classification" value="2"/>
        <table class="dialog-table-form">
            <tr>
                <td class="dialog-td-first">ファイル</td>
                <td class="dialog-td required-input">
                    <input type="file" accept="image/*" class="dialog-file-input dialog-l" name="image"
                           id="qa-image-ans"
                           onchange="loadFileA(event)"/>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">コメント</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input dialog-l" id="image-comment-ans" name="comment"
                           maxlength="100">
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">縦サイズ（px）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="img-height-ans"
                           name="height" pattern="[0-9.]+"/>
                </td>
            </tr>
            </tr>
            <tr>
                <td class="dialog-td-first">横サイズ（px）</td>
                <td class="dialog-td">
                    <input type="text" class="dialog-text-input number-input dialog-s" id="img-width-ans"
                           name="length" pattern="[0-9.]+"/>
                </td>
            </tr>
            <tr>
                <td class="dialog-td-first">リンク画面</td>
                <td class="dialog-td">
                    <select class="dialog-text-input dialog-l" id="image-qa-linked" name="qa_linked" required>
                        <option value=""></option>
                        @if(isset($qa_linked_dialog))
                            @foreach($story_list as $story)
                                <optgroup label="{{$story->story}}">
                                    @foreach($qa_linked_dialog as $qa_linked_item)--}}
                                    @if($qa_linked_item->story == $story->story)
                                        <option value="{{$qa_linked_item->qaLinkedID}}">{{$qa_linked_item->qaLinked}}</option>
                                    @endif
                                    @endforeach
                                    <option disabled></option>
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                    <span class="qc-form-required" style="top: 35%">必須</span>
                </td>
            </tr>
        </table>
        <div class="qa-dialog-button-bar">
            <button class="qa-dialog-button qa-dialog-add" type="submit">登録</button>
            <button class="qa-dialog-button qa-dialog-cancel" type="button">戻る</button>
        </div>
    </form>
</div>

<script>
    var Font = Quill.import('formats/font');
    Font.whitelist = ['mirza', 'roboto', 'arial', 'MS UI Gothic', 'Meiryo UI', '游ゴシック', 'sans-serif'];
    Quill.register(Font, true);
    var fonts = ['roboto', 'arial', 'MS UI Gothic', 'Meiryo UI', '游ゴシック', 'sans-serif'];
    var fullEditor = new Quill('#rich-text-editor', {
        bounds: '#rich-text-editor',
        modules: {
            'toolbar': [
                [{'size': []}],
                ['bold', 'italic', 'underline', 'strike'],
                [{'color': []}, {'background': []}],
                [{'script': 'super'}, {'script': 'sub'}],
                [{'header': '1'}, {'header': '2'}, 'blockquote'],
                [{'list': 'ordered'}, {'list': 'bullet'}, {'indent': '-1'}, {'indent': '+1'}],
                [{'align': []}],
                ['link'],
                ['clean']
            ],
        },
        placeholder: "下記のボックスの中に、職場の問題を書き出してください...",
        theme: 'snow'
    });
</script>
<script>
    $(".number-input").each(function () {
        $(this).inputFilter(function (value) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) > 0);
        });
    });
</script>
<script>
    let url = "{{ url('/') }}";
    inputBtnQ(url);
    formatBtnQ();
    let BG = $('#BG');
    var target_card;
    var qa_card_collection_q = $('#qa-card-collection-q');
    let rq = '<span class="qc-form-required-special text-danger" style="top: 35%">必須</span>';
    qa_card_collection_q.on('click', '.qa-card>.qa-input-field>.text-content', function () {
        BG.show();
        $('#qa-text-dialog').show('drop', {direction: "up"}, 200);
        $('#qa-text-dialog #card_id_text').val($(this).parent().parent().attr('id'));
        target_card = $(this);
        fullEditor.setContents(JSON.parse($(this).find('.editable-content').text()));
    });
    qa_card_collection_q.on('click', '.qa-card>.qa-input-field>.image-content', function () {
        BG.show();
        let qaImageDialog = $('#qa-image-dialog');
        qaImageDialog.show('drop', {direction: "up"}, 200);
        let require = $(this).find('.qa-real-required').text();
        if (!require) {
            let rqAlready = qaImageDialog.find('.qc-form-required-special').text();
            if (!rqAlready) {
                qaImageDialog.find('.required-input').append(rq);
                qaImageDialog.find('input[type=file]').prop('required', true);
            }
        } else {
            qaImageDialog.find('.qc-form-required-special').remove();
            qaImageDialog.find('input[type=file]').prop('required', false);
        }
        $('#qa-image-dialog #card_id_image').val($(this).parent().parent().attr('id'));
        $('#qa-image-dialog #image-comment').val($(this).find('.qa-real-content').text());
        $('#qa-image-dialog #img-height').val($(this).find('img').attr('height'));
        $('#qa-image-dialog #img-width').val($(this).find('img').attr('width'));
    });
    qa_card_collection_q.on('click', '.qa-card>.qa-input-field>.file-dl-content', function () {
        BG.show();
        let qaDlDialog = $('#qa-file-dl-dialog');
        qaDlDialog.show('drop', {direction: "up"}, 200);
        let require = $(this).find('.qa-real-required').text();
        if (!require) {
            let rqAlready = qaDlDialog.find('.qc-form-required-special').text();
            if (!rqAlready) {
                qaDlDialog.find('.required-input').append(rq);
                qaDlDialog.find('input[type=file]').prop('required', true);
            }
        } else {
            qaDlDialog.find('.qc-form-required-special').remove();
            qaDlDialog.find('input[type=file]').prop('required', false);
        }
        $('#qa-file-dl-dialog #card_id_file_dl').val($(this).parent().parent().attr('id'));
        $('#qa-file-dl-dialog #file-dl-comment').val($(this).find('.qa-real-content').text());
    });
    qa_card_collection_q.on('click', '.qa-card>.qa-input-field>.textbox-content', function () {
        BG.show();
        $('#qa-textbox-dialog').show('drop', {direction: "up"}, 200);
        $('#qa-textbox-dialog #card_id_textbox').val($(this).parent().parent().attr('id'));
        $('#qa-textbox-dialog #qa-dialog-textarea').val($(this).find('.qa-real-content').text());
        $('#qa-textbox-dialog #textbox-height').val($(this).find('.card-textbox-height').text());
        $('#qa-textbox-dialog #textbox-length').val($(this).find('.card-textbox-length').text());
    });
    qa_card_collection_q.on('click', '.qa-card>.qa-input-field>.file-ul-content', function () {
        BG.show();
        $('#qa-file-ul-dialog').show('drop', {direction: "up"}, 200);
        $('#qa-file-ul-dialog #card_id_file_ul').val($(this).parent().parent().attr('id'));
        $('#qa-file-ul-dialog #qa-file-ul-comment').val($(this).find('.qa-real-content').text());
        $('#qa-file-ul-dialog #file_size').val($(this).find('.qa-file-size').text());
    });

    var qa_card_collection_a = $('#qa-card-collection-a');
    qa_card_collection_a.on('click', '.qa-card>.qa-input-field>.text-content', function () {
        BG.show();
        $('#qa-text-dialog-ans').show('drop', {direction: "up"}, 200);
        $('#qa-text-dialog-ans #card_id_text_ans').val($(this).parent().parent().attr('id'));
        $('#qa-text-dialog-ans #text-content-ans').val($(this).find('.qa-real-content').text());
        // $('#qa-text-dialog-ans #text-qa-linked').find('option:contains(' + $(this).find('.qa-linked-content').text() + ')').attr('selected', 'selected');
        $('#qa-text-dialog-ans #text-qa-linked').val($(this).find('.linked-id').val());
    });
    qa_card_collection_a.on('click', '.qa-card>.qa-input-field>.image-content', function () {
        BG.show();
        let qaImageDialog = $('#qa-image-dialog-ans');
        qaImageDialog.show('drop', {direction: "up"}, 200);
        let require = $(this).find('.qa-real-required').text();
        if (!require) {
            let rqAlready = qaImageDialog.find('.qc-form-required-special').text();
            if (!rqAlready) {
                qaImageDialog.find('.required-input').append(rq);
                qaImageDialog.find('input[type=file]').prop('required', true);
            }
        } else {
            qaImageDialog.find('.qc-form-required-special').remove();
            qaImageDialog.find('input[type=file]').prop('required', false);
        }
        $('#qa-image-dialog-ans #card_id_image_ans').val($(this).parent().parent().attr('id'));
        $('#qa-image-dialog-ans #image-comment-ans').val($(this).find('.qa-real-content').text());
        $('#qa-image-dialog-ans #img-height-ans').val($(this).find('img').attr('height'));
        $('#qa-image-dialog-ans #img-width-ans').val($(this).find('img').attr('width'));
        // $('#qa-image-dialog-ans #image-qa-linked').find('option:contains(' + $(this).find('.qa-linked-content').text() + ')').attr('selected', 'selected');
        $('#qa-image-dialog-ans #image-qa-linked').val($(this).find('.linked-id').val());
    });
    // $(function () {

    //Saving edit
    $('#qa-text-form').on('submit', function (ev) {
        ev.preventDefault();
        let relative_card = $(this).find('input[name=id]').val();
        target_card.find('.editable-content').text(JSON.stringify(fullEditor.getContents()));
        let notEmpty = fullEditor.root.innerHTML !== '<p><br></p>';
        if (notEmpty) {
            var formData = new FormData(this);
            formData.append('contentias', fullEditor.root.innerHTML);
            formData.append('delta', JSON.stringify(fullEditor.getContents()));
            $.ajax({
                url: url + "/qa/question-edit",
                type: 'POST',
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                success: function (data) {
                    displayData(data, relative_card);
                    fullEditor.setContents([{insert: '\n'}]);
                },
                error: function () {
                    alert("Bad submit");
                }
            });
            $(this).trigger('reset');
            $(this).parent().hide('fade');
            $('#BG').hide();
        } else {
            alert("{{\App\Enums\StaticConfig::$Required}}");
        }
    });
    $('#qa-image-form').on('submit', function (ev) {
        ev.preventDefault();
        let relative_card = $(this).find('input[name=id]').val();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: url + "/qa/question-edit",
            type: 'POST',
            data: formData,
            processData: false,
            cache: false,
            contentType: false,
            success: function (data) {
                displayData(data, relative_card);
            },
            error: function () {
                alert("Bad submit");
            }
        });
        $(this).trigger('reset');
        $(this).parent().hide('fade');
        $('#BG').hide();
    });
    $('#qa-file-dl-form').on('submit', function (ev) {
        ev.preventDefault();
        let baseUrl = location.origin;
        let relative_card = $(this).find('input[name=id]').val();
        var formData = new FormData($(this)[0]);
        let this_card = $('#' + relative_card);
        this_card.find('.qa-icon-holder img').hide();
        this_card.find('.qa-icon-holder .qa-file-ext').hide();
        this_card.find('.qa-icon-holder').append('<img class="loading-icon" style="margin-left: 5px" src="' + baseUrl + '/images/loading.gif" alt="" />');
        $.ajax({
            url: url + "/qa/question-edit",
            type: 'POST',
            data: formData,
            processData: false,
            cache: false,
            contentType: false,
            success: function (data) {
                this_card.find('.loading-icon').remove();
                this_card.find('.qa-icon-holder img').show();
                this_card.find('.qa-icon-holder .qa-file-ext').show();
                displayData(data, relative_card);
            },
            error: function () {
                alert("Bad submit");
            }
        });
        $(this).trigger('reset');
        $(this).parent().hide('fade');
        $('#BG').hide();
    });
    $('#qa-textbox-form').on('submit', function (ev) {
        ev.preventDefault();
        let relative_card = $(this).find('input[name=id]').val();
        $.ajax({
            url: url + "/qa/question-edit",
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                displayData(data, relative_card);
            },
            error: function () {
                alert("Bad submit");
            }
        });
        $(this).trigger('reset');
        $(this).parent().hide('fade');
        $('#BG').hide();
    });
    $('#qa-file-ul-form').on('submit', function (ev) {
        ev.preventDefault();
        let relative_card = $(this).find('input[name=id]').val();
        $.ajax({
            url: url + "/qa/question-edit",
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                displayData(data, relative_card);
            },
            error: function () {
                alert("Fail!");
            }
        });
        $(this).trigger('reset');
        $(this).parent().hide('fade');
        $('#BG').hide();
    });
    $('#qa-text-form-ans').on('submit', function (ev) {
        ev.preventDefault();
        let relative_card = $(this).find('input[name=id]').val();
        $('#' + relative_card).find('.linked-id').val($(this).find('#text-qa-linked').val());
        $.ajax({
            url: url + "/qa/answer-edit",
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                displayDataA(data[0], relative_card);
            },
            error: function () {
                alert("Bad submit");
            }
        });
        $(this).trigger('reset');
        $(this).parent().hide('fade');
        $('#BG').hide();
    });
    $('#qa-image-form-ans').on('submit', function (ev) {
        ev.preventDefault();
        let relative_card = $(this).find('input[name=id]').val();
        $('#' + relative_card).find('.linked-id').val($(this).find('#image-qa-linked').val());
        let formData = new FormData($(this)[0]);
        $.ajax({
            url: url + "/qa/answer-edit",
            type: 'POST',
            data: formData,
            processData: false,
            cache: false,
            contentType: false,
            success: function (data) {
                displayDataA(data[0], relative_card);
            },
            error: function () {
                alert("Bad submit");
            }
        });
        $(this).trigger('reset');
        $(this).parent().hide('fade');
        $('#BG').hide();
    });

    //Cancel button
    $('.qa-input-dialog').on('click', '.qa-dialog-cancel', function () {
        $(this).parent().parent().parent().hide("fade", {duration: 200});
        $(this).closest('form').trigger('reset');
        BG.hide();
    });

    $('.qa-input-dialog').draggable({handle: ".drag-header"});

    var img_url;
    var img_urlA;

    function loadFile(event) {
        img_url = URL.createObjectURL(event.target.files[0]);
    }

    function loadFileA(event) {
        img_urlA = URL.createObjectURL(event.target.files[0]);
    }

    $('input[type=file]').bind('change', function () {
            if (this.name === 'image') {
                let ext = getExt(this.files[0].name).toLowerCase();
                let types = ['jpeg', 'gif', 'png', 'bmp', 'jpg', 'tiff', 'tif', 'dib', 'webp', 'svgz', 'ico', 'xbm', 'jfif', 'pjpeg'];
                if (types.indexOf(ext) === -1) {
                    alert("{{\App\Enums\StaticConfig::$Image_Only}}");
                    this.value = "";
                } else if (this.files[0].size > 10485760) {
                    alert("{{\App\Enums\StaticConfig::$File_Size_10M}}");
                    this.value = "";
                }
            } else {
                let ext = getExt(this.files[0].name).toLowerCase();
                let types = ['php', 'exe', 'bat', 'bmp', 'js', 'jar', 'vbs', 'vb', 'dll', 'py', 'tmp'];
                if (types.indexOf(ext) !== -1) {
                    alert("{{\App\Enums\StaticConfig::$File_Not_Allowed}}");
                    this.value = "";
                } else if (this.files[0].size > 10485760) {
                    alert("{{\App\Enums\StaticConfig::$File_Size_10M}}");
                    this.value = "";
                }
            }
        }
    );

    // var extension;
    function getExt(fileName) {
        let temp = fileName.slice(0, fileName.lastIndexOf('.') + 1);
        let extension = fileName.replace(temp, '');
        return extension.toLowerCase();
    }

    function getFileName(fileName) {
        let temp = fileName.slice(0, fileName.indexOf('.') + 1);
        let name = fileName.replace(temp, '');
        return name;
    }

    function generateColor(ext) {
        var letters = 'c7c3521f36c102bf1bbf07c83518bf36';
        if (ext.length < 3) {
            ext = ext + "z";
        }
        let r = Math.floor(ext.charCodeAt(0) / 100 * 16);
        let g = Math.floor(ext.charCodeAt(1) / 100 * 16);
        let b = Math.floor(ext.charCodeAt(2) / 100 * 16);
        return "#" + letters[r] + letters[r + 1] + letters[g] + letters[g + 1] + letters[b] + letters[b + 1];
    }

    function displayData(datum, relative_card) {
        let card = $('#' + relative_card);
        let data = datum[0];
        let color = datum[1] ? datum[1] : "";
        let type = parseInt(data.screen_classification);
        switch (type) {
            case 1:
                card.find('.qa-real-content').html(data.content);
                break;
            case 2:
                let image = card.find('img');
                image.attr('src', img_url);
                image.attr('height', data.height);
                image.attr('width', data.length);
                card.find('.qa-real-content').text(data.comment);
                card.find('.qa-real-required').text(data.file_name);
                break;
            case 3:
                if (data.file_name) {
                    card.find('.qa-file-ext').text(getExt(data.file_name));
                    card.find('.qa-file-ext').css('background-color', color);
                    card.find('.qa-file-dl-name').text(getFileName(data.file_name));
                    card.find('.qa-real-required').text(data.file_name);
                }
                card.find('.qa-real-content').text(data.content);
                break;
            case 4:
                card.find('.qa-real-content').text(data.content);
                card.find('textarea').attr('cols', data.length);
                card.find('textarea').attr('rows', data.height);
                card.find('.card-textbox-height').text(data.height);
                card.find('.card-textbox-length').text(data.length);
                break;
            case 5:
                card.find('.qa-real-content').text(data.content);
                card.find('.qa-file-size').text(data.file_size);
                break;
        }
    }

    function displayDataA(data, relative_card) {
        let card = $('#' + relative_card);
        let type = parseInt(data.screen_classification);
        switch (type) {
            case 1:
                card.find('.qa-real-content').text(data.content);
                // card.find('.qa-linked-content-story').text(data.story_name);
                card.find('.qa-linked-content').text(data.qaLinked);
                break;
            case 2:
                let image = card.find('img');
                image.attr('src', img_urlA);
                image.attr('height', data.height);
                image.attr('width', data.length);
                card.find('.qa-real-content').text(data.comment);
                card.find('.qa-linked-content').text(data.qaLinked);
                break;
        }
    }

    // });
</script>


