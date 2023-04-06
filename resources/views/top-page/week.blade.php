<style>
    .th-left-alg {
        text-align: left !important;
        border-bottom: none !important;
        font-weight: normal;
    }

    .td-left-alg {
        border-width: 0 1px 0 0 !important;
    }

    .td-left-alg-last-child {
        border: none !important;
    }

    .fc-today-none-highlight {
        background-color: inherit !important;
    }
</style>

<div id="calendar"></div>

<script>
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
    @if(isset($activity_own))
    @foreach($activity_own as $activity_own_item)
    eventAdd(
        "{{$activity_own_item->id}}",
        "[会]{{ $activity_own_item->activity_title }}",
        "{{ $activity_own_item->date_intended }}",
            @if(session('toppage') != \App\Enums\AccessAuthority::USER)
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
            @if(session('toppage') != \App\Enums\AccessAuthority::USER)
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

    //Add event area

    var today = moment().format("MM-DD");
    $('#calendar').fullCalendar({
        firstDay: today,
        defaultView: 'basicWeek',
        height: 150,
        columnHeader: true,
        columnHeaderFormat: "D[(]ddd[)]",
        header: false,
        eventLimit: 3, // allow "more" link when too many events
        eventColor: 'transparent',
        displayEventTime: false,
        eventSources: [eventTotal, eventCircle, eventOwn],
        viewRender: function () {
            $('.fc th').addClass('th-left-alg');
            $('.fc td').addClass('td-left-alg');
            $('.fc td:last-child').addClass('td-left-alg-last-child');
            $('.fc-title').css('font-size', '14px');
            $('.fc-today').addClass('fc-today-none-highlight');
        }
    });

</script>
