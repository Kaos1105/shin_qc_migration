<?php

namespace App\Http\Controllers;

use App\Models\PlanByYear;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanByYearController extends Controller
{
    private $rules = array(
        'prioritize_1'=> 'required|max:100',
        'prioritize_2'=> 'nullable|max:100',
        'prioritize_3'=> 'nullable|max:100',
        'prioritize_4'=> 'nullable|max:100',
        'prioritize_5'=> 'nullable|max:100',
        'prioritize_6'=> 'nullable|max:100',
        'prioritize_7'=> 'nullable|max:100',
        'prioritize_8'=> 'nullable|max:100',
        'prioritize_9'=> 'nullable|max:100',
        'prioritize_10'=> 'nullable|max:100',
        'meeting_times'=> 'required|numeric',
        'meeting_hour'=> 'required|numeric',
        'case_number_complete'=> 'required|numeric',
        'case_number_improve'=> 'required|numeric',
        'classes_organizing_objective'=> 'required|numeric',
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'prioritize_1.required'=> StaticConfig::$Required,
            'prioritize_1.max'=> StaticConfig::$Max_Length_100,
            'prioritize_2.max'=> StaticConfig::$Max_Length_100,
            'prioritize_3.max'=> StaticConfig::$Max_Length_100,
            'prioritize_4.max'=> StaticConfig::$Max_Length_100,
            'prioritize_5.max'=> StaticConfig::$Max_Length_100,
            'prioritize_6.max'=> StaticConfig::$Max_Length_100,
            'prioritize_7.max'=> StaticConfig::$Max_Length_100,
            'prioritize_8.max'=> StaticConfig::$Max_Length_100,
            'prioritize_9.max'=> StaticConfig::$Max_Length_100,
            'prioritize_10.max'=> StaticConfig::$Max_Length_100,
            'meeting_times.required'=> StaticConfig::$Required,
            'meeting_times.numeric'=> StaticConfig::$Number,
            'meeting_hour.required'=> StaticConfig::$Required,
            'meeting_hour.numeric'=> StaticConfig::$Number,
            'case_number_complete.required'=> StaticConfig::$Required,
            'case_number_complete.numeric'=> StaticConfig::$Number,
            'case_number_improve.required'=> StaticConfig::$Required,
            'case_number_improve.numeric'=> StaticConfig::$Number,
            'classes_organizing_objective.required'=> StaticConfig::$Required,
            'classes_organizing_objective.numeric'=> StaticConfig::$Number,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_year = date("Y");
        if(isset($_GET['year'])){
            $current_year = $_GET['year'];
        }
        $planbyyear = DB::table('plan_by_years')->where('year', $current_year)->first();
        if(!$planbyyear){
            $planbyyear = new PlanByYear;
            $planbyyear->year = $current_year;
        }
        else{
            $list_plan_by_month = DB::table('plan_by_months')
                ->where('plan_by_year_id',$planbyyear->id)
                ->orderBy('execution_order_no', 'asc')
                ->orderBy('display_order', 'asc')
                ->get();
        }
        $first_year = DB::table('plan_by_years')->orderby('year', 'ASC')->first();
        $last_year = DB::table('plan_by_years')->orderby('year', 'DESC')->first();
        return view('planbyyear.view', [
            'planbyyear' => $planbyyear,
            'list_plan_by_month' => isset($list_plan_by_month)? $list_plan_by_month : null,
            'first_year' => isset($first_year)? $first_year->year : $current_year,
            'last_year' => isset($last_year)? $last_year->year : $current_year
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($year)
    {
        return view('planbyyear.add', ['year' => $year]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        $planbyyear = new PlanByYear();
        $planbyyear->year = $request->year;
        $planbyyear->vision = $request->vision;
        $planbyyear->target = $request->target;
        $planbyyear->motto = $request->motto;
        $planbyyear->prioritize_1 = $request->prioritize_1;
        $planbyyear->prioritize_2 = $request->prioritize_2;
        $planbyyear->prioritize_3 = $request->prioritize_3;
        $planbyyear->prioritize_4 = $request->prioritize_4;
        $planbyyear->prioritize_5 = $request->prioritize_5;
        $planbyyear->prioritize_6 = $request->prioritize_6;
        $planbyyear->prioritize_7 = $request->prioritize_7;
        $planbyyear->prioritize_8 = $request->prioritize_8;
        $planbyyear->prioritize_9 = $request->prioritize_9;
        $planbyyear->prioritize_10 = $request->prioritize_10;
        $planbyyear->meeting_times = $request->meeting_times;
        $planbyyear->meeting_hour = $request->meeting_hour;
        $planbyyear->case_number_complete = $request->case_number_complete;
        $planbyyear->case_number_improve = $request->case_number_improve;
        $planbyyear->classes_organizing_objective = $request->classes_organizing_objective;
        $planbyyear->created_by = Auth::id();
        $planbyyear->updated_by = Auth::id();
        $planbyyear->save();
        return redirect()->route('planbyyear.index', ['year' => $request->year]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $planbyyear = PlanByYear::find($id);
        return view('planbyyear.edit',['planbyyear' => $planbyyear ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules, $this->messages);
        $planbyyear = PlanByYear::find($id);
        $planbyyear->year = $request->year;
        $planbyyear->vision = $request->vision;
        $planbyyear->target = $request->target;
        $planbyyear->motto = $request->motto;
        $planbyyear->prioritize_1 = $request->prioritize_1;
        $planbyyear->prioritize_2 = $request->prioritize_2;
        $planbyyear->prioritize_3 = $request->prioritize_3;
        $planbyyear->prioritize_4 = $request->prioritize_4;
        $planbyyear->prioritize_5 = $request->prioritize_5;
        $planbyyear->prioritize_6 = $request->prioritize_6;
        $planbyyear->prioritize_7 = $request->prioritize_7;
        $planbyyear->prioritize_8 = $request->prioritize_8;
        $planbyyear->prioritize_9 = $request->prioritize_9;
        $planbyyear->prioritize_10 = $request->prioritize_10;
        $planbyyear->meeting_times = $request->meeting_times;
        $planbyyear->meeting_hour = $request->meeting_hour;
        $planbyyear->case_number_complete = $request->case_number_complete;
        $planbyyear->case_number_improve = $request->case_number_improve;
        $planbyyear->classes_organizing_objective = $request->classes_organizing_objective;
        $planbyyear->updated_by = Auth::id();
        $planbyyear->save();
        return redirect()->route('planbyyear.index', ['year' => $request->year]);
    }

    /**
     * history
     */
    public function show()
    {
        $sort = 'year';
        $sortType = 'asc';
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        $member = DB::table('members')->select('circle_id as cirID', 'user_id as memberID');

        $tb = DB::table('promotion_circles')
            ->leftJoinSub($member, 'member', function ($join) {
                $join->on('promotion_circles.circle_id', '=', 'member.cirID');
            })->select('circle_id', 'year AS year2', 'memberID');

        $planbyyear = DB::table('plan_by_years')
            ->leftJoinSub($tb, 'tb', function ($join) {
                $join->on('plan_by_years.year', '=', 'tb.year2');
            })->selectRaw('*, COUNT(DISTINCT circle_id) as numCircle, COUNT(DISTINCT memberID ) as totalMem')
            ->groupBy('year')
            ->orderBy($sort, $sortType)->paginate(20);

        return view('planbyyear.history',['paginate' => $planbyyear ]);
    }
}
