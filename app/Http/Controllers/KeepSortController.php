<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeepSortController extends Controller
{
    public function postSortKeys(Request $request) {
        if($request->master == "user") {
            session()->put('userKeys', $request->sort_keys);
        } elseif ($request->master == "department") {
            session()->put('departmentKeys', $request->sort_keys);
        } elseif ($request->master == "place") {
            session()->put('placeKeys', $request->sort_keys);
        } else {
            session()->put('circleKeys', $request->sort_keys);
        }
    }
}