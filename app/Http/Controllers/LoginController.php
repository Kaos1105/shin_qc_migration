<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Enums\AccessAuthority;
use App\Enums\Common;
use App\Enums\RoleIndicatorEnum;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Enums\StaticConfig;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin()
    {
        // Form View
        $notification = DB::table('notifications')
            ->where('use_classification', \App\Enums\UseClassificationEnum::USES)
            ->where('notification_classify', 1)
            ->whereRaw('(notifications.date_start < now() OR notifications.date_start is null) AND (notifications.date_end > now() OR notifications.date_end is null)')
            ->orderBy('display_order')
            ->select('message', 'date_start', 'date_end')
            ->get();
        return view(
            'login.login',
            [
                'notification' => $notification,
            ]
        );
    }
    public function doLogout()
    {
        Auth::logout();
        return Redirect::to('login.login');
    }
    public function doLogin(Request $request)
    {
        $rules = array(
            'login_id' => 'required',
            'password' => 'required'
        );
        $message = array(
            'login_id.required' => StaticConfig::$Required,
            'password.required' => StaticConfig::$Required,
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userdata = array(
            'login_id' => $request->login_id,
            'password' => $request->password,
        );
        // attempt to do the login
        if (Auth::attempt($userdata)) {
            if (Auth::user()->access_authority == AccessAuthority::USER) {
                if ($request->access_authority == AccessAuthority::ADMIN) {  //user cannot access office page
                    Auth::logout();
                    $validator->getMessageBag()->add('incorrect', 'ユーザ名またはパスワードが間違っています');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }
            if ($request->access_authority == AccessAuthority::USER) {
                session()->put('toppage', AccessAuthority::USER);

                if (
                    Auth::user()->role_indicator == RoleIndicatorEnum::PROMOTION_OFFICER || Auth::user()->role_indicator == RoleIndicatorEnum::PROMOTION_COMMITTEE
                    || Auth::user()->role_indicator == RoleIndicatorEnum::EXECUTIVE_DIRECTOR || Auth::user()->role_indicator == RoleIndicatorEnum::OFFICE_WORKER
                ) {
                    $circle_first = DB::table('circles')->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                        ->orderBy('display_order')
                        ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->first();
                } else {
                    $circle_first = DB::table('members')->where('members.user_id', Auth::id())
                        ->join('circles', 'circles.id', '=', 'members.circle_id')
                        ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                        ->orderBy('circles.display_order')
                        ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->first();
                    $circle_by_circle_first = DB::table('circles')->where('user_id', Auth::id())
                        ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                        ->orderBy('display_order')
                        ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->first();
                    $circle_by_place_first = DB::table('circles')
                        ->join('places', 'places.id', '=', 'circles.place_id')
                        ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                        ->where('places.user_id', '=', Auth::id())
                        ->orderBy('circles.display_order')
                        ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->first();
                    $circle_by_department_first = DB::table('circles')
                        ->join('places', 'places.id', '=', 'circles.place_id')
                        ->join('departments', 'departments.id', '=', 'places.department_id')
                        ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                        ->where(function ($query) {
                            $query->where('departments.sw_id', '=', Auth::id())
                                ->orWhere('departments.bs_id', '=', Auth::id());
                        })
                        ->orderBy('circles.display_order')
                        ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->first();


                    $circle_first = Common::get_circle_display_order_smallest($circle_first, $circle_by_circle_first);
                    $circle_first = Common::get_circle_display_order_smallest($circle_first, $circle_by_place_first);
                    $circle_first = Common::get_circle_display_order_smallest($circle_first, $circle_by_department_first);
                }


                if (isset($circle_first)) {
                    $circle_session = Circle::find($circle_first->id);
                    session()->put('circle', $circle_session);
                } else {
                    Auth::logout();
                    $validator->getMessageBag()->add('incorrect', 'どのサークルにも追加されていない');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            } else {
                session()->put('toppage', AccessAuthority::ADMIN);
            }
            return redirect('top-page');
        } else {
            $validator->getMessageBag()->add('incorrect', 'ユーザ名またはパスワードが間違っています');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
