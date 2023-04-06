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
                <input type="radio" class="form-check-input" id="total-filter" name="filter" value="1" checked>
                <span>全体</span>
            </label>
            <label class="form-check-label">
                <input type="radio" class="form-check-input" id="circle-filter" name="filter" value="2">
                <span>サークル</span>
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
                    "{{ URL::to('activity/'.$activity_own_item->id.'?callback=calendar') }}",
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
                    "{{ URL::to('activity/'.$activity_other_item->id.'?callback=calendar') }}",
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
                    },
                    eventRender: function (event, element) {
                        element.find('span.fc-title').addClass('event-decoration');
                        element.css('cursor', 'pointer');
                    }
                });
            }

            $('input[type=radio][name=filter]').change(function () {
                if (this.value == 1) {
                    $('#calendar').fullCalendar('addEventSource', eventTotal);
                } else if (this.value == 2) {
                    $('#calendar').fullCalendar('removeEventSource', eventTotal);
                }
            });

        });
    </script>
@endsection
