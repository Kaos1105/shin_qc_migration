@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
                   $trail->push('トップページ', route('toppageoffice'));
               })
   }}

    {{
        Breadcrumbs::for('calendar', function ($trail) {
                $trail->parent('toppage');
                $trail->push('ＱＣ活動カレンダー');
            })
    }}

    {{ Breadcrumbs::render('calendar') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <div class="qc-form-radio-group">
            <label class="form-check-label">
                {{--                @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)--}}
                @if(session('toppage') != \App\Enums\AccessAuthority::USER)
                    <input type="radio" class="form-check-input" id="total-filter" name="filter" value="1" checked>
                    <span>全て</span>
                @else
                    <input type="radio" class="form-check-input" id="total-filter" name="filter" value="1" checked>
                    <span>全体</span>
                @endif
            </label>
            <label class="form-check-label">
{{--                @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)--}}
                @if(session('toppage') != \App\Enums\AccessAuthority::USER)
                    <input type="radio" class="form-check-input" id="office-filter" name="filter" value="2">
                    <span>事務局</span>
                @else
                    <input type="radio" class="form-check-input" id="circle-filter" name="filter" value="2">
                    <span>サークル</span>
                @endif
            </label>
        </div>
        <a class="btn btn-back float-right" href="{{ URL::to('top-page') }}">戻る</a>
    </div>

    <div class="container-fluid bottom-fix" style="margin-top: 5rem">
        <div id='calendar'></div>
        <div>色について・・・<span style="color: red">赤</span>：事務局、<span style="color: blue">青</span>：他のサークル、<span
                    style="color: green">緑</span>：自サークル
        </div>
    </div>

    <div id="popup-container" class="draggable" style="display: none">
        <div class="event-header" style="cursor: default">
            <span><strong>イベント登録</strong></span>
        </div>
        <span class="xclose">&times;</span>
        <div id="body">
            <form id="event-adding" method="POST" action="">
                @csrf
                <input type='text' id="event" name="contents"/>
                <input type="hidden" name="dates" id="chosen_date"/>
                <input type="hidden" name="times" id="chosen_time"/>
            </form>
            <div class="float-right btn-area" style="padding-right: 0">
                <button id="add-event-save">登録</button>
                <button id="add-event-cancel">キャンセル</button>
            </div>
            <div id="date-info">
                <span>日付: </span><span id="selected-date"></span>
            </div>
        </div>
    </div>

    <div id="event-box" class="draggable" style="display:none;">
        <div class="event-header" style="cursor: default">
            <span><strong>イベント</strong></span>
        </div>
        <span class="xclose">&times;</span>
        <div>
            <table class="table event-popup-table">
                <tr>
                    <td>イベント名</td>
                    <td>
                        <span id="eventContent"></span>
                    </td>
                </tr>
                <tr>
                    <td>日付</td>
                    <td>
                        <span id="startTime"></span>
                    </td>
                </tr>
            </table>
            <form
            {{ Form::model($calendar, array('route' => array('calendar.destroy', 0), 'method' => 'PUT', 'id' => 'form-delete-event')) }}
            @csrf
            <input type="hidden" name="event_id_info" id="event_id_info" value="">
            {!! Form::close() !!}
        </div>
        <div class="float-right">
            <a class="event-option-link" id="eventEdit" href="" target="_self">修正</a>
            <a class="event-option-link" id="eventDelete" href="" target="_self">削除</a>
        </div>
    </div>

    <div id="dim-BG" style="display:none; z-index: 200"></div>
    <div id="edit-popup" class="draggable" style="display:none; z-index: 500">
        <div class="event-header" style="cursor: default">
            <span><strong>イベント編集</strong></span>
        </div>
        <span class="xclose">&times;</span>
        <div>
            {{ Form::model($calendar, array('route' => array('calendar.update', 0), 'method' => 'PUT', 'id' => 'form-edit-event')) }}
            @csrf
            <table class="table event-popup-table">
                <tr>
                    <td>イベント名</td>
                    <td>
                        <input type="hidden" id="total-event-id" name="total_event_id" value=""/>
                        <input class="event-input-box" type="text" name="eventTitle" id="eventTitle"/>
                    </td>
                </tr>
                <tr>
                    <td>日付</td>
                    <td>
                        <input class="event-input-box" type="text" name="eventDate" id="eventDate"
                               style="margin-bottom: -10px;"/>
                        <img id="datepicker-icon" class="date-selector-icon"
                             src="{{ asset('images/calendar_24px.png')}}"
                             alt="date" height="24" width="24">
                        <input type="hidden" id="eventTime" name="eventTime"/>
                    </td>
                </tr>
            </table>
            {{ Form::close() }}
        </div>
        <div class="float-right event-box-edit-btn">
            <button type="button" id="edit-event-save">登録</button>
            <button type="button" id="edit-event-cancel">キャンセル</button>
        </div>
    </div>

    <script>

        $(function () {
            function gotoDate() {
                let gotoDates = sessionStorage.getItem("gotoDate");
                $('#calendar').fullCalendar('gotoDate', gotoDates);
            }

            window.onload = function () {
                var reloading = sessionStorage.getItem("reloading");
                if (reloading) {
                    sessionStorage.removeItem("reloading");
                    gotoDate();
                }
            };

            function reloadP(date) {
                sessionStorage.setItem("reloading", "true");
                sessionStorage.setItem("gotoDate", date);
                document.location.reload();
            }

            //Drag popup
            var recoupLeft, recoupTop;
            $('.draggable').draggable({
                start: function (event, ui) {
                    var left = parseInt($(this).css('left'), 10);
                    left = isNaN(left) ? 0 : left;
                    var top = parseInt($(this).css('top'), 10);
                    top = isNaN(top) ? 0 : top;
                    recoupLeft = left - ui.position.left;
                    recoupTop = top - ui.position.top;
                },
                drag: function (event, ui) {
                    ui.position.left += recoupLeft;
                    ui.position.top += recoupTop;
                }
            });
            $('#total-filter').prop('checked', 'checked');
            //End drag

            var officeTotal = [],
                otherCircle = [],
                ownCircle = [];
            var eventTotal = {
                    events: officeTotal,
                    textColor: 'red'
                },
                eventCircle = {
                    events: otherCircle,
                    textColor: 'blue'
                },
                eventOwn = {
                    events: ownCircle,
                    textColor: 'green'
                };

            function eventAdd(id, title, start, url, eventType) { //title is the name of event, start is date of event, must be YYYY-MM-DD, event type is 'office' OR 'other' OR 'own'
                let event = {
                    id: id,
                    title: title,
                    start: start,
                    url: url
                };
                if (eventType == 'office') {
                    officeTotal.push(event);
                } else if (eventType == 'other') {
                    otherCircle.push(event);
                } else if (eventType == 'own') {
                    ownCircle.push(event);
                }
            }

            //Add event area
            function initEvent() {
                @if(isset($activity_own))
                @foreach($activity_own as $activity_own_item)
                eventAdd(
                    "{{$activity_own_item->id}}",
                    "[会]{{ $activity_own_item->activity_title }}",
                    "{{ $activity_own_item->date_intended }}",
                        @if(\Illuminate\Support\Facades\Auth::user()->access_authority == \App\Enums\AccessAuthority::ADMIN)
                            "{{ URL::to('activity-office/show/'.$activity_own_item->id.'?callback=calendar') }}",
                        @else
                            "{{ URL::to('activity/'.$activity_own_item->id.'?callback=calendar') }}",
                        @endif
                            "own"
                );
                @endforeach
                @endif
                @if(isset($activity_other))
                @foreach($activity_other as $activity_other_item)
                eventAdd(
                    "{{$activity_other_item->id}}",
                    "[会]{{ $activity_other_item->activity_title }}",
                    "{{ $activity_other_item->date_intended }}",
                        @if(\Illuminate\Support\Facades\Auth::user()->access_authority == \App\Enums\AccessAuthority::ADMIN)
                            "{{ URL::to('activity-office/show/'.$activity_other_item->id.'?callback=calendar') }}",
                        @else
                            "{{ URL::to('activity/'.$activity_other_item->id.'?callback=calendar') }}",
                        @endif
                            "other"
                );
                @endforeach
                @endif
                @if(isset($calendar))
                @foreach($calendar as $calendar_item)
                eventAdd(
                    "{{$calendar_item->id}}",
                    "{{ $calendar_item->content }}",
                    "{{ $calendar_item->dates }}",
                    "",
                    "office"
                );
                @endforeach
                @endif
            }

            initEvent();
            //Add event area

            raiseCalendar(eventTotal, eventCircle, eventOwn);

            function raiseCalendar(a, b, c) {
                $('#calendar').fullCalendar({
                    height: $(window).height() * 0.78,
                    eventLimit: true,
                    header: {
                        left: "",
                        center: 'prevYear, prev, title, next, nextYear',
                        right: ""
                    },
                    fixedWeekCount: false,
                    eventColor: 'transparent',
                    handleWindowResize: true,
                    eventSources: [a, b, c],
                    eventClick: function (event) {
                        $('#popup-container').hide("puff", {percent: 97}, 120);
                        $('#event_id_info').val(event.id);
                        $('#eventContent').text(event.title);
                        $('#startTime').text(moment(event.start).format('YYYY/MM/DD'));
                        if (!event.url) {
                            eventMed = event;
                            $('#event-box').show("puff", {percent: 97}, 120);
                            $('#eventEdit').attr('href', 'javascript:void(0);');
                            $('#eventDelete').attr('href', 'javascript:void(0);');
                        }
                    },
                    eventRender: function (event, element) {
                        element.find('span.fc-title').addClass('event-decoration');
                        element.css('cursor', 'pointer');
                    },
                    dayClick: function (date) {
                        $('#event-box').hide("puff", {percent: 97}, 120);
                        $('#popup-container').show("puff", {percent: 97}, 120);
                        $('#selected-date').text(moment(date).format('YYYY/MM/DD')).effect("highlight", {color: '#ffcc66'}, 500);
                        $('#chosen_date').val(moment(date).format('YYYY-MM-DD'));
                        let now = moment().format('HH:mm:ss');
                        $('#chosen_time').val(now);
                        $('#event').focus();
                    }
                });
            }

            var eventMed;

            $('#eventEdit').click(function () {
                showEdit(eventMed);
            });

            //edit and delete event
            function showEdit(updatedEvent) {
                // updatedEvent = eventMed;
                $('#dim-BG').show();
                $('#edit-popup').show();
                $('#total-event-id').val(updatedEvent.id);
                $('#eventTitle').val(updatedEvent.title);
                $('#eventDate').val(moment(updatedEvent.start).format('YYYY/MM/DD'));
                let now = moment().format('HH:mm:ss');
                $('#eventTime').val(now);
                $('#edit-event-save').prop("disabled", true);
                $('#eventTitle').on('keyup', function () {
                    let newTitle = $('#eventTitle').val();
                    if (newTitle == updatedEvent.title) {
                        $('#edit-event-save').prop('disabled', true);
                    } else {
                        $('#edit-event-save').prop("disabled", false);
                    }
                });
                $('#eventDate').on('change', function () {
                    let newDate = $('#eventDate').val();
                    if (newDate == updatedEvent.start) {
                        $('#edit-event-save').prop('disabled', true);
                    } else {
                        $('#edit-event-save').prop("disabled", false);
                    }
                });
                $('#edit-event-save').click(function () {
                    let title = $('#eventTitle').val(),
                        date = $('#eventDate').val(),
                        id = $('#total-event-id').val();
                    updateEvent(updatedEvent, id, title, date);
                });
                $('#event-box').hide();
            }

            function updateEvent(updatedEvent, id, title, date) {
                if (!title || !date) {
                    alert('空白の予定は登録できません');
                } else if (!moment(date).isValid()) {
                    alert("日付が正しくありません。（YYYY/MM/DD）");
                } else {
                    updateForm();
                    reloadP(date);
                    $('#dim-BG').hide();
                    $('#edit-popup').hide();
                }
            }

            $('#eventDelete').click(deleteEvent);

            function deleteEvent() {
                var r = confirm("{{ App\Enums\StaticConfig::$Delete_Calendar_Event }}");
                if (r == true) {
                    deleteForm();
                    let id = $('#event_id_info').val();
                    $('#event-box').hide("puff", {percent: 97}, 120);
                    $('#calendar').fullCalendar('removeEvents', id);
                    // $('#event_id_info').val("");
                }
            }

            $('#edit-event-cancel').click(function () {
                $('#dim-BG').hide();
                $('#edit-popup').hide();
            });

            var newID;

            function newEventID(newEventID) {
                newID = newEventID;
            }

            function submitForm() {
                $.ajax({
                    async: false,
                    url: "{{route('calendar.store')}}",
                    type: "POST",
                    data: $('#event-adding').serialize(),
                    success: function (data) {
                        newEventID(data);
                    }
                });
            }

            var updatedID;

            function updatedEventID(updatedEventID) {
                updatedID = updatedEventID;
            }

            function updateForm() {
                $.ajax({
                    async: false,
                    url: "{{route('calendar.update', 0)}}",
                    type: "POST",
                    data: $('#form-edit-event').serialize(),
                    success: function (data) {
                        updatedEventID(data);
                    }
                });
            }

            function deleteForm() {
                $.ajax({
                    url: "{{route('calendar.destroy')}}",
                    type: "POST",
                    data: $('#form-delete-event').serialize()
                });
            }

            //add event popup buttons
            $('#add-event-save').on('click', function () {
                let title = $('#event').val();
                let date = $('#chosen_date').val();
                if (!title) {
                    alert('空白の予定は登録できません');
                } else {
                    submitForm();
                    eventAdd(newID, title, date, '', 'office');
                    $('#calendar').fullCalendar('removeEventSource', eventTotal);
                    $('#calendar').fullCalendar('addEventSource', eventTotal);
                    $('span.fc-title:contains(' + title + ')').parent().effect("highlight", 500);
                    $('#event-adding').trigger('reset');
                    $('#popup-container').hide("puff", {percent: 97}, 120);
                }
            });
            $('#add-event-cancel').on('click', function () {
                $('#popup-container').hide("puff", {percent: 97}, 120);
                $('#event').text("");
            });

            //filer event source total or circle
            {{--            @if(\Illuminate\Support\Facades\Auth::user()->access_authority != \App\Enums\AccessAuthority::ADMIN)--}}
            @if(session('toppage') == \App\Enums\AccessAuthority::USER)
            $('input[type=radio][name=filter]').change(function () {
                if (this.value == 1) {
                    $('#calendar').fullCalendar('addEventSource', eventTotal);
                } else if (this.value == 2) {
                    $('#calendar').fullCalendar('removeEventSource', eventTotal);
                }
            });
            @else
            $('input[type=radio][name=filter]').change(function () {
                if (this.value == 1) {
                    $('#calendar').fullCalendar('addEventSource', eventCircle);
                    $('#calendar').fullCalendar('addEventSource', eventOwn);
                } else if (this.value == 2) {
                    $('#calendar').fullCalendar('removeEventSources', [eventCircle, eventOwn]);
                }
            });
            @endif

            //popup close button
            $('.xclose').click(function () {
                $('#popup-container').hide("puff", {percent: 97}, 120);
                $('#event-box').hide("puff", {percent: 97}, 120);
                $('#dim-BG').hide();
                $('#edit-popup').hide();
            });

            //Date picker in edit event
            $('#eventDate').datepicker(options);
            $('#datepicker-icon').click(function () {
                $('#eventDate').focus();
            });
        });

        //prevent Enter key event
        $(document).keypress(
            function (event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            });
    </script>
@endsection
