<?php

namespace App\Http\Controllers;

use App\Enums\StaticConfig;
use App\PlanByMonth;
use App\PlanByYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanByMonthController extends Controller
{
    private $rules = array(
        'execution_order_no' => 'required',
        "contents" => "required",
        "display_order" => "required|numeric|min:0|max:999999",
    );
    private $messages = array();
    public function __construct()
    {
        $this->middleware('auth');
        $this->messages = [
            'execution_order_no.required' => StaticConfig::$Required,
            'contents.required' => StaticConfig::$Required,
            'display_order.required' => StaticConfig::$Required,
            'display_order.numeric' => StaticConfig::$Number,
            'display_order.min' => StaticConfig::$Min,
            'display_order.max' => StaticConfig::$Max,
        ];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($planbyyear_id)
    {
        $planbyyear = PlanByYear::find($planbyyear_id);
        return view('planbymonth.add', ['planbyyear' => $planbyyear]);
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
        $planbymonth = new PlanByMonth;
        $planbymonth->plan_by_year_id = $request->plan_by_year_id;
        $planbymonth->execution_order_no = $request->execution_order_no;
        $planbymonth->contents = $request->contents;
        $planbymonth->display_order = $request->display_order;
        $planbymonth->in_charge = $request->in_charge;
        $planbymonth->note = $request->note;
        $planbymonth->month_start = $request->month_start;
        $planbymonth->month_end = $request->month_end;
        $planbymonth->content_jan = $request->content_jan;
        $planbymonth->content_feb = $request->content_feb;
        $planbymonth->content_mar = $request->content_mar;
        $planbymonth->content_apr = $request->content_apr;
        $planbymonth->content_may = $request->content_may;
        $planbymonth->content_jun = $request->content_jun;
        $planbymonth->content_jul = $request->content_jul;
        $planbymonth->content_aug = $request->content_aug;
        $planbymonth->content_sep = $request->content_sep;
        $planbymonth->content_oct = $request->content_oct;
        $planbymonth->content_nov = $request->content_nov;
        $planbymonth->content_dec = $request->content_dec;
        $planbymonth->created_by = Auth::id();
        $planbymonth->updated_by = Auth::id();
        $planbymonth->save();
        $planbyyear = PlanByYear::find($request->plan_by_year_id);
        return redirect('planbyyear?year=' . $planbyyear->year);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $planbymonth = PlanByMonth::find($id);
        $planbyyear = DB::table('plan_by_years')->where('id', $planbymonth->plan_by_year_id)->first();
        return view('planbymonth.edit', ['planbymonth' => $planbymonth, 'planbyyear' => $planbyyear]);
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
        $planbymonth = PlanByMonth::find($id);
        $planbymonth->execution_order_no = $request->execution_order_no;
        $planbymonth->contents = $request->contents;
        $planbymonth->display_order = $request->display_order;
        $planbymonth->in_charge = $request->in_charge;
        $planbymonth->note = $request->note;
        $planbymonth->month_start = $request->month_start;
        $planbymonth->month_end = $request->month_end;
        $planbymonth->content_jan = $request->content_jan;
        $planbymonth->content_feb = $request->content_feb;
        $planbymonth->content_mar = $request->content_mar;
        $planbymonth->content_apr = $request->content_apr;
        $planbymonth->content_may = $request->content_may;
        $planbymonth->content_jun = $request->content_jun;
        $planbymonth->content_jul = $request->content_jul;
        $planbymonth->content_aug = $request->content_aug;
        $planbymonth->content_sep = $request->content_sep;
        $planbymonth->content_oct = $request->content_oct;
        $planbymonth->content_nov = $request->content_nov;
        $planbymonth->content_dec = $request->content_dec;
        $planbymonth->created_by = Auth::id();
        $planbymonth->save();
        $planbyyear = PlanByYear::find($request->plan_by_year_id);
        return redirect('planbyyear?year=' . $planbyyear->year);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $planbymonth = PlanByMonth::find($id);
        $planbymonth->delete();
        return redirect()->route('planbyyear.index');
    }
}
