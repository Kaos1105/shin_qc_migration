<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class DepartmentController extends Controller
{
    private $rules = array(
        'department_name'=> 'required|max:100',
        "bs_id"	=>	"required",
        "sw_id"	=>	"required",
        "display_order"    =>    "required|numeric|min:0|max:999999"
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'department_name.required'  => StaticConfig::$Required,
            'department_name.max'   => StaticConfig::$Max_Length_100,
            'bs_id.required'    => StaticConfig::$Required,
            'sw_id.required'    => StaticConfig::$Required,
            'display_order.required'    => StaticConfig::$Required,
            'display_order.numeric' => StaticConfig::$Number,
            'display_order.min' => StaticConfig::$Min,
            'display_order.max' => StaticConfig::$Max,
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
            $arr_where['d.use_classification'] = $filter;
        }

        $places = DB::table('places')->selectRaw('department_id as dpID, COUNT(id) as numPlace')->groupBy('department_id');
        $department = \DB::table('departments as d')->where($arr_where)
            ->join('users as bs', 'bs.id', '=', 'd.bs_id')
            ->join('users as sw', 'sw.id', '=', 'd.sw_id')
            ->leftJoinSub($places, 'place', function ($join) {
                $join->on('d.id', '=', 'place.dpID');
            })->select('d.id', 'd.department_name', 'd.bs_id', 'bs.name as bs_name', 'bs.position as bs_position', 'd.sw_id', 'sw.name as sw_name', 'sw.position as sw_position', 'd.display_order', 'd.note', 'numPlace')
            ->orderBy($sort, $sortType)->paginate(20);

        return view('department.list',['paginate' => $department]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department_manager = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->select('id', 'position', 'name')->orderBy('display_order')->get();
        $department_caretaker = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('department.add', ['department_manager' => $department_manager, 'department_caretaker' => $department_caretaker]);
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
        $department = new Department;
        $department->department_name = $request->department_name;
        $department->bs_id = $request->bs_id;
        $department->sw_id = $request->sw_id;
        $department->display_order = $request->display_order;
        $department->statistic_classification = $request->statistic_classification;
        $department->use_classification = $request->use_classification;
        $department->note = $request->note;
        $department->created_by = Auth::id();
        $department->updated_by = Auth::id();
        $department->save();
        $department_lastest = Department::latest()->first();
        return redirect()->route('department.show', $department_lastest->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::find($id);
        $user_manager =  User::find($department->bs_id);
        $user_caretaker =  User::find($department->sw_id);
        $user_created_by = User::find($department->created_by);
        $user_updated_by = User::find($department->updated_by);
        return view('department.view',
            [
                'department' => $department,
                'user_manager' => $user_manager,
                'user_caretaker' => $user_caretaker,
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
        $department = Department::find($id);
        $user_created_by = User::find($department->created_by);
        $user_updated_by = User::find($department->updated_by);
        $department_manager = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->orWhere('id', $department->bs_id)->select('id', 'position', 'name')->orderBy('display_order')->get();
        $department_caretaker = DB::table('users')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->orWhere('id', $department->sw_id)->select('id', 'position', 'name')->orderBy('display_order')->get();
        return view('department.edit',
            [
                'department' => $department,
                'department_manager' => $department_manager,
                'department_caretaker' => $department_caretaker,
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
        $department = Department::find($id);
        $department->department_name = $request->department_name;
        $department->bs_id = $request->bs_id;
        $department->sw_id = $request->sw_id;
        $department->display_order = $request->display_order;
        $department->statistic_classification = $request->statistic_classification;
        $department->use_classification = $request->use_classification;
        $department->note = $request->note;
        $department->updated_by = Auth::id();
        $department->save();
        return redirect()->route('department.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = DB::table('places')->where('department_id', $id)->first();
        if($place){
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('delete_using', StaticConfig::$Cannot_Delete_Department);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $department = Department::find($id);
        $department->delete();
        return redirect()->route('department.index');
    }
}
