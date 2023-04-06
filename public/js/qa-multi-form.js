"use strict";
let base = location.origin;
let qa_card_head = '<div class="qa-card" id="';
let qa_card_main = '"><div class="qa-format-button-group"><button class="qa-f-button qa-align qa-to-left" disabled>&lt;</button><button class="qa-f-button qa-align qa-center">=</button><button class="qa-f-button qa-align qa-to-right">&gt;</button><button class="qa-f-button up-down qa-up">&#9651;</button><button class="qa-f-button up-down qa-down">&#9661;</button><button class="qa-f-button qa-remove">&#10799;</button></div><div class="qa-input-field">';
let closure_tail = '</div></div>';
let text_content = '<div class="dialog-content text-content qa-align-left"><div><div class="qa-real-content ql-editor"></div><span class="d-none editable-content">{"ops":[{"insert":""}]}</span></div></div>';
let image_content = '<div class="dialog-content image-content qa-align-left"><div><img src="" alt="" width="" height="" /><span class="qa-real-content d-block"></span><span class="d-none qa-real-required"></span></div></div>';
let file_download_content = '<div class="dialog-content file-dl-content qa-align-left"><div><div class="qa-icon-holder"><img src="' + base + '/images/icons8-file-80.png" width="48px" height="48px" style="margin-top: -10px"/><div class="qa-file-ext"></div></div><div class="content-holder"><span class="d-block qa-real-content"></span><span class="d-inline qa-file-dl-name"></span><button class="qa-download-button" type="button" disabled>&#11123; ダウンロード</button></div><span class="d-none qa-real-required"></span></div></div>';
let textbox_content = '<div class="dialog-content textbox-content qa-align-left"><div><textarea class="line-brake-preserve fake-textbox qa-real-content" rows="" cols=""></textarea><span class="d-none card-textbox-height"></span><span class="d-none card-textbox-length"></span></div></div>';
let file_upload_content = '<div class="dialog-content file-ul-content qa-align-left"><div><div class="qa-real-content"></div><span class="d-none qa-file-size"></span><input type="file" class="fake-file" disabled/></div></div>';

let text_content_a = '<div class="dialog-content text-content qa-align-left"><div><span class="qa-real-content line-brake-preserve"></span><span class="d-block qa-linked-content"></span><input type="hidden" class="linked-id" value=""></div></div>';
let image_content_a = '<div class="dialog-content image-content qa-align-left"><div><img src="" alt="" width="" height="" /><span class="qa-real-content d-block"></span><span class="d-block qa-linked-content"></span><input type="hidden" class="linked-id" value=""></div></div>';


function inputBtnQ(url) {
    function addTextCard(addedId) {
        let new_card_id = addedId;
        let text_card = qa_card_head + new_card_id + qa_card_main + text_content + closure_tail;
        $('#qa-card-collection-q').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-btn-text').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/question-add",
            type: "POST",
            data: $('#text-card-info').serialize(),
            success: function (data) {
                changeDisp(data.display_order);
                addTextCard(data.id);
            }
        });
    });

    function addImageCard(addedId) {
        let new_card_id = addedId;
        let text_card = qa_card_head + new_card_id + qa_card_main + image_content + closure_tail;
        $('#qa-card-collection-q').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-btn-image').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/question-add",
            type: "POST",
            data: $('#image-card-info').serialize(),
            success: function (data) {
                changeDisp(data.display_order);
                addImageCard(data.id);
            }
        });
    });

    function addDownloadCard(addedId) {
        let new_card_id = addedId;
        let text_card = qa_card_head + new_card_id + qa_card_main + file_download_content + closure_tail;
        $('#qa-card-collection-q').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-btn-file-dl').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/question-add",
            type: "POST",
            data: $('#download-card-info').serialize(),
            success: function (data) {
                changeDisp(data.display_order);
                addDownloadCard(data.id);
            }
        });
    });

    function addTextboxCard(addedId) {
        let new_card_id = addedId;
        let text_card = qa_card_head + new_card_id + qa_card_main + textbox_content + closure_tail;
        $('#qa-card-collection-q').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-btn-textbox').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/question-add",
            type: "POST",
            data: $('#textbox-card-info').serialize(),
            success: function (data) {
                changeDisp(data.display_order);
                addTextboxCard(data.id);
            }
        });
    });

    function addUploadCard(addedId) {
        let new_card_id = addedId;
        let text_card = qa_card_head + new_card_id + qa_card_main + file_upload_content + closure_tail;
        $('#qa-card-collection-q').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-btn-file-ul').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/question-add",
            type: "POST",
            data: $('#upload-card-info').serialize(),
            success: function (data) {
                changeDisp(data.display_order);
                addUploadCard(data.id);
            }
        });
    });

