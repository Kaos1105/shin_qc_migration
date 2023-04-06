<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\Department;
use App\Enums\UseClassificationEnum;
use App\Models\Member;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\PromotionTheme;
use App\Models\PromotionCircle;
use App\Models\ActivityApproval;
use App\Models\Activity;
use App\Models\ActivityApprovalsStatistics;
use App\Models\CircleLevel;
use App\Models\ActivityOther;
use App\Models\Theme;

class CircleController extends Controller
{
    private $rules = array(
        'circle_code'=> 'required|unique:circles,circle_code|max:5',
        'circle_name'=> 'required',
        "place_id"	=>	"required",
        "user_id"	=>	"required",
        "date_register"	=>	"required|date",
        "display_order"	=>	"required|numeric|min:0|max:999999"
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'circle_code.required'=> StaticConfig::$Required,
            'circle_code.unique'=> StaticConfig::$Unique,
            'circle_code.max'=> StaticConfig::$Max_Length_5,
            'circle_name.required'=> StaticConfig::$Required,
            'place_id.required'=> StaticConfig::$Required,
            'user_id.required'=> StaticConfig::$Required,
            'date_register.required'=> StaticConfig::$Required,
            'date_register.date'=> StaticConfig::$Date,
            'display_order.required'=> StaticConfig::$Required,
            'display_order.numeric'=> StaticConfig::$Number,
            'display_order.min'=> StaticConfig::$Min,
            'display_order.max'=> StaticConfig::$Max,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = 'display_order';
        $sortType = 'asc';
        $filter = 2;
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }
        if(isset($_GET['filter'])){
            $filter = (int) $_GET['filter'];
        }
        $arr_where = array();
        if($filter != 0){
            $arr_where['c.use_classification'] = $filter;
        }
        $member = DB::table('members')->selectRaw('members.circle_id as circleID, COUNT(id) as numMember')->groupBy('members.circle_id');
        $circle = DB::table('circles as c')->where($arr_where)
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->join('places as p', 'p.id', '=', 'c.place_id')
            ->join('departments as d', 'd.id', '=', 'p.department_id')
            ->leftJoin('activities as ac', 'ac.circle_id', '=', 'c.id')
            ->groupBy('c.id')
            ->leftJoinSub($member, 'member', function ($join) {
                $join->on('c.id', '=', 'member.circleID');
            })
            ->select('c.id', 'c.circle_name', 'c.circle_code', 'c.date_register', 'c.place_id', 'c.user_id', 'c.display_order', 'd.id as department_id', 'd.department_name', 'u.name as user_name', 'u.position', 'p.place_name', 'numMember', DB::raw('MAX(ac.date_execution) as date_execution'))
            ->orderBy($sort, $sortType)->paginate(20);
        return view('circle.list',['paginate' => $circle]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dep = 'departments';
        $department_id = DB::table($dep)->where('use_classification', \App\Enums\UseClassificationEnum::USES)->select('id')->get()->toArray();
        $department_id_array = array_column($department_id, 'id');
        $department_name = DB::table($dep)->select('id as depID', 'department_name');
        $place = DB::table('places')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->whereIn('department_id', $department_id_array)
            ->leftJoinSub($department_name, 'dp', function ($join) {
                $join->on('department_id', '=', 'dp.depID');
            })->select('id', 'place_name', 'department_name')
            ->get();
        $user = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('circle.add', ['place' => $place, 'user' => $user]);
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
        $circle = new Circle();
        $circle->circle_name = $request->circle_name;
        $circle->place_id = $request->place_id;
        $circle->circle_code = $request->circle_code;
        $circle->user_id = $request->user_id;
        $circle->display_order = $request->display_order;
        $circle->date_register = $request->date_register;
        $circle->statistic_classification = $request->statistic_classification;
        $circle->use_classification = $request->use_classification;
        $circle->note = $request->note;
        $circle->created_by = Auth::id();
        $circle->updated_by = Auth::id();
        $circle->save();
        $circle_lastest = Circle::latest()->first();
        return redirect()->route('circle.show', $circle_lastest->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $circle = Circle::find($id);
        $place =  Place::find($circle->place_id);
        $department =  Department::find($place->department_id);
        $user_created_by = User::find($circle->created_by);
        $user_updated_by = User::find($circle->updated_by);
        $list_member = DB::table('members')->where('circle_id', $id)->orderBy('is_leader', 'desc')->orderBy('display_order', 'asc')->get();
        $activity_lastest = DB::table('activities')->where('circle_id', $id)->orderBy('date_execution', 'DESC')->first();
        return view('circle.view',
            [
                'circle' => $circle,
                'list_member' => $list_member,
                'place' => $place,
                'department' => $department,
                'user_created_by_name' => isset($user_created_by)? $user_created_by->name : '',
                'user_updated_by' => isset($user_updated_by)? $user_updated_by->name : '',
                'activity_lastest' => isset($activity_lastest)? $activity_lastest : null,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $circle = Circle::find($id);
        $user_created_by = User::find($circle->created_by);
        $user_updated_by = User::find($circle->updated_by);
        $dep = 'departments';
        $department_id = DB::table('departments')->where('use_classification', UseClassificationEnum::USES)->select('id')->get()->toArray();
        $department_id_array = array_column($department_id, 'id');
        $department_name = DB::table($dep)->select('id as depID', 'department_name');
        $place = DB::table('places')->where('use_classification', UseClassificationEnum::USES)->whereIn('department_id', $department_id_array)->orWhere('id', $circle->place_id)
            ->leftJoinSub($department_name, 'dp', function ($join) {
                $join->on('department_id', '=', 'dp.depID');
            })->select('id', 'place_name', 'department_name')
            ->get();

        $user = DB::table('users')->where('use_classification', UseClassificationEnum::USES)->orWhere('id', $circle->user_id)->select('id', 'position', 'name')->orderBy('display_order')->get();
        $activity_lastest = DB::table('activities')->where('circle_id', $id)->orderBy('date_execution', 'DESC')->first();
        return view('circle.edit',
            [
                'circle' => $circle,
                'user' =>$user,
                'user_created_by_name' => isset($user_created_by)? $user_created_by->name : '',
                'user_updated_by' => isset($user_updated_by)? $user_updated_by->name : '',
                'place' => $place,
                'activity_lastest' => $activity_lastest,
            ]);
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
        $this->validate($request,
            [
                'circle_code'=> 'required|unique:circles,circle_code,'.$id.'|max:5',
                'circle_name'=> 'required',
                "place_id"	=>	"required",
                "user_id"	=>	"required",
                "date_register"	=>	"required|date",
                "display_order"	=>	"required|numeric|min:0|max:999999"
            ], $this->messages
        );
        $circle = Circle::find($id);
        $circle->circle_name = $request->circle_name;
        $circle->place_id = $request->place_id;
        $circle->circle_code = $request->circle_code;
        $circle->user_id = $request->user_id;
        $circle->display_order = $request->display_order;
        $circle->date_register = $request->date_register;
        $circle->statistic_classification = $request->statistic_classification;
        $circle->use_classification = $request->use_classification;
        $circle->note = $request->note;
        $circle->created_by = Auth::id();
        $circle->updated_by = Auth::id();
        $circle->save();
        return redirect()->route('circle.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //delete promotion circle, activity_approvals, circle_levels
        $promotion_circle = DB::table('promotion_circles')->where('circle_id', $id)->get();
        if(count($promotion_circle) > 0){
            foreach ($promotion_circle as $promotion_circle_item){
                //delete activity_approvals with promotion_circles_id
                $activity_approvals = DB::table('activity_approvals')->where('promotion_circle_id', $promotion_circle_item->id)->get();
                foreach ($activity_approvals as $activity_approval_item)
                {
                    $activity_approval = ActivityApproval::find($activity_approval_item->id);
                    $activity_approval->delete();
                }
                //delete circle_levels with promotion_circles_id
                $circle_levels = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle_item->id)->get();
                foreach ($circle_levels as $circle_level_item)
                {
                    $circle_level = CircleLevel::find($circle_level_item->id);
                    $circle_level->delete();
                }
                //delete promotion circle
                $promotion_circle = PromotionCircle::find($promotion_circle_item->id);
                $promotion_circle->delete();
            }
        }
        //delete theme, activity_others, promotion_themes
        $themes = DB::table('themes')->where('circle_id', $id)->get();
        if(count($themes) > 0){
            foreach ($themes as $theme_item)
            {
                //delete activity_others with theme_id
                $activity_others = DB::table('activity_others')->where('theme_id', $theme_item->id)->get();
                foreach ($activity_others as $activity_others_item)
                {
                    $activity_other = ActivityOther::find($activity_others_item->id);
                    $activity_other->delete();
                }
                //delete promotion_themes with theme_id
                $promotion_themes = DB::table('promotion_themes')->where('theme_id', $theme_item->id)->get();
                foreach ($promotion_themes as $promotion_themes_item)
                {
                    $promotion_theme = PromotionTheme::find($promotion_themes_item->id);
                    $promotion_theme->delete();
                }
                //delete theme
                $theme = Theme::find($theme_item->id);
                $theme->delete();
            }
        }
        //check activity
        $activitys = DB::table('activities')->where('circle_id', $id)->get();
        if(count($activitys) > 0){
            foreach ($activitys as $activity_item){
                //delete activity_others with activity_id
                $activity_others = DB::table('activity_others')->where('activity_id', $activity_item->id)->get();
                foreach ($activity_others as $activity_others_item)
                {
                    $activity_other = ActivityOther::find($activity_others_item->id);
                    $activity_other->delete();
                }
                //delete activity
                $activity = Activity::find($activity_item->id);
                $activity->delete();
            }
        }

        //check member
        $member_list = DB::table('members')->where('circle_id', $id)->get();
        if(count($member_list) > 0){
            foreach ($member_list as $member_item){
                //delete circle_levels with member_id
                $circle_levels = DB::table('circle_levels')->where('promotion_circle_id', $member_item->id)->get();
                foreach ($circle_levels as $circle_level_item)
                {
                    $circle_level = CircleLevel::find($circle_level_item->id);
                    $circle_level->delete();
                }
                $member = Member::find($member_item->id);
                $member->delete();
            }
        }
        //check activity_approvals_statistics
        $activity_approvals_statistics = DB::table('activity_approvals_statistics')->where('circle_id', $id)->get();
        if(count($activity_approvals_statistics) > 0){
            foreach ($activity_approvals_statistics as $activity_approval_statistic_item){
                $activity_approval_statistic = ActivityApprovalsStatistics::find($activity_approval_statistic_item->id);
                $activity_approval_statistic->delete();
            }
        }
        $circle = Circle::find($id);
        $circle->delete();
        return redirect()->route('circle.index');
    }
}
