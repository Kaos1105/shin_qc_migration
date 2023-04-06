<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Enums\Activity;
use App\Enums\UseClassificationEnum;
use App\Theme;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\Helper;
use Validator;
use DateTime;
use App\PromotionTheme;
use App\ActivityOther;

class ThemeController extends Controller
{

    private $rules = array(
        'theme_name' => 'required',
        "value_property" => "required",
        "value_objective" => "required",
        "date_start" => "required",
        "date_expected_completion" => "required",
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'theme_name.required' => StaticConfig::$Required,
            'value_property.required' => StaticConfig::$Required,
            'value_objective.required' => StaticConfig::$Required,
            'date_start.required' => StaticConfig::$Required,
            'date_expected_completion.required' => StaticConfig::$Required,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['filter'])) {
            $year = $_GET['filter'];
        }else{
            $year = date('Y');
        }
        $sort = 'date_start';
        $sortType = 'asc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }
        $list_year = DB::table('themes')->selectRaw('YEAR(date_start) as year')->distinct('year')->orderBy('year')->get();
        $promos = DB::table('promotion_themes')
            ->select(DB::raw('promotion_themes.theme_id, MAX(promotion_themes.progression_category) AS progress, promotion_themes.date_actual_completion AS date_actual_2'))
            ->whereNotNull('promotion_themes.date_actual_completion')
            ->groupBy('promotion_themes.theme_id');

        $dd = DB::table('themes')
            ->where('circle_id', session('circle.id'))
            ->leftJoinSub($promos, 'promos', function ($join) { $join->on('themes.id', '=', 'promos.theme_id'); });

        $act = DB::table('activities')->where('activity_category', 1)->pluck('id');

        $cc = DB::table('activity_others')
            ->whereIn('activity_id', $act)
            ->where('time', ">", 0)
            ->select('activity_others.activity_id', 'time' ,'activity_others.theme_id as theme_id_ref');

        if($year == 0){
            $theme = $dd
                ->leftJoinSub($cc, 'cc', function ($join) { $join->on('themes.id', '=', 'cc.theme_id_ref'); })
                ->select('id', 'theme_name', 'value_property', 'value_objective', 'date_start', 'date_expected_completion', 'date_actual_completion', 'progress', 'date_actual_2')
                ->addSelect(DB::raw('COUNT(activity_id) as num_meeting, SUM(time) as hours_meeting'))
                ->groupBy('themes.id')
                ->orderBy($sort, $sortType)
                ->paginate(20);
        } else {
            $theme = $dd
                ->leftJoinSub($cc, 'cc', function ($join) { $join->on('themes.id', '=', 'cc.theme_id_ref'); })
                ->whereYear('themes.date_start', '<=', $year)->whereYear('themes.date_expected_completion', '>=', $year)
                ->select('id', 'theme_name', 'value_property', 'value_objective', 'date_start', 'date_expected_completion', 'date_actual_completion', 'progress', 'date_actual_2')
                ->addSelect(DB::raw('COUNT(activity_id) as num_meeting, SUM(time) as hours_meeting'))
                ->groupBy('themes.id')
                ->orderBy($sort, $sortType)
                ->paginate(20);
        }

        return view('theme.list',
            [
                'paginate' => $theme,
                'list_year' => $list_year,
                'current_year' => isset($_GET['year'])? $_GET['year'] : null,
                'current_circle' => isset($_GET['circle'])? $_GET['circle'] : null
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (isset($_GET['theme_id'])) {
            $theme = Theme::find($_GET['theme_id']);
            if (isset($theme)) {
                return view('theme.add', ['theme' => $theme]);
            }
        }
        return view('theme.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        $theme = new Theme();
        $theme->circle_id = $request->circle_id;
        $theme->theme_name = $request->theme_name;
        $theme->value_property = $request->value_property;
        $theme->value_objective = $request->value_objective;
        $theme->date_start = $request->date_start;
        $theme->date_expected_completion = $request->date_expected_completion;
        $theme->date_actual_completion = $request->date_actual_completion;
        $theme->note = $request->note;
        $theme->created_by = Auth::id();
        $theme->updated_by = Auth::id();
        $theme->save();
        return redirect()->route('theme.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($theme_id)
    {
        $theme = Theme::find($theme_id);
        $circle = Circle::find($theme->circle_id);
        $promotion_theme_1 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 1)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_2 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 2)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_3 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 3)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_4 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 4)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_5 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 5)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_6 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 6)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();
        $promotion_theme_7 = DB::table('promotion_themes')->where('theme_id', $theme_id)->where('progression_category', 7)->select('id', 'progression_category', 'date_expected_start', 'date_expected_completion', 'date_actual_start', 'date_actual_completion', 'note')->first();

        $date_expected_start = DB::table('promotion_themes')->where('theme_id', $theme_id)->orderBy('date_expected_start', 'ASC')
            ->select('date_expected_start')->first();
        $date_expected_completion = DB::table('promotion_themes')->where('theme_id', $theme_id)->orderBy('date_expected_completion', 'DESC')
            ->select('date_expected_completion')->first();

        $date_actual_start = DB::table('promotion_themes')->where('theme_id', $theme_id)->orderBy('date_actual_start', 'ASC')
            ->select('date_actual_start')->first();
        $date_actual_completion = DB::table('promotion_themes')->where('theme_id', $theme_id)->orderBy('date_actual_completion', 'DESC')
            ->select('date_actual_completion')->first();

        $theme_start = $theme->date_start;
        $theme_complete_expected = $theme->date_expected_completion;
        $theme_complete_actual = $theme->date_actual_completion;
        $theme_complete = $theme_complete_expected > $theme_complete_actual ? $theme_complete_expected : $theme_complete_actual;

        $expected_start =  isset($date_expected_start->date_expected_start) ? $date_expected_start->date_expected_start : $theme_start;
        $expected_completion = isset($date_expected_completion->date_expected_completion) ? $date_expected_completion->date_expected_completion : $theme_complete;

        $actual_start = isset($date_actual_start->date_actual_start) ? $date_actual_start->date_actual_start : $theme_start;
        $actual_completion = isset($date_actual_completion->date_actual_completion) ? $date_actual_completion->date_actual_completion : $theme_complete;

        $datetime_str1 = min($theme_start, $expected_start, $actual_start);
        $datetime_str2 = max($theme_complete, $expected_completion, $actual_completion);

        $datetime1 = new DateTime($datetime_str1);
        $datetime2 = new DateTime($datetime_str2);
        $year1 = $datetime1->format('Y');
        $year2 = $datetime2->format('Y');
        $month1 = $datetime1->format('m');
        $month2 = $datetime2->format('m');
        $year_compare_1 = (int)$datetime1->format('Y');
        $month_compare_1 = (int)$datetime1->format('m');
        $date_compare_1 = new DateTime($year_compare_1 . '-' . $month_compare_1);
        $year_compare_2 = (int)$datetime2->format('Y');
        $month_compare_2 = (int)$datetime2->format('m');
        $date_compare_2 = new DateTime($year_compare_2 . '-' . $month_compare_2);

        $number_year = (int)$datetime2->format('Y') - $datetime1->format('Y');
        $number_month = ((int)($year2 - $year1) * 12) + (int)($month2 - $month1) + 1;
        $finish_month = (int)$datetime2->format('m');
        $start_month = (int)$datetime1->format('m');
        $start_year = (int)$datetime1->format('Y');
        $end_year = (int)$datetime2->format('Y');

        $activity_other = DB::table('activity_others')->where('theme_id', $theme_id)->where('time', '>', 0)->select('activity_id', 'time')->get();
        $meeting_times = 0;
        $meeting_total_time = 0.00;
        $study_times = 0;
        $study_total_time = 0.00;
        $kaizen_times = 0;
        $kaizen_total_time = 0.00;
        $other_times = 0;
        $other_total_time = 0.00;
        if ($activity_other) {
            foreach ($activity_other as $item) {
                $activity = \App\Activity::find($item->activity_id);
                if ($activity->activity_category == Activity::MEETING) {
                    $meeting_times += 1;
                    $meeting_total_time += $item->time;
                    continue;
                }
                if ($activity->activity_category == Activity::STUDY_GROUP) {
                    $study_times += 1;
                    $study_total_time += $item->time;
                    continue;
                }
                if ($activity->activity_category == Activity::KAIZEN) {
                    $kaizen_times += 1;
                    $kaizen_total_time += $item->time;
                    continue;
                }
                $other_times += 1;
                $other_total_time += $item->time;
            }
        }

        return view('theme.view',
            [
                'theme' => $theme,
                'circle' => $circle,
                'promotion_theme_1' => $promotion_theme_1,
                'promotion_theme_2' => $promotion_theme_2,
                'promotion_theme_3' => $promotion_theme_3,
                'promotion_theme_4' => $promotion_theme_4,
                'promotion_theme_6' => $promotion_theme_6,
                'promotion_theme_7' => $promotion_theme_7,
                'meeting_times' => $meeting_times,
                'promotion_theme_5' => $promotion_theme_5,
                'meeting_total_time' => $meeting_total_time,
                'study_times' => $study_times,
                'study_total_time' => $study_total_time,
                'kaizen_times' => $kaizen_times,
                'kaizen_total_time' => $kaizen_total_time,
                'other_times' => $other_times,
                'other_total_time' => $other_total_time,
                'number_year' => isset($number_year) ? $number_year : null,
                'number_month' => isset($number_month) ? $number_month : null,
                'finish_month' => isset($finish_month) ? $finish_month : null,
                'start_month' => isset($start_month) ? $start_month : null,
                'start_year' => isset($start_year) ? $start_year : null,
                'end_year' => isset($end_year) ? $end_year : null,
                'date_expected_start' => isset($datetime1) ? $datetime1 : null,
                'holdsort' => isset($_GET['holdsort']) ? 'yes' : null,
                'callback' => isset($_GET['callback']) ? 'yes' : null,
                'date_compare_start' => isset($date_compare_1) ? $date_compare_1 : null,
                'date_compare_finish' => isset($date_compare_2) ? $date_compare_2 : null,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $theme = Theme::find($id);
        $circle = Circle::find($theme->circle_id);
        return view('theme.edit', ['theme' => $theme, 'circle' => $circle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules, $this->messages);
        $theme = Theme::find($id);
        $theme->theme_name = $request->theme_name;
        $theme->value_property = $request->value_property;
        $theme->value_objective = $request->value_objective;
        $theme->date_start = $request->date_start;
        $theme->date_expected_completion = $request->date_expected_completion;
        $theme->date_actual_completion = $request->date_actual_completion;
        $theme->note = $request->note;
        $theme->updated_by = Auth::id();
        $theme->save();
        return redirect()->route('theme.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete activity_others with theme_id
        $activity_others = DB::table('activity_others')->where('theme_id', $id)->get();
        foreach ($activity_others as $activity_others_item)
        {
            $activity_other = ActivityOther::find($activity_others_item->id);
            $activity_other->delete();                    
        }
        //delete promotion_themes with theme_id
        $promotion_themes = DB::table('promotion_themes')->where('theme_id', $id)->get();
        foreach ($promotion_themes as $promotion_themes_item)
        {
            $promotion_theme = PromotionTheme::find($promotion_themes_item->id);
            $promotion_theme->delete();                    
        }
        $theme = Theme::find($id);
        $theme->delete();
        return redirect()->route('theme.index');
    }

    private static function buildCircle() {
        $user = DB::table('users')->select('id as userID', 'name');
        $member = DB::table('members')->selectRaw('circle_id, COUNT(id) as numMem, IF(is_leader = 2, name, "") as leader')
            ->leftJoinSub($user, 'user', function ($join) {
                $join->on('members.user_id', '=', 'user.userID');
            })->groupBy('circle_id');
        $place = DB::table('places')->select('id as placeID', 'place_name', 'department_id');
        $dep = DB::table('departments')->select('id as depID', 'department_name');
        return DB::table('circles')
            ->leftJoinSub($member, 'member', function ($join) {
                $join->on('circles.id', '=', 'member.circle_id');
            })->leftJoinSub($place, 'place', function ($join) {
                $join->on('circles.place_id', '=', 'place.placeID');
            })->leftJoinSub($dep, 'dep', function ($join) {
                $join->on('department_id', '=', 'depID');
            })->where('use_classification', UseClassificationEnum::USES)
            ->selectRaw('id, circle_name, circle_code, department_name, place_name, IF(numMem is null, 0, numMem) as numMem, IF(leader is null OR leader = "", "なし", leader) as leader');
    }
    public function report($id) {
        $theme = Theme::find($id);
        $current_circle = session('circle.id');
        $circle1 = $this::buildCircle()->where('id', $current_circle)->first();
        $circle_other = $this::buildCircle()->where('id', '<>', $current_circle)->get();

        $current_circle_member = DB::table('members')->where('circle_id', $current_circle)->pluck('user_id');
        $category = DB::table('categories')
            ->where('use_classification', UseClassificationEnum::USES)
            ->select('id as catID', 'category_name');
        $thread = DB::table('threads')
            ->joinSub($category, 'category', function($join) {
                $join->on('category_id', '=', 'category.catID');
            })->whereIn('created_by', $current_circle_member)
            ->where('use_classification', UseClassificationEnum::USES)
            ->where('circle_id', $current_circle)
            ->orderBy('created_at', 'desc')
            ->select('id', 'category_name', 'thread_name', 'created_by', 'created_at')
            ->get();

        return view('theme.report',
            [
                'theme' => $theme,
                'circle1' => $circle1,
                'circle_other' => $circle_other,
                'thread' => $thread
            ]
        );
    }
}