//Answer
    function addTextCardA(addedId) {
        let new_card_id = addedId + '-a';
        let text_card = qa_card_head + new_card_id + qa_card_main + text_content_a + closure_tail;
        $('#qa-card-collection-a').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-text-card-a').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/answer-add",
            type: "POST",
            data: $('#text-card-info-a').serialize(),
            success: function (data) {
                changeDispA(data.display_order);
                addTextCardA(data.id);
            }
        });
    });

    function addImageCardA(addedId) {
        let new_card_id = addedId + '-a';
        let text_card = qa_card_head + new_card_id + qa_card_main + image_content_a + closure_tail;
        $('#qa-card-collection-a').append(text_card);
        $('#' + new_card_id).css('order', new_card_id);
    }
    $('#qa-add-image-card-a').on('click', function () {
        $.ajax({
            async: false,
            url: url + "/qa/answer-add",
            type: "POST",
            data: $('#image-card-info').serialize(),
            success: function (data) {
                changeDispA(data.display_order);
                addImageCardA(data.id);
            }
        });
    });
}

function changeDisp(disp) {
    $('#q-form-init input[name=display_order]').val(eval(disp) + 1);
}
function changeDispA(disp) {
    $('#a-form-init input[name=display_order]').val(eval(disp) + 1);
}

