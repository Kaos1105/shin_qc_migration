<?php

namespace App\Http\Controllers;

use App\Models\Homepage;
use App\Models\Library;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Response;
use Storage;

class HomepageController extends Controller
{
    private $rules = array(
        'title' => 'required|max:100',
        'url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        "display_order" => "required|numeric|min:0|max:999999",
        "date_start" => 'nullable|date_format:"Y/m/d H:i"',
        "date_end" => 'nullable|date_format:"Y/m/d H:i"',
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'title.required' => StaticConfig::$Required,
            'title.max'   => StaticConfig::$Max_Length_100,
            'url.required' => StaticConfig::$Required,
            'url.regex' =>  StaticConfig::$Url_Error,
            'display_order.required' => StaticConfig::$Required,
            'display_order.numeric' => StaticConfig::$Number,
            'display_order.min' => StaticConfig::$Min,
            'display_order.max' => StaticConfig::$Max,
            'date_start.date_format' => StaticConfig::$DateTime,
            'date_end.date_format' => StaticConfig::$DateTime
        ];
    }
    public function index()
    {
        $sort = 'display_order';
        $sortType = 'asc';
        $filter = 0;
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
            $homepage = DB::table('homepages')
                ->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->orderBy($sort, $sortType)->paginate(20);
        } else {
            $homepage = DB::table('homepages')->where('use_classification', $filter)
                ->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->orderBy($sort, $sortType)->paginate(20);
        }

        return view('homepage.list', ['paginate' => $homepage]);
    }

    public function create()
    {
        $homepage = new Library();
        $suggestion = DB::table('homepages')->whereNotNull('classification')->select('classification')->distinct()->get();
        return view('homepage.add',
            [
                'homepage' => $homepage,
                'suggestion' => $suggestion
            ]);
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

        $homepage = new Homepage();
        $homepage->title = $request->title;
        $homepage->classification = $request->classification;
        $homepage->url = $request->url;
        $homepage->use_classification = $request->use_classification;
        $homepage->date_start = $request->date_start;
        $homepage->date_end = $request->date_end;
        $homepage->note = $request->note;
        $homepage->display_order = $request->display_order;
        $homepage->created_by = Auth::id();
        $homepage->updated_by = Auth::id();
        $homepage->save();

        return redirect()->route('homepage.show', $homepage->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')->select('id as userID', 'name');
        $homepage = DB::table('homepages')->leftJoinSub($user, 'user', function ($join) {
            $join->on('updated_by', '=', 'user.userID');
        })->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
            ->where('id', $id)
            ->first();

        return view('homepage.view',
            [
                'homepage' => $homepage,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
            ]);
    }


    public function edit($id)
    {
        $homepage = Homepage::find($id);
        $user_updated_by = User::find($homepage->updated_by);
        $suggestion = DB::table('homepages')->select('classification')->distinct()->get();

        return view('homepage.edit',
            [
                'homepage' => $homepage,
                'user_updated_by_name' => isset($user_updated_by)? $user_updated_by->name : '',
                'suggestion' => $suggestion
            ]);
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
        $homepage = Homepage::find($id);
        $homepage->title = $request->title;
        $homepage->classification = $request->classification;
        $homepage->url = $request->url;
        $homepage->use_classification = $request->use_classification;
        $homepage->date_start = $request->date_start;
        $homepage->date_end = $request->date_end;
        $homepage->note = $request->note;
        $homepage->display_order = $request->display_order;
        $homepage->updated_by = Auth::id();
        $homepage->save();

        return redirect()->route('homepage.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $homepage = Homepage::find($id);
        $homepage->delete();
        return redirect()->route('homepage.index');
    }

}
