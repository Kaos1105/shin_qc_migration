<?php

namespace App\Http\Controllers;

use App\Enums\UseClassificationEnum;
use App\Library;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Response;

class LibraryCircleController extends Controller
{
    public function index()
    {
        $sort = 'display_order';
        $sortType = 'asc';
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        $library = DB::table('libraries')
            ->selectRaw('*, SUBSTRING(file, 12) as fileName')
            ->selectRaw('IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
            ->where('use_classification', UseClassificationEnum::USES)
            ->whereRaw('(now() >= date_start and now() <= date_end and date_start is not null and date_end is not null)')
            ->orderBy($sort, $sortType)
            ->paginate(20);

        return view('library-circle.list',
            [
                'paginate' => $library
            ]
        );
    }

    public function download($id) {
        $library = Library::find($id);
        $fileOrigin = $library->file;
        $filePath = 'storage/uploaded-files/library-upload/'.$library->file;
        $downloadName = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin)-stripos($fileOrigin, '.'));
        if ( file_exists( $filePath ) ) {
            return response()->download( $filePath, $downloadName );
        } else {
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('download_error', StaticConfig::$File_Not_Exist);
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}