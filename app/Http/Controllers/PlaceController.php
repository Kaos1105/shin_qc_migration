<?php

namespace App\Http\Controllers;

use App\Department;
use App\Place;
use App\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
class PlaceController extends Controller
{
    private $rules = array(
        "place_name"	=>	"required|max:100",
        'department_id'=> 'required',
        "user_id"	=>	"required",
        "display_order"	=>	"required|numeric|min:0|max:999999"
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'place_name.required'=> StaticConfig::$Required,
            'place_name.max'=> StaticConfig::$Max_Length_100,
            'department_id.required'=> StaticConfig::$Required,
            'user_id.required'=> StaticConfig::$Required,
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
            $arr_where['p.use_classification'] = $filter;
        }

        $circles = DB::table('circles')->selectRaw('circles.place_id as placeID, COUNT(id) as numCircle')->groupBy('circles.place_id');
        $place = DB::table('places as p')->where($arr_where)
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->join('departments as d', 'd.id', '=', 'p.department_id')
            ->leftJoinSub($circles, 'circle', function ($join) {
                $join->on('p.id', '=', 'circle.placeID');
            })->select('p.id', 'p.place_name', 'p.department_id', 'p.user_id', 'd.department_name', 'u.name as user_name', 'u.position', 'p.display_order', 'p.note', 'numCircle')
            ->orderBy($sort, $sortType)->paginate(20);

        return view('place.list',['paginate' => $place]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department = DB::table('departments')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->select('id', 'department_name')->get();
        $user = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('place.add', ['department' => $department, 'user' => $user]);
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
        $place = new Place;
        $place->place_name = $request->place_name;
        $place->department_id = $request->department_id;
        $place->user_id = $request->user_id;
        $place->display_order = $request->display_order;
        $place->statistic_classification = $request->statistic_classification;
        $place->use_classification = $request->use_classification;
        $place->note = $request->note;
        $place->created_by = Auth::id();
        $place->updated_by = Auth::id();
        $place->save();
        $place_lastest = Place::latest()->first();
        return redirect()->route('place.show', $place_lastest->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = Place::find($id);
        $department =  Department::find($place->department_id);
        $user =  User::find($place->user_id);
        $user_created_by = User::find($place->created_by);
        $user_updated_by = User::find($place->updated_by);
        return view('place.view',
            [
                'place' => $place,
                'department' => $department,
                'user' => $user,
                'user_created_by_name' => isset($user_created_by)? $user_created_by->name : '',
                'user_updated_by' => isset($user_updated_by)? $user_updated_by->name : '',
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $place = Place::find($id);
        $user_created_by = User::find($place->created_by);
        $user_updated_by = User::find($place->updated_by);

        $department = DB::table('departments')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->orWhere('id', $place->department_id)->select('id', 'department_name')->get();
        $user = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->orWhere('id', $place->user_id)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('place.edit',
            [
                'place' => $place,
                'department' => $department,
                'user' => $user,
                'user_created_by_name' => isset($user_created_by)? $user_created_by->name : '',
                'user_updated_by' => isset($user_updated_by)? $user_updated_by->name : '',
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
        $this->validate($request, $this->rules, $this->messages);
        $place = Place::find($id);
        $place->place_name = $request->place_name;
        $place->department_id = $request->department_id;
        $place->user_id = $request->user_id;
        $place->display_order = $request->display_order;
        $place->statistic_classification = $request->statistic_classification;
        $place->use_classification = $request->use_classification;
        $place->note = $request->note;
        $place->updated_by = Auth::id();
        $place->save();
        return redirect()->route('place.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $circle = DB::table('circles')->where('place_id', $id)->first();
        if($circle){
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('delete_using', StaticConfig::$Cannot_Delete_Place);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $place = Place::find($id);
        $place->delete();
        return redirect('place');
    }
}
