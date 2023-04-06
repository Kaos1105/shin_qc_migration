<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;

class NotificationController extends Controller
{
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
            $notification = DB::table('notifications')->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->orderBy($sort, $sortType)->paginate(20);
        }else{
            $notification = DB::table('notifications')->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->where('use_classification', $filter)->orderBy($sort, $sortType)->paginate(20);
        }
        return view('notification.list', ['paginate' => $notification]);
    }

    public function create()
    {
        $notification = new Notification();
        return view('notification.add', ['notification' => $notification]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'message' => 'required',
                "display_order" => "required|numeric|min:0|max:999999",
                "date_start" => 'nullable|date_format:"Y/m/d H:i"',
                "date_end" => 'nullable|date_format:"Y/m/d H:i"',
            ],
            [
                'message.required' => StaticConfig::$Required,
                'display_order.required'=> StaticConfig::$Required,
                'display_order.numeric' => StaticConfig::$Number,
                'display_order.min' => StaticConfig::$Min,
                'display_order.max' => StaticConfig::$Max,
                'date_start.date_format' => StaticConfig::$DateTime,
                'date_end.date_format' => StaticConfig::$DateTime
            ]
        );

        $notification = new Notification();
        $notification->notification_classify = $request->notification_classify;
        $notification->message = $request->message;

        $notification->use_classification = $request->use_classification;

        $notification->date_start = $request->date_start;
        $notification->date_end = $request->date_end;
        $notification->display_order = $request->display_order;
        $notification->created_by = Auth::id();
        $notification->updated_by = Auth::id();
        $notification->save();
        return redirect()->route('notification.show', $notification->id);
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
        $notification = DB::table('notifications')->leftJoinSub($user, 'user', function ($join) {
            $join->on('updated_by', '=', 'user.userID');
        })->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
          ->where('id', $id)
          ->first();
        return view('notification.view',
            [
                'notification' => $notification,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function copy($id)
    {
        $notification = Notification::find($id);
        return view('notification.add', ['notification' => $notification]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = Notification::find($id);
        $user_updated_by_name = '';
        $user_updated_by = User::find($notification->updated_by);
        if ($user_updated_by != null) {
            $user_updated_by_name = $user_updated_by->name;
        }
        return view('notification.edit', ['notification' => $notification, 'user_updated_by_name' => $user_updated_by_name]);
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

        $this->validate($request,
            [
                'message' => 'required',
                "display_order" => "required|numeric|min:0|max:999999",
                "date_start" => 'nullable|date_format:"Y/m/d H:i"',
                "date_end" => 'nullable|date_format:"Y/m/d H:i"',
            ],
            [
                'message.required' => StaticConfig::$Required,
                'display_order.required'=> StaticConfig::$Required,
                'display_order.numeric' => StaticConfig::$Number,
                'display_order.min' => StaticConfig::$Min,
                'display_order.max' => StaticConfig::$Max,
                'date_start.date_format' => StaticConfig::$DateTime,
                'date_end.date_format' => StaticConfig::$DateTime
            ]
        );

        $notification = Notification::find($id);
        $notification->notification_classify = $request->notification_classify;
        $notification->message = $request->message;

        $notification->use_classification = $request->use_classification;

        $notification->date_start = $request->date_start;
        $notification->date_end = $request->date_end;
        $notification->display_order = $request->display_order;

        $notification->updated_by = Auth::id();
        $notification->save();
        return redirect()->route('notification.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        return redirect()->route('notification.index');
    }
}
