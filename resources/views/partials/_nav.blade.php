<nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background: #2D2DB9; z-index: 10000">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <div class="row">
                <img id="img-logo" src="{{ asset('images/qcsystem_logo.png') }}" alt="logo">
                <span id="text-logo">{{config('app.name', 'ＱＣ活動支援システム')}}</span>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto nav-authentication">
                <!-- Authentication Links -->
                @guest
                @else
                    <?php
                    if (Auth::user()->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_OFFICER || Auth::user()->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_COMMITTEE
                        || Auth::user()->role_indicator == \App\Enums\RoleIndicatorEnum::EXECUTIVE_DIRECTOR || Auth::user()->role_indicator == \App\Enums\RoleIndicatorEnum::OFFICE_WORKER) {
                        $circle_list = DB::table('circles')->where('use_classification', \App\Enums\UseClassificationEnum::USES)->orderBy('display_order')
                            ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->get();
                    } else {
                        $circle_list = DB::table('members')->where('members.user_id', Auth::id())
                            ->join('circles', 'circles.id', '=', 'members.circle_id')
                            ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                            ->distinct('circles.id')
                            ->orderBy('circles.display_order')
                            ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->get();
                        $circle_by_circle_list = DB::table('circles')->where('user_id', Auth::id())
                            ->where('use_classification', \App\Enums\UseClassificationEnum::USES)
                            ->orderBy('display_order')
                            ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->get();
                        $circle_by_place_list = DB::table('circles')
                            ->join('places', 'places.id', '=', 'circles.place_id')
                            ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                            ->where('places.user_id', '=', Auth::id())
                            ->orderBy('circles.display_order')
                            ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->get();
                        $circle_by_department_list = DB::table('circles')
                            ->join('places', 'places.id', '=', 'circles.place_id')
                            ->join('departments', 'departments.id', '=', 'places.department_id')
                            ->where('circles.use_classification', \App\Enums\UseClassificationEnum::USES)
                            ->where(function ($query) {
                                $query->where('departments.sw_id', '=', Auth::id())
                                    ->orWhere('departments.bs_id', '=', Auth::id());
                            })
                            ->orderBy('circles.display_order')
                            ->select('circles.id', 'circles.circle_name', 'circles.circle_code', 'circles.display_order')->get();

                        if (count($circle_by_circle_list) > 0) {
                            if (count($circle_list) > 0) {
                                foreach ($circle_by_circle_list as $circle_by_circle_item) {
                                    $is_push_array = true;
                                    foreach ($circle_list as $item) {
                                        if ($item->id == $circle_by_circle_item->id) {
                                            $is_push_array = false;
                                            break;
                                        }
                                    }
                                    if ($is_push_array) {
                                        $circle_list->push($circle_by_circle_item);
                                    }
                                }
                            } else {
                                $circle_list = $circle_by_circle_list;
                            }
                        }
                        if (count($circle_by_place_list) > 0) {
                            if (count($circle_list) > 0) {
                                foreach ($circle_by_place_list as $circle_by_place_item) {
                                    $is_push_array = true;
                                    foreach ($circle_list as $item) {
                                        if ($item->id == $circle_by_place_item->id) {
                                            $is_push_array = false;
                                            break;
                                        }
                                    }
                                    if ($is_push_array) {
                                        $circle_list->push($circle_by_place_item);
                                    }
                                }
                            } else {
                                $circle_list = $circle_by_place_list;
                            }
                        }
                        if (count($circle_by_department_list) > 0) {
                            if (count($circle_list) > 0) {
                                foreach ($circle_by_department_list as $circle_by_department_item) {
                                    $is_push_array = true;
                                    foreach ($circle_list as $item) {
                                        if ($item->id == $circle_by_department_item->id) {
                                            $is_push_array = false;
                                            break;
                                        }
                                    }
                                    if ($is_push_array) {
                                        $circle_list->push($circle_by_department_item);
                                    }
                                }
                            } else {
                                $circle_list = $circle_by_department_list;
                            }
                        }
                        $circle_list = $circle_list->sortBy('display_order');
                    }
                    ?>
                    @if(count($circle_list) > 0)
                        @if(isset($allowCircle))
                            <li id="circle-select">
                                <select class="btn btn-light" name="circle_selected"
                                        onchange="update_session_value(this.value)"
                                        style="padding-left: 20px; padding-right: 20px;">
                                    @foreach($circle_list as $circle_list_item)
                                        <option value="{{ $circle_list_item->id }}"
                                                @if(session()->has('circle'))
                                                @if(session('circle.id') == $circle_list_item->id)
                                                selected
                                                @endif
                                                @endif>
                                            {{ $circle_list_item->circle_name }}サークル
                                        </option>
                                    @endforeach
                                </select>
                                <script>
                                    function update_session_value(value) {
                                        $.ajax({
                                            type: "GET",
                                            url: '{{ URL::to("/update-session?circle_selected=") }}' + value,
                                            success: function (data) {
                                                if (data == "1") {
                                                    location.reload();
                                                }
                                            }
                                        });
                                    }

                                    function set_session_first(value) {
                                        $.ajax({
                                            type: "GET",
                                            url: '{{ URL::to("/update-session?circle_selected=") }}' + value,
                                        });
                                    }

                                    @if(session()->has('circle') == false && count($circle_list) > 0)
                                    set_session_first({{$circle_list[0]->id}});
                                    @endif
                                </script>
                            </li>
                        @endif
                    @endif
                    <li><a class="btn btn-light" id="btn-logout" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('ログアウト') }} </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    <li><a class="btn btn-light" id="btn-setting"><span class="fa fa-cog"></span></a></li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
