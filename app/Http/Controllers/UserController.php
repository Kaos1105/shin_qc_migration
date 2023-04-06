<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;

class UserController extends Controller
{

    private $rules = array(
        'user_code'=> "required|unique:users,user_code|max:10",
        "name"	=>	"required|max:100",
        "email"	=>	"nullable|email|max:100",
        "position"	=>	"max:100",
        "phone"	=>	"nullable|max:20",
        "login_id"	=>	"required|unique:users,login_id",
        "password"	=>	"required|max:40",
        "display_order"	=>	"required|numeric|min:0|max:999999"
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'user_code.required'=> StaticConfig::$Required,
            'user_code.unique'=> StaticConfig::$Unique,
            'user_code.max'=> StaticConfig::$Max_Length_10,
            'name.required'=> StaticConfig::$Required,
            'name.max'=> StaticConfig::$Max_Length_100,
            'email.email'=> StaticConfig::$Email,
            'email.max'=> StaticConfig::$Max_Length_100,
            'position.max'=> StaticConfig::$Max_Length_100,
            'phone.max'=> StaticConfig::$Max_Length_20,
            'login_id.required'=> StaticConfig::$Required,
            'login_id.unique'=> StaticConfig::$Unique,
            'password.required'=> StaticConfig::$Required,
            'password.max'=> StaticConfig::$Max_Length_40,
            'display_order.required'=> StaticConfig::$Required,
            'display_order.numeric'=> StaticConfig::$Number,
            'display_order.min'=> StaticConfig::$Min,
            'display_order.max'=> StaticConfig::$Max
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
        if($filter == 0){
            $users = DB::table('users')->orderBy($sort, $sortType)->paginate(20);
        }else{
            $users = DB::table('users')->where('use_classification', $filter)->orderBy($sort, $sortType)->paginate(20);
        }

        return view('user.list',['paginate' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        $user = new User;
        $user->user_code = $request->user_code;
        $user->name = $request->name;
        $user->role_indicator = $request->role_indicator;
        $user->position = $request->position;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->login_id = $request->login_id;
        $user->password = Hash::make($request->password);
        $user->password_encrypt = base64_encode($request->password);
        $user->access_authority = $request->access_authority;
        $user->display_order = $request->display_order;
        $user->statistic_classification = $request->statistic_classification;
        $user->use_classification = $request->use_classification;
        $user->note = $request->note;
        $user->created_by = Auth::id();
        $user->updated_by = Auth::id();
        $user->save();
        $user_lastest = User::latest()->first();
        return redirect()->route('user.show', $user_lastest->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user_created_by = User::find($user->created_by);
        $user_updated_by = User::find($user->updated_by);
        return view('user.view',[
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
        $user = User::find($id);
        $user_created_by = User::find($user->created_by);
        $user_updated_by = User::find($user->updated_by);
        return view('user.edit',[
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
        $this->validate($request,
            [
                'user_code'=> "required|max:10|unique:users,user_code,".$id."|max:10",
                "name"	=>	"required|max:100",
                "email"	=>	"nullable|email|max:100",
                "position"	=>	"nullable|max:100",
                "phone"	=>	"nullable|max:20",
                "login_id"	=>	"required|unique:users,login_id,". $id,
                "password"	=>	"required|max:40",
                "display_order"	=>	"required|numeric|min:0|max:999999"
            ], $this->messages
        );
        $user = User::find($id);
        $user->user_code = $request->user_code;
        $user->name = $request->name;
        $user->role_indicator = $request->role_indicator;
        $user->position = $request->position;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->login_id = $request->login_id;
        if($user->password != $request->password){
            $user->password = Hash::make($request->password);
            $user->password_encrypt = base64_encode($request->password);
        }
        $user->access_authority = $request->access_authority;
        $user->display_order = $request->display_order;
        $user->statistic_classification = $request->statistic_classification;
        $user->use_classification = $request->use_classification;
        $user->note = $request->note;
        $user->updated_by = Auth::id();
        $user->save();
        return redirect()->route('user.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department_exists = DB::table('departments')->where('bs_id', $id)->orWhere('sw_id', $id)->exists();
        if($department_exists){
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('cannotDelete', StaticConfig::$Cannot_Delete_User);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $place_exists = DB::table('places')->where('user_id', $id)->exists();
        if($place_exists){
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('cannotDelete', StaticConfig::$Cannot_Delete_User);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $circle_exists = DB::table('circles')->where('user_id', $id)->exists();
        if($circle_exists){
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('cannotDelete', StaticConfig::$Cannot_Delete_User);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $member_exists = DB::table('members')->where('user_id', $id)->exists();
        if($member_exists) {
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('cannotDelete', StaticConfig::$Cannot_Delete_User);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $activity_approval_exists = DB::table('activity_approvals')->where('user_approved', $id)
            ->orWhere('user_jan', $id)
            ->orWhere('user_feb', $id)
            ->orWhere('user_mar', $id)
            ->orWhere('user_apr', $id)
            ->orWhere('user_may', $id)
            ->orWhere('user_jun', $id)
            ->orWhere('user_jul', $id)
            ->orWhere('user_aug', $id)
            ->orWhere('user_sep', $id)
            ->orWhere('user_oct', $id)
            ->orWhere('user_nov', $id)
            ->orWhere('user_dec', $id)
            ->exists();

        if($activity_approval_exists) {
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('cannotDelete', StaticConfig::$Cannot_Delete_User);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index');
    }
}
