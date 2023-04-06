<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;
use App\Models\EducationalMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;
use File;
use Response;
/**
 * Description of EducationalMaterialsController
 *
 * @author NTQ
 */
class EducationalMaterialsController extends Controller {
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
            $educational_material = DB::table('educational_materials')
                ->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->orderBy($sort, $sortType)->paginate(20);
        } else {
            $educational_material = DB::table('educational_materials')
                ->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
                ->where('use_classification', $filter)
                ->orderBy($sort, $sortType)->paginate(20);
        }

        return view('educational-materials.list', ['paginate' => $educational_material]);
    }

    public function create()
    {
        $educational_material = new EducationalMaterial();
        $suggestion = DB::table('educational_materials')->whereNotNull('educational_materials_type')->select('educational_materials_type')->distinct()->get();
        return view('educational-materials.add',
            [
                'educational_material' => $educational_material,
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

        $educational_material = new EducationalMaterial();
        $educational_material->title = $request->title;
        $educational_material->educational_materials_type = $request->educational_materials_type;

        if ($request->hasFile('file_upload')) {
            if (!Storage::exists(StaticConfig::$Path_EducationalMaterials)) {
                Storage::makeDirectory(StaticConfig::$Path_EducationalMaterials, 0775, true); //creates directory
            }
            if ($request->hasFile('file_upload')) {
                $stamp = now()->timestamp;
                $fileName = $stamp . '.' . $request->file('file_upload')->getClientOriginalName();
                $educational_material->file = $fileName;

                $request->file('file_upload')->move(
                    base_path() . StaticConfig::$Path_EducationalMaterials, $fileName
                );
            }
        }

        $educational_material->use_classification = $request->use_classification;
        $educational_material->date_start = $request->date_start;
        $educational_material->date_end = $request->date_end;
        $educational_material->note = $request->note;
        $educational_material->display_order = $request->display_order;
        $educational_material->created_by = Auth::id();
        $educational_material->updated_by = Auth::id();
        $educational_material->save();

        return redirect()->route('educational-materials.show', $educational_material->id);
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
        $educational_material = DB::table('educational_materials')->leftJoinSub($user, 'user', function ($join) {
            $join->on('updated_by', '=', 'user.userID');
        })->selectRaw('*, IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
            ->where('id', $id)
            ->first();

        $fileOrigin = $educational_material->file;
        $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin)-stripos($fileOrigin, '.'));

        return view('educational-materials.view',
            [
                'educational_material' => $educational_material,
                'fileNameDisp' => $fileNameDisp,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
                ]);
    }


    public function edit($id)
    {
        $educational_material = EducationalMaterial::find($id);
        $user_updated_by_name = '';
        $user_updated_by = User::find($educational_material->updated_by);
        if ($user_updated_by != null) {
            $user_updated_by_name = $user_updated_by->name;
        }
        $fileOrigin = $educational_material->file;
        $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin)-stripos($fileOrigin, '.'));

        $suggestion = DB::table('educational_materials')->select('educational_materials_type')->distinct()->get();

        return view('educational-materials.edit',
            [
                'educational_material' => $educational_material,
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

        $educational_material = EducationalMaterial::find($id);
        $educational_material->educational_materials_type = $request->educational_materials_type;
        $educational_material->title = $request->title;

        if ($request->hasFile('file_upload')) {
            if(isset($educational_material->file)){
                $oldFile = base_path() . '/storage/app/public/uploaded-files/education-materials-upload/'.$educational_material->file;
                if(File::exists($oldFile)){
                    File::delete($oldFile);
                }
            }
            $stamp = now()->timestamp;
            $fileName = $stamp . '.' . $request->file('file_upload')->getClientOriginalName();
            $educational_material->file = $fileName;
            $request->file('file_upload')->move(
                base_path() . '/storage/app/public/uploaded-files/education-materials-upload', $fileName
            );
        }
        $educational_material->use_classification = $request->use_classification;
        $educational_material->date_start = $request->date_start;
        $educational_material->date_end = $request->date_end;
        $educational_material->note = $request->note;
        $educational_material->display_order = $request->display_order;
        $educational_material->updated_by = Auth::id();
        $educational_material->save();

        return redirect()->route('educational-materials.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educational_material = EducationalMaterial::find($id);
        if(isset($educational_material->file)){
            $oldFile = base_path() . '/storage/app/public/uploaded-files/education-materials-upload/'.$educational_material->file;
            if(File::exists($oldFile)){
                File::delete($oldFile);
            }
        }
        $educational_material->delete();
        return redirect()->route('educational-materials.index');
    }

    public function download($id) {
        $educational_material = EducationalMaterial::find($id);
        $fileOrigin = $educational_material->file;
        $filePath = 'storage/uploaded-files/education-materials-upload/'.$educational_material->file;
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
