<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Enums\UseClassificationEnum;
use App\Models\EducationalMaterial;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Response;

class EducationalMaterialsCircleController extends Controller{
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

        $educational_material = DB::table('educational_materials')
            ->selectRaw('*, SUBSTRING(file, 12) as fileName')
            ->selectRaw('IF(now() < date_start OR now() > date_end OR date_start = null OR date_end = null, 0, 1) as now_post')
            ->where('use_classification', UseClassificationEnum::USES)
            ->whereRaw('(now() >= date_start and now() <= date_end and date_start is not null and date_end is not null)')
            ->orderBy($sort, $sortType)
            ->paginate(20);

        return view('educational-materials-circle.list',
            [
                'paginate' => $educational_material
            ]
        );
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