function formatBtnQ() {
    $('#qa-card-collection-q').on('click', '.qa-card>.qa-format-button-group>.qa-remove', function () {
        var r = confirm("このQA画面(Q)を削除しますか。");
        if (r == true) {
            let this_card = $(this).parent().parent();
            let this_card_id = this_card.attr('id');
            $.ajax({
                async: false,
                url: url + "/qa/question-delete?id=" + this_card_id,
                type: "GET",
                success: function () {
                    this_card.fadeOut(200, function () {
                        $(this).remove();
                    });
                }
            });
        }
    });

    $('#qa-card-collection-a').on('click', '.qa-card>.qa-format-button-group>.qa-remove', function () {
        var r = confirm("このQA画面(A)を削除しますか。");
        if (r == true) {
            let this_card = $(this).parent().parent();
            let this_card_id = this_card.attr('id');
            $.ajax({
                async: false,
                url: url + "/qa/answer-delete?id=" + this_card_id,
                type: "GET",
                success: function () {
                    this_card.fadeOut(200, function () {
                        $(this).remove();
                    });
                }
            });
        }
    });

    function upDownDisable() {
        $('#qa-card-collection-q .qa-card .qa-format-button-group .qa-up').first().prop('disabled', true);
        $('#qa-card-collection-q .qa-card .qa-format-button-group .qa-down').last().prop('disabled', true);
    }
    function upDownDisableA() {
        $('#qa-card-collection-a .qa-card .qa-format-button-group .qa-up').first().prop('disabled', true);
        $('#qa-card-collection-a .qa-card .qa-format-button-group .qa-down').last().prop('disabled', true);
    }

    upDownDisable();
    upDownDisableA();
    var config = {childList: true, subtree: true};
    var targetNode = document.getElementById('qa-card-collection-q');
    var targetNodeA = document.getElementById('qa-card-collection-a');
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            $('#qa-card-collection-q .qa-card .qa-format-button-group .up-down').prop('disabled', false);
            upDownDisable();
        });
    });
    var observerA = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            $('#qa-card-collection-a .qa-card .qa-format-button-group .up-down').prop('disabled', false);
            upDownDisableA();
        });
    });
    observer.observe(targetNode, config);
    observerA.observe(targetNodeA, config);

    $('#qa-card-collection-q').on('click', '.qa-card>.qa-format-button-group>.qa-up', function () {
        let this_card = $(this).parent().parent();
        let this_card_id = this_card.attr('id');
        let above_card = $(this).parent().parent().prev();
        let above_card_id = above_card.attr('id');
        $.ajax({
            async: false,
            url: url + "/qa/question-disp?id1=" + this_card_id + "&id2=" + above_card_id,
            type: "GET",
            success: function (data) {
                this_card.fadeOut(200, function () {
                    $(this).insertBefore(above_card).slideDown(300);
                });
            }
        });
    });
    $('#qa-card-collection-q').on('click', '.qa-card>.qa-format-button-group>.qa-down', function () {
        let this_card = $(this).parent().parent();
        let this_card_id = this_card.attr('id');
        let below_card = $(this).parent().parent().next();
        let below_card_id = below_card.attr('id');
        console.log(this_card_id, below_card_id);
        $.ajax({
            async: false,
            url: url + "/qa/question-disp?id1=" + this_card_id + "&id2=" + below_card_id,
            type: "GET",
            success: function (data) {
                this_card.fadeOut(200, function () {
                    $(this).insertAfter(below_card).slideDown(300);
                });
            }
        });
    });

    $('#qa-card-collection-q').on('click', '.qa-card>.qa-format-button-group>.qa-to-left', function () {
        let toLeft = 1;
        let parent_id = $(this).parent().parent().attr('id');
        let this_button = $(this);
        $.ajax({
            async: false,
            url: url + "/qa/question-align?id=" + parent_id + "&align=" + toLeft,
            type: "GET",
            success: function () {
                this_button.prop('disabled', true);
                this_button.next().prop('disabled', false);
                this_button.next().next().prop('disabled', false);
                this_button.parent().next().find('.dialog-content').removeClass('qa-align-center').removeClass('qa-align-right');
                this_button.parent().next().find('.dialog-content').addClass('qa-align-left');
            }
        });
    });
    $('#qa-card-collection-q').on('click', '.qa-card>.qa-format-button-group>.qa-center', function () {
        let center = 2;
        let parent_id = $(this).parent().parent().attr('id');
        let this_button = $(this);
        $.ajax({
            async: false,
            url: url + "/qa/question-align?id=" + parent_id + "&align=" + center,
            type: "GET",
            success: function () {
                this_button.prev().prop('disabled', false);
                this_button.prop('disabled', true);
                this_button.next().prop('disabled', false);
                this_button.parent().next().find('.dialog-content').removeClass('qa-align-left').removeClass('qa-align-right');
                this_button.parent().next().find('.dialog-content').addClass('qa-align-center');
            }
        });
    });
    $('#qa-card-collection-q').on('click', '.qa-card>.qa-format-button-group>.qa-to-right', function () {
        let toRight = 3;
        let parent_id = $(this).parent().parent().attr('id');
        let this_button = $(this);
        $.ajax({
            async: false,
            url: url + "/qa/question-align?id=" + parent_id + "&align=" + toRight,
            type: "GET",
            success: function () {
                this_button.prev().prev().prop('disabled', false);
                this_button.prev().prop('disabled', false);
                this_button.prop('disabled', true);
                this_button.parent().next().find('.dialog-content').removeClass('qa-align-center').removeClass('qa-align-left');
                this_button.parent().next().find('.dialog-content').addClass('qa-align-right');
            }
        });
    });

    $('#qa-card-collection-a').on('click', '.qa-card>.qa-format-button-group>.qa-up', function () {
        let this_card = $(this).parent().parent();
        let this_card_id = this_card.attr('id');
        let above_card = $(this).parent().parent().prev();
        let above_card_id = above_card.attr('id');
        $.ajax({
            async: false,
            url: url + "/qa/answer-disp?id1=" + this_card_id + "&id2=" + above_card_id,
            type: "GET",
            success: function (data) {
                this_card.fadeOut(200, function () {
                    $(this).insertBefore(above_card).slideDown(300);
                });
            }
        });
    });
    $('#qa-card-collection-a').on('click', '.qa-card>.qa-format-button-group>.qa-down', function () {
        let this_card = $(this).parent().parent();
        let this_card_id = this_card.attr('id');
        let below_card = $(this).parent().parent().next();
        let below_card_id = below_card.attr('id');
        $.ajax({
            async: false,
            url: url + "/qa/answer-disp?id1=" + this_card_id + "&id2=" + below_card_id,
            type: "GET",
            success: function (data) {
                this_card.fadeOut(200, function () {
                    $(this).insertAfter(below_card).slideDown(300);
                });
            }
        });
    });
    $('#qa-card-collection-a').on('click', '.qa-card>.qa-format-button-group>.qa-to-left', function () {
        let toLeft = 1;
        let parent_id = $(this).parent().parent().attr('id');
        let this_button = $(this);
        $.ajax({
            async: false,
            url: url + "/qa/answer-align?id=" + parent_id + "&align=" + toLeft,
            type: "GET",
            success: function () {
                this_button.prop('disabled', true);
                this_button.next().prop('disabled', false);
                this_button.next().next().prop('disabled', false);
                this_button.parent().next().find('.dialog-content').removeClass('qa-align-center').removeClass('qa-align-right');
                this_button.parent().next().find('.dialog-content').addClass('qa-align-left');
            }
        });
    });
    $('#qa-card-collection-a').on('click', '.qa-card>.qa-format-button-group>.qa-center', function () {
        let center = 2;
        let parent_id = $(this).parent().parent().attr('id');
        let this_button = $(this);
        $.ajax({
            async: false,
            url: url + "/qa/answer-align?id=" + parent_id + "&align=" + center,
            type: "GET",
            success: function () {
                this_button.prev().prop('disabled', false);
                this_button.prop('disabled', true);
                this_button.next().prop('disabled', false);
                this_button.parent().next().find('.dialog-content').removeClass('qa-align-left').removeClass('qa-align-right');
                this_button.parent().next().find('.dialog-content').addClass('qa-align-center');
            }
        });
    });
    $('#qa-card-collection-a').on('click', '.qa-card>.qa-format-button-group>.qa-to-right', function () {
        let toRight = 3;
        let parent_id = $(this).parent().parent().attr('id');
        let this_button = $(this);
        $.ajax({
            async: false,
            url: url + "/qa/answer-align?id=" + parent_id + "&align=" + toRight,
            type: "GET",
            success: function () {
                this_button.prev().prev().prop('disabled', false);
                this_button.prev().prop('disabled', false);
                this_button.prop('disabled', true);
                this_button.parent().next().find('.dialog-content').removeClass('qa-align-center').removeClass('qa-align-left');
                this_button.parent().next().find('.dialog-content').addClass('qa-align-right');
            }
        });
    });
}

