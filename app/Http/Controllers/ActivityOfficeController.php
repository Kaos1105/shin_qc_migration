<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Circle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\StaticConfig;

class ActivityOfficeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList()
    {
        $year = date('Y');
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        }
        $circle_filter = "all";
        if (isset($_GET['circle'])) {
            $circle_filter = $_GET['circle'];
        }
        $sort = 'date_intended';
        $sortType = 'desc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        $list_year = DB::table('activities')->selectRaw('YEAR(date_intended) as year')->distinct('year')->orderBy('year')->get();
        $circle = DB::table('circles')->select('id as circleID', 'circle_name');
        $circle_list = DB::table('circles')->select('id as circleID', 'circle_name')->groupBy('circle_name')->get();
        $activity_core = DB::table('activities')->leftJoinSub($circle, 'circle', function ($join) {
            $join->on('circle_id', '=', 'circle.circleID');
        });

        if($year == "all"){
            if($circle_filter == "all") {
                $activity = $activity_core->orderBy($sort, $sortType)->paginate(20);
            } else {
                $activity = $activity_core->where('circle_id', $circle_filter)->orderBy($sort, $sortType)->paginate(20);
            }
        }else{
            if($circle_filter == "all") {
                $activity = $activity_core->whereYear('date_intended', $year)->orderBy($sort, $sortType)->paginate(20);
            } else {
                $activity = $activity_core->where('circle_id', $circle_filter)->whereYear('date_intended', $year)->orderBy($sort, $sortType)->paginate(20);
            }
        }
        return view('activity-office.list',
            [
                'paginate' => $activity,
                'list_year' => $list_year,
                'circle_list' => $circle_list,
                'year' => $year
            ]
        );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShow($id)
    {
        $activity = Activity::find($id);
        $activity_other = DB::table('activity_others')->where('activity_id', $id)
            ->join('themes', 'themes.id', '=', 'activity_others.theme_id')
            ->select('activity_others.theme_id', 'activity_others.content', 'activity_others.time', 'themes.theme_name')->get();


        $circle = Circle::find($activity->circle_id);
        //get file content
        $ContentFiles = DB::table('activity_attachments')->where('activity_id', $id)->where('FileType', StaticConfig::$Type_File_Content)
                        ->join('attachments', 'attachments.id', '=', 'activity_attachments.attachment_id')
                        ->select('attachments.id', 'attachments.FileName', 'attachments.FilePath', 'attachments.FileNameOriginal')->get();
        //get file content
        $RequestToBossFiles = DB::table('activity_attachments')->where('activity_id', $id)->where('FileType', StaticConfig::$Type_File_RequestToBoss)
                        ->join('attachments', 'attachments.id', '=', 'activity_attachments.attachment_id')
                        ->select('attachments.id', 'attachments.FileName', 'attachments.FilePath')->get();
        return view('activity-office.view',
            [
                'activity' => $activity,
                'activity_other' => $activity_other,
                'circle' => $circle,
                'callback'  => isset($_GET['callback'])? $_GET['callback'] : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null,
                'ContentFiles' => $ContentFiles,
                'RequestToBossFiles' => $RequestToBossFiles
            ]);
    }

    public function updateSecretariatEntry(Request $request)
    {
        $activity = Activity::find($request->activity_id);
        $activity->content5 = $request->secretariat_entry;
        $activity->updated_by = Auth::id();
        $activity->save();
    }
}
