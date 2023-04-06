<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;
use File;
use Response;

class LibraryController extends Controller
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
            $library = DB::table('libraries')
                ->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->orderBy($sort, $sortType)->paginate(20);
        } else {
            $library = DB::table('libraries')
                ->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->where('use_classification', $filter)
                ->orderBy($sort, $sortType)->paginate(20);
        }

        return view('library.list', ['paginate' => $library]);
    }

    public function create()
    {
        $library = new Library();
        $suggestion = DB::table('libraries')->whereNotNull('library_type')->select('library_type')->distinct()->get();
        return view('library.add',
            [
                'library' => $library,
                'suggestion' => isset($suggestion) ? $suggestion : null
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
        $this->validate($request,
            [
                'title' => 'required|max:100',
                'file_upload' => 'required|file|max:10240',
                "display_order" => "required|numeric|min:0|max:999999",
                "date_start" => 'nullable|date_format:"Y/m/d H:i"',
                "date_end" => 'nullable|date_format:"Y/m/d H:i"',
            ],
            [
                'title.required' => StaticConfig::$Required,
                'title.max'   => StaticConfig::$Max_Length_100,
                'file_upload.required' => StaticConfig::$Required,
                'file_upload.max' => StaticConfig::$Library_Size,
                'display_order.required' => StaticConfig::$Required,
                'display_order.numeric' => StaticConfig::$Number,
                'display_order.min' => StaticConfig::$Min,
                'display_order.max' => StaticConfig::$Max,
                'date_start.date_format' => StaticConfig::$DateTime,
                'date_end.date_format' => StaticConfig::$DateTime
            ]
        );

        $library = new Library();
        $library->title = $request->title;
        $library->library_type = $request->library_type;

        if ($request->hasFile('file_upload')) {
            if (!Storage::exists(StaticConfig::$Path_Library)) {
                Storage::makeDirectory(StaticConfig::$Path_Library, 0775, true); //creates directory
            }
            if ($request->hasFile('file_upload')) {
                $stamp = now()->timestamp;
                $fileName = $stamp . '.' . $request->file('file_upload')->getClientOriginalName();
                $library->file = $fileName;

                $request->file('file_upload')->move(
                    base_path() . StaticConfig::$Path_Library, $fileName
                );
            }
        }

        $library->use_classification = $request->use_classification;
        $library->date_start = $request->date_start;
        $library->date_end = $request->date_end;
        $library->note = $request->note;
        $library->display_order = $request->display_order;
        $library->created_by = Auth::id();
        $library->updated_by = Auth::id();
        $library->save();

        return redirect()->route('library.show', $library->id);
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
        $library = DB::table('libraries')->leftJoinSub($user, 'user', function ($join) {
            $join->on('updated_by', '=', 'user.userID');
        })->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
            ->where('id', $id)
            ->first();

        $fileOrigin = $library->file;
        $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin)-stripos($fileOrigin, '.'));

        return view('library.view',
            [
                'library' => $library,
                'fileNameDisp' => $fileNameDisp,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
                ]);
    }


    public function edit($id)
    {
        $library = Library::find($id);
        $user_updated_by_name = '';
        $user_updated_by = User::find($library->updated_by);
        if ($user_updated_by != null) {
            $user_updated_by_name = $user_updated_by->name;
        }
        $fileOrigin = $library->file;
        $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin)-stripos($fileOrigin, '.'));

        $suggestion = DB::table('libraries')->select('library_type')->distinct()->get();

        return view('library.edit',
            [
                'library' => $library,
                'user_updated_by_name' => $user_updated_by_name,
                'fileNameDisp' => $fileNameDisp,
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
        $this->validate($request,
            [
                'title' => 'required|max:100',
                'file_upload' => 'nullable|file|max:10240',
                "display_order" => "required|numeric|min:0|max:999999",
                "date_start" => 'nullable|date_format:"Y/m/d H:i"',
                "date_end" => 'nullable|date_format:"Y/m/d H:i"',
            ],
            [
                'title.required' => StaticConfig::$Required,
                'title.max'   => StaticConfig::$Max_Length_100,
                'file_upload.max' => StaticConfig::$Library_Size,
                'display_order.required' => StaticConfig::$Required,
                'display_order.numeric' => StaticConfig::$Number,
                'display_order.min' => StaticConfig::$Min,
                'display_order.max' => StaticConfig::$Max,
                'date_start.date_format' => StaticConfig::$DateTime,
                'date_end.date_format' => StaticConfig::$DateTime
            ]
        );

        $library = Library::find($id);
        $library->library_type = $request->library_type;
        $library->title = $request->title;

        if ($request->hasFile('file_upload')) {
            if(isset($library->file)){
                $oldFile = base_path() . '/storage/app/public/uploaded-files/library-upload/'.$library->file;
                if(File::exists($oldFile)){
                    File::delete($oldFile);
                }
            }
            $stamp = now()->timestamp;
            $fileName = $stamp . '.' . $request->file('file_upload')->getClientOriginalName();
            $library->file = $fileName;
            $request->file('file_upload')->move(
                base_path() . '/storage/app/public/uploaded-files/library-upload', $fileName
            );
        }
        $library->use_classification = $request->use_classification;
        $library->date_start = $request->date_start;
        $library->date_end = $request->date_end;
        $library->note = $request->note;
        $library->display_order = $request->display_order;
        $library->updated_by = Auth::id();
        $library->save();

        return redirect()->route('library.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $library = Library::find($id);
        if(isset($library->file)){
            $oldFile = base_path() . '/storage/app/public/uploaded-files/library-upload/'.$library->file;
            if(File::exists($oldFile)){
                File::delete($oldFile);
            }
        }
        $library->delete();
        return redirect()->route('library.index');
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
