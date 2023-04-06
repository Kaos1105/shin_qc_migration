<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Enums\UseClassificationEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $calendarCollection = $this -> getWeekDisplay();
        return view('home',['calendarCollection' => $calendarCollection]);
    }

    protected function getWeekDisplay()
    {
        $today = Carbon::now();
        //create collection data
        $calendarCollection = collect([$today]);
        for ($i = 1; $i <= 6; $i++)
        {
            $dayNext = Carbon::now() -> addDays($i);
            $calendarCollection ->push($dayNext);
        }

        return $calendarCollection;
    }

    public function cannotLogin()
    {
        return view('login.cannotlogin');
    }

    public function sendQuestion()
    {
        return view('login.sendquestion');
    }

    public function updateSession(){
        if( isset($_GET['circle_selected']) ) {
            $circle_id = $_GET['circle_selected'];
            $circle = Circle::find($circle_id);
            session()->put('circle', $circle);
            return 1;
        }
        return 0;
    }
    public function testSession(){
        //return session('circle.circle_name');
        return session('userKeys');
    }
    public function testUrl(){
        $thread_noti = DB::table('threads')
            ->where('is_display', UseClassificationEnum::USES)
            ->where('use_classification', UseClassificationEnum::USES)
            ->pluck('id');
        $noti_mark = DB::table('topics')->whereIn('thread_id', $thread_noti)->whereRaw('created_at > DATE_ADD(NOW(), INTERVAL -24 HOUR)')->first();
        return isset($noti_mark) ? 1 : 0;
    }
}
