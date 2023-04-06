<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\Models\Attachment;
use Response;
use File;
/**
 * Description of UploadFileController
 *
 * @author NTQ
 */
class UploadFileController extends Controller {
    //put your code here
    public function updateContentActivity(Request $request)
    {
        if($request->hasFile('attachmentContent'))
        {
            if (!Storage::exists(StaticConfig::$Upload_Path_ContentActivity)) {
                Storage::makeDirectory(StaticConfig::$Upload_Path_ContentActivity, 0775, true); //creates directory
            }
            if ($request->hasFile('attachmentContent')) {
                $stamp = now()->timestamp;
                $fileName = $stamp . '.' . $request->file('attachmentContent')->getClientOriginalName();
                $request->file('attachmentContent')->move(base_path() . StaticConfig::$Upload_Path_ContentActivity, $fileName);

                $attachmentFile = new Attachment();
                $attachmentFile->FileName = $fileName;
                $attachmentFile->FileNameOriginal = $request->file('attachmentContent')->getClientOriginalName();
                $attachmentFile->FilePath =  StaticConfig::$Upload_Path_ContentActivity;
                $attachmentFile->save();
                 $activity_lastest = Attachment::latest()->first();
                 return $activity_lastest;
             }
        }

    }
    public function updateRequestToBossFileActivity(Request $request)
    {
        if($request->hasFile('attachmentRequestToBoss'))
        {
            if (!Storage::exists(StaticConfig::$Upload_Path_RequestToBossActivity)) {
                Storage::makeDirectory(StaticConfig::$Upload_Path_RequestToBossActivity, 0775, true); //creates directory
            }
            if ($request->hasFile('attachmentRequestToBoss')) {
                $stamp = now()->timestamp;
                $fileName = $stamp . '.' . $request->file('attachmentRequestToBoss')->getClientOriginalName();
                $request->file('attachmentRequestToBoss')->move(base_path() . StaticConfig::$Upload_Path_RequestToBossActivity, $fileName);

                $attachmentFile = new Attachment();
                $attachmentFile->FileName = $fileName;
                $attachmentFile->FilePath =  StaticConfig::$Upload_Path_RequestToBossActivity;
                $attachmentFile->save();
                 $activity_lastest = Attachment::latest()->first();
                 return $activity_lastest;
             }
        }

    }
    public function downloadFile($id)
    {
        $FileAttachment = Attachment::find($id);
        $pathToFile=base_path().$FileAttachment->FilePath.$FileAttachment->FileName;
        return response()->download($pathToFile,$FileAttachment->FileNameOriginal);
    }
    public function deleteAttachment($attachmentid)
    {
        $FileAttachment = Attachment::find($attachmentid);
        $oldFile = base_path() . StaticConfig::$Upload_Path_ContentActivity . $FileAttachment->FileName;
        if (File::exists($oldFile)) {
            File::delete($oldFile);
        }
        $FileAttachment->delete();
    }
}
