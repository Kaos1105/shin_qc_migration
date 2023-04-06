<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityOther;
use App\Circle;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ActivityAttachment;
use App\Attachment;
use File;

class ActivityController extends Controller
{


    private $rules = array(
        'activity_category'         => 'required',
        "activity_title"            =>	"required",
        "date_intended"	            =>	"required|date",
        "time_intended"	            =>	"required|date_format:H:i",
        "date_execution"	        =>	"nullable|date",
        "time_start"	            =>	"nullable|date_format:H:i",
        "time_finish"	            =>	"nullable|date_format:H:i",
        "participant_number"	    =>	"nullable|numeric",
    );
    private $messages = array();
    public function __construct() {
        $this->middleware('auth');
        $this->messages = [
            'activity_category.required'    => StaticConfig::$Required,
            'activity_title.required'   => StaticConfig::$Required,
            'date_intended.required'    => StaticConfig::$Required,
            'date_intended.date'    => StaticConfig::$Date,
            'time_intended.required'    => StaticConfig::$Required,
            'time_intended.date_format'    => StaticConfig::$Time,
            'date_execution.date'   => StaticConfig::$Date,
            'time_start.date_format'   => StaticConfig::$Time_Start,
            'time_finish.date_format'   => StaticConfig::$Time_End,
            'participant_number.numeric'    => StaticConfig::$Number,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = 'date_intended';
        $sortType = 'desc';
        $year = date("Y");
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        if(isset($_GET['filter'])){
            $year = (int) $_GET['filter'];
        }
        $list_year = DB::table('activities')->selectRaw('YEAR(date_intended) as year')->distinct('year')->orderBy('year')->get();

        if($year == 0){
            $activity = DB::table('activities')->where('circle_id', session('circle.id'))->orderBy($sort, $sortType)->paginate(20);
        }else{
            $activity = DB::table('activities')->where('circle_id', session('circle.id'))->whereYear('date_intended', $year)->orderBy($sort, $sortType)->paginate(20);
        }
        return view('activity.list',['paginate' => $activity, 'list_year' => $list_year]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $theme_list = DB::table('themes')->where('circle_id', session('circle.id'))->whereRaw('((YEAR(date_start) <= ' .$currentYear. ' AND ' .$currentYear. ' <= YEAR(date_expected_completion)) OR '
                . '(date_actual_completion IS not null AND YEAR(date_start) <= ' .$currentYear. ' AND ' .$currentYear. ' <= YEAR(date_actual_completion)))')
            ->select('id', 'theme_name')->get();

        if(isset($_GET['activity_id'])){
            $activity = Activity::find($_GET['activity_id']);
            if(isset($activity)){
                $activity_other = DB::table('activity_others')->where('activity_id', $activity->id)->get();
                //get file content
                $ContentFiles = DB::table('activity_attachments')->where('activity_id', $_GET['activity_id'])->where('FileType', StaticConfig::$Type_File_Content)
                    ->join('attachments', 'attachments.id', '=', 'activity_attachments.attachment_id')
                    ->select('attachments.id', 'attachments.FileName', 'attachments.FilePath', 'attachments.FileNameOriginal')->get();
                //get file content
                $RequestToBossFiles = DB::table('activity_attachments')->where('activity_id', $_GET['activity_id'])->where('FileType', StaticConfig::$Type_File_RequestToBoss)
                    ->join('attachments', 'attachments.id', '=', 'activity_attachments.attachment_id')
                    ->select('attachments.id', 'attachments.FileName', 'attachments.FilePath')->get();
                return view('activity.add', ['circle' => session('circle'), 'theme_list' => $theme_list, 'activity_other' => $activity_other,'activity' => $activity,'ContentFiles' => isset($ContentFiles) ? $ContentFiles : null,
                'RequestToBossFiles' => isset($RequestToBossFiles) ? $RequestToBossFiles : null]);
            }
        }
        $content1 = '';
        $member_list = DB::table('members as m')->where('m.circle_id', session('circle.id'))
            ->join('users as u', 'u.id', '=', 'm.user_id')
            ->select('u.name')->get();
        if(count($member_list) > 0){
            $content1 = '';
            for($i = 0; $i < count($member_list); $i++){
                $content1 .= $member_list[$i]->name;
                if($i < count($member_list) -1){ $content1 .= '、'; }
            }
        }

        $location_pool = DB::table('activities')->where('circle_id', session('circle.id'))->whereNotNull('location')->select('location')->distinct()->get();
        return view('activity.add',
            [
                'circle' => session('circle'),
                'theme_list' => $theme_list,
                'content1' => $content1,
                'location_pool' => isset($location_pool) ? $location_pool : null,
                'ContentFiles' => []
            ]
        );
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
        $activity = new Activity();
        $activity->circle_id = $request->circle_id;
        $activity->activity_category = $request->activity_category;
        $activity->activity_title = $request->activity_title;
        $activity->date_intended = $request->date_intended;
        $activity->time_intended = $request->time_intended;
        $activity->date_execution = $request->date_execution;
        $activity->time_start = $request->time_start;
        $activity->time_finish = $request->time_finish;
        $activity->time_span = $request->time_span;
        $activity->participant_number = $request->participant_number;
        $activity->location = $request->location;
        $activity->content1 = $request->content1;
        $activity->content2 = $request->content2;
        $activity->content3 = $request->content3;
        $activity->content4 = $request->content4;
        $activity->content5 = $request->content5;
        $activity->created_by = Auth::id();
        $activity->updated_by = Auth::id();        
        $activity->save();
        $activity_lastest = Activity::latest()->first();

        if(isset($request->theme_id)){
            for($i = 0; $i < count($request->theme_id); $i++){
                if(!empty($request->contents[$i]) || !empty($request->time[$i])){
                    $activity_other = new ActivityOther();
                    $activity_other->activity_id = $activity_lastest->id;
                    $activity_other->theme_id = $request->theme_id[$i];
                    $activity_other->content = $request->contents[$i];
                    $activity_other->time = $request->time[$i];
                    $activity_other->created_by = Auth::id();
                    $activity_other->updated_by = Auth::id();
                    $activity_other->save();
                }
            }
        }
        //add  content file
        if(!empty($request->contentFileIds)){
            $arraycontentFileIds = explode(',', $request->contentFileIds);           
            for($i = 0; $i < count($arraycontentFileIds); $i++){
                $existContentFile = DB::table('activity_attachments')->where('activity_id',$activity_lastest->id )->where('attachment_id',$arraycontentFileIds[$i])
                        ->where('FileType', StaticConfig::$Type_File_Content)->first();
                if(empty($existContentFile))
                {
                    $activity_attachment = new ActivityAttachment();
                    $activity_attachment->activity_id = $activity_lastest->id;
                    $activity_attachment->attachment_id = $arraycontentFileIds[$i];
                    $activity_attachment->FileType = StaticConfig::$Type_File_Content;
                    $activity_attachment->save();
                }
            }
        }

        //add  request to boss file
        if(!empty($request->requestToBossFileIds)){
            $arrayRequestFileIds = explode(',', $request->requestToBossFileIds);           
            for($i = 0; $i < count($arrayRequestFileIds); $i++){
                $existRequestFile = DB::table('activity_attachments')->where('activity_id',$activity_lastest->id )->where('attachment_id',$arrayRequestFileIds[$i])
                        ->where('FileType', StaticConfig::$Type_File_RequestToBoss)->first();
                if(empty($existRequestFile))
                {
                    $activity_attachment = new ActivityAttachment();
                    $activity_attachment->activity_id = $activity_lastest->id;
                    $activity_attachment->attachment_id = $arrayRequestFileIds[$i];
                    $activity_attachment->FileType = StaticConfig::$Type_File_RequestToBoss;
                    $activity_attachment->save();
                }
            }
        }
        
        $currentYear = $request->filterYear;
        return redirect()->route('activity.show', [$activity_lastest->id,'year' => $currentYear]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        return view('activity.view',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = Activity::find($id);
        $activity_other = DB::table('activity_others')->where('activity_id', $id)
            ->join('themes', 'themes.id', '=', 'activity_others.theme_id')
            ->select('activity_others.id', 'activity_others.theme_id', 'activity_others.content', 'activity_others.time', 'themes.theme_name')->get();

        $currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $theme_list = DB::table('themes')->where('circle_id', session('circle.id'))
            ->whereRaw('((YEAR(date_start) <= ' .$currentYear. ' AND ' .$currentYear. ' <= YEAR(date_expected_completion)) OR '
                . '(date_actual_completion IS not null AND YEAR(date_start) <= ' .$currentYear. ' AND ' .$currentYear. ' <= YEAR(date_actual_completion))) AND id NOT IN (SELECT theme_id FROM activity_others WHERE activity_id = '.$id.') ')
            ->select('id', 'theme_name')->get();

        $circle = Circle::find($activity->circle_id);
        $location_pool = DB::table('activities')->where('circle_id', session('circle.id'))->whereNotNull('location')->select('location')->distinct()->get();
        //get file content
        $ContentFiles = DB::table('activity_attachments')->where('activity_id', $id)->where('FileType', StaticConfig::$Type_File_Content)
            ->join('attachments', 'attachments.id', '=', 'activity_attachments.attachment_id')
            ->select('attachments.id', 'attachments.FileName', 'attachments.FilePath', 'attachments.FileNameOriginal')->get();
        //get file content
        $RequestToBossFiles = DB::table('activity_attachments')->where('activity_id', $id)->where('FileType', StaticConfig::$Type_File_RequestToBoss)
            ->join('attachments', 'attachments.id', '=', 'activity_attachments.attachment_id')
            ->select('attachments.id', 'attachments.FileName', 'attachments.FilePath')->get();
        
        return view('activity.edit',
            [
                'activity' => $activity,
                'activity_other' => $activity_other,
                'circle' => $circle,
                'theme_list' => $theme_list,
                'callback'  => isset($_GET['callback'])? $_GET['callback'] : null,
                'location_pool' => isset($location_pool) ? $location_pool : null,
                'ContentFiles' => $ContentFiles,
                'RequestToBossFiles' => $RequestToBossFiles
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
        $activity = Activity::find($id);
        $activity->activity_category = $request->activity_category;
        $activity->activity_title = $request->activity_title;
        $activity->date_intended = $request->date_intended;
        $activity->time_intended = $request->time_intended;
        $activity->date_execution = $request->date_execution;
        $activity->time_start = $request->time_start;
        $activity->time_finish = $request->time_finish;
        $activity->time_span = $request->time_span;
        $activity->participant_number = $request->participant_number;
        $activity->location = $request->location;
        $activity->content1 = $request->content1;
        $activity->content2 = $request->content2;
        $activity->content3 = $request->content3;
        $activity->content4 = $request->content4;
        $activity->content5 = $request->content5;
        $activity->updated_by = Auth::id();
        $activity->save();

        if(isset($request->activity_other_id)){
            for($i = 0; $i < count($request->activity_other_id); $i++){
                if($request->activity_other_id[$i] != 0){
                    $activity_other = ActivityOther::find($request->activity_other_id[$i]);
                    if(!empty($request->contents[$i]) || !empty($request->time[$i])){
                        $activity_other->content = $request->contents[$i];
                        $activity_other->time = $request->time[$i];
                        $activity_other->updated_by = Auth::id();
                        $activity_other->save();
                    }
                    else{
                        $activity_other->delete();
                    }
                }
                else{
                    $activity_other = new ActivityOther();
                    if(!empty($request->contents[$i]) || !empty($request->time[$i])){
                        $activity_other->activity_id = $id;
                        $activity_other->theme_id = $request->theme_id[$i];
                        $activity_other->content = $request->contents[$i];
                        $activity_other->time = $request->time[$i];
                        $activity_other->created_by = Auth::id();
                        $activity_other->updated_by = Auth::id();
                        $activity_other->save();
                    }
                }
            }
        }
        //add or delete content file
        $existContentFiles = DB::table('activity_attachments')->where('activity_id',$id )->where('FileType', StaticConfig::$Type_File_Content)->get();        
        if(!empty($request->contentFileIds)){
            $arraycontentFileIds = explode(',', $request->contentFileIds);           
            for($i = 0; $i < count($arraycontentFileIds); $i++){
                $existContentFile = DB::table('activity_attachments')->where('activity_id',$id )->where('attachment_id',$arraycontentFileIds[$i])
                        ->where('FileType', StaticConfig::$Type_File_Content)->first();
                if(empty($existContentFile))
                {
                    $activity_attachment = new ActivityAttachment();
                    $activity_attachment->activity_id = $id;
                    $activity_attachment->attachment_id = $arraycontentFileIds[$i];
                    $activity_attachment->FileType = StaticConfig::$Type_File_Content;
                    $activity_attachment->save();
                }
            }
            foreach ($existContentFiles as $contentfile)
            {
                if(!in_array($contentfile->attachment_id,$arraycontentFileIds))
                {
                    $activity_attachmentfile = ActivityAttachment::find($contentfile->id);
                    $activity_attachmentfile->delete();
                    $FileAttachment = Attachment::find($contentfile->attachment_id);
                    $oldFile = base_path() . StaticConfig::$Upload_Path_ContentActivity . $FileAttachment->FileName;
                    if (File::exists($oldFile)) {
                        File::delete($oldFile);
                    }
                    $FileAttachment->delete();
                }
            }
        }
        else
        {
            foreach ($existContentFiles as $contentfile)
            {
                $activity_attachmentfile = ActivityAttachment::find($contentfile->id);
                $activity_attachmentfile->delete();
                $FileAttachment = Attachment::find($contentfile->attachment_id);
                $oldFile = base_path() . StaticConfig::$Upload_Path_ContentActivity . $FileAttachment->FileName;
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
                $FileAttachment->delete();
            }
        }
        //add or delete request to bos file
        $existRequestFiles = DB::table('activity_attachments')->where('activity_id',$id )->where('FileType', StaticConfig::$Type_File_RequestToBoss)->get();        
        if(!empty($request->requestToBossFileIds)){
            $arrayRequestFileIds = explode(',', $request->requestToBossFileIds);           
            for($i = 0; $i < count($arrayRequestFileIds); $i++){
                $existRequestFile = DB::table('activity_attachments')->where('activity_id',$id )->where('attachment_id',$arrayRequestFileIds[$i])
                        ->where('FileType', StaticConfig::$Type_File_RequestToBoss)->first();
                if(empty($existRequestFile))
                {
                    $activity_attachment = new ActivityAttachment();
                    $activity_attachment->activity_id = $id;
                    $activity_attachment->attachment_id = $arrayRequestFileIds[$i];
                    $activity_attachment->FileType = StaticConfig::$Type_File_RequestToBoss;
                    $activity_attachment->save();
                }
            }
            foreach ($existRequestFiles as $requestfile)
            {
                if(!in_array($requestfile->attachment_id,$arrayRequestFileIds))
                {
                    $activity_attachmentfile = ActivityAttachment::find($requestfile->id);
                    $activity_attachmentfile->delete();
                    $FileAttachment = Attachment::find($requestfile->attachment_id);
                    $oldFile = base_path() . StaticConfig::$Upload_Path_RequestToBossActivity . $FileAttachment->FileName;
                    if (File::exists($oldFile)) {
                        File::delete($oldFile);
                    }
                    $FileAttachment->delete();
                }
            }
        }
        else
        {
            foreach ($existRequestFiles as $requestfile)
            {
                $activity_attachmentfile = ActivityAttachment::find($requestfile->id);
                $activity_attachmentfile->delete();
                $FileAttachment = Attachment::find($requestfile->attachment_id);
                $oldFile = base_path() . StaticConfig::$Upload_Path_RequestToBossActivity . $FileAttachment->FileName;
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
                $FileAttachment->delete();
            }
        }
        if($request->callback != ""){
            return redirect($request->callback);
        }
        $currentYear = $request->filterYear;
        return redirect()->route('activity.show', [$id,'year' => $currentYear]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $activity_other_list_id = DB::table('activity_others')->where('activity_id', $id)->pluck('id');
        if($activity_other_list_id){
            foreach($activity_other_list_id as $activity_other_id){
                $activity_other = ActivityOther::find($activity_other_id);
                $activity_other->delete();
            }
        }        
        $activity_attachment = DB::table('activity_attachments')->where('activity_id', $id)->get();
        foreach ($activity_attachment as $contentfile)
        {
            $activity_attachmentfile = ActivityAttachment::find($contentfile->id);
            $activity_attachmentfile->delete();
            $FileAttachment = Attachment::find($contentfile->attachment_id);
            $oldFile = base_path() . StaticConfig::$Upload_Path_ContentActivity . $FileAttachment->FileName;
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
            $FileAttachment->delete();
        }
        
        $activity = Activity::find($id);
        $activity->delete();
        return redirect()->route('activity.index',['filter' => $request->filterYearDelete]);
    }

}
