<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Enums\AccessAuthority;
use App\Enums\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        $calendar = DB::table('calendars')->get();
        if(Auth::user()->access_authority == AccessAuthority::ADMIN) {
            $activity_other = DB::table('activities')->where('activity_category', Activity::MEETING)->where('circle_id', '<>',session('circle.id'))->get();
            if (session()->has('circle')) {
                $activity_own = DB::table('activities')->where('activity_category', Activity::MEETING)->where('circle_id', session('circle.id'))->get();
            }
        } elseif (session()->has('circle')){
            $activity_own = DB::table('activities')->where('activity_category', Activity::MEETING)->where('circle_id', session('circle.id'))->get();
        }

        if (session('toppage') != AccessAuthority::USER) {
            return view('calendar.calendar',
                [
                    'calendar' => isset($calendar) ? $calendar : null,
                    'activity_own' => isset($activity_own) ? $activity_own : null,
                    'activity_other' => isset($activity_other) ? $activity_other : null,
                ]);
        } else {
            return view('calendar.calendar-circle',
                [
                    'calendar' => isset($calendar) ? $calendar : null,
                    'activity_own' => isset($activity_own) ? $activity_own : null,
                    'activity_other' => isset($activity_other) ? $activity_other : null,
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $calendar = new Calendar();
            $calendar->dates = $request->dates;
            $calendar->times = $request->times;
            $calendar->content = $request->contents;
            $calendar->created_by = Auth::id();
            $calendar->updated_by = Auth::id();
            $calendar->save();
        return $calendar->id;
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
            $id = $request->total_event_id;
            $calendar = Calendar::find($id);
            $calendar->dates = $request->eventDate;
            $calendar->times = $request->eventTime;
            $calendar->content = $request->eventTitle;
            $calendar->created_by = Auth::id();
            $calendar->updated_by = Auth::id();
            $calendar->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->event_id_info;
        $calendar = Calendar::find($id);
        $calendar->delete();
    }
}