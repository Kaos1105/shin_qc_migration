<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\PromotionTheme;
use App\Models\Theme;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $theme = DB::table('themes')->where('circle_id', session('circle.id'))->paginate(20);
        return view('theme.list',['paginate' => $theme]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($theme_id, $value)
    {
        $theme = Theme::find($theme_id);
        $circle = Circle::find($theme->circle_id);
        return view('promotion-theme.add', ['theme' => $theme, 'circle' => $circle, 'value' => $value]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'date_expected_start'=> 'required',
                "date_expected_completion"	=>	"required"
            ],
            [
                'date_expected_start.required'=> StaticConfig::$Required,
                'date_expected_completion.required'=> StaticConfig::$Required
            ]
        );
        $promotion_theme = new PromotionTheme();
        $promotion_theme->theme_id = $request->theme_id;
        $promotion_theme->progression_category = $request->progression_category;
        $promotion_theme->date_expected_start = $request->date_expected_start;
        $promotion_theme->date_expected_completion = $request->date_expected_completion;
        $promotion_theme->date_actual_start = $request->date_actual_start;
        $promotion_theme->date_actual_completion = $request->date_actual_completion;
        $promotion_theme->note = $request->note;
        $promotion_theme->created_by = Auth::id();
        $promotion_theme->updated_by = Auth::id();
        $promotion_theme->save();
        return redirect()->route('theme.show',$request->theme_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($theme_id)
    {
        $theme = Theme::find($theme_id);
        $circle = Circle::find($theme->circle_id);

        return view('theme.view',
            [
                'theme' => $theme,
                'circle' => $circle
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
        $promotion_theme = PromotionTheme::find($id);
        $theme = Theme::find($promotion_theme->theme_id);
        $circle = Circle::find($theme->circle_id);
        return view('promotion-theme.edit',['promotion_theme' => $promotion_theme, 'theme' => $theme, 'circle' => $circle]);
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
        $this->validate($request,
            [
                'date_expected_start'=> 'required',
                "date_expected_completion"	=>	"required"
            ],
            [
                'date_expected_start.required'=> StaticConfig::$Required,
                'date_expected_completion.required'=> StaticConfig::$Required
            ]
        );
        $promotion_theme = PromotionTheme::find($id);
        $promotion_theme->date_expected_start = $request->date_expected_start;
        $promotion_theme->date_expected_completion = $request->date_expected_completion;
        $promotion_theme->date_actual_start = $request->date_actual_start;
        $promotion_theme->date_actual_completion = $request->date_actual_completion;
        $promotion_theme->note = $request->note;
        $promotion_theme->updated_by = Auth::id();
        $promotion_theme->save();
        return redirect()->route('theme.show',$promotion_theme->theme_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promotion_theme = PromotionTheme::find($id);
        $theme_id = $promotion_theme->theme_id;
        $promotion_theme->delete();
        return redirect()->route('theme.show', $theme_id);
    }
}
