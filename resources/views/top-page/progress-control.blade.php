<div id="canvas-container" class="position-relative">
    <?php
    $current_year = (int)date('Y');
    $current_month = (int)date('m');
    $is_display_current_year = false;
    $is_display_last_year = false;
    $is_display_next_year = false;
    ?>
    <table class="table-progress" id="table-active-theme" style="table-layout: fixed">
        <thead>
        <tr>
            <th style="width: 5%">No</th>
            <th style="">テーマ</th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month > 2) {
                    $is_display_current_year = true;
                    echo '<span class="d-block year-on-top">' . $current_year . '</span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month - 2) . '</span>';
                } else {
                    $is_display_last_year = true;
                    $month_temp = 12;
                    if ($current_month == 1) {
                        $month_temp = 11;
                    }
                    echo '<span class="d-block year-on-top">' . ($current_year - 1) . '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month > 1) {
                    echo '<span class="d-block year-on-top">';
                    if (!$is_display_current_year) {
                        echo $current_year;
                        $is_display_current_year = true;
                    }
                    echo '</span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month - 1) . '</span>';
                } else {
                    $month_temp = 12;
                    echo '<span class="d-block year-on-top">';
                    if (!$is_display_last_year) {
                        echo $current_year - 1;
                        $is_display_last_year = true;
                    }
                    echo '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th class="theme-head-active" style="width: 12%; background-color:rgb(255, 204, 204)">
                    <span class="d-block year-on-top">
                    <?php
                        if (!$is_display_current_year) {
                            echo $current_year;
                            $is_display_current_year = true;
                        }
                        ?>
                    </span>
                <span class="month-current">{{ $current_month }}</span>
            </th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month < 12) {
                    echo '<span class="d-block year-on-top"></span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month + 1) . '</span>';
                } else {
                    $month_temp = 1;
                    $is_display_next_year = true;
                    echo '<span class="d-block year-on-top">' . ($current_year + 1) . '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month < 11) {
                    echo '<span class="d-block year-on-top"></span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month + 2) . '</span>';
                } else {
                    $month_temp = 1;
                    if ($current_month == 12) {
                        $month_temp = 2;
                    }
                    echo '<span class="d-block year-on-top">';
                    if (!$is_display_next_year) {
                        echo $current_year + 1;
                    }
                    echo '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th class="invisible special-arrow-border" style="width: 0; padding: 0 !important;"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $theme_stt = 1;
        $number_colum_first = 1;
        ?>
        @foreach($theme_active_list as $theme_active_list_item)
            <?php
            $date_start = new DateTime($theme_active_list_item->date_start);
            $date_expected_completion = new DateTime($theme_active_list_item->date_expected_completion);
            ?>
            <tr>
                <td>{{ $theme_stt }}</td>
                <td>{{ $theme_active_list_item->theme_name }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-right: 1px solid #333333"></td>
                <td class="invisible special-arrow-border" style="width:0; padding: 0 !important;"></td>
            </tr>

            <?php
            $isDraw = true;
            $has_start_point = true;
            $has_end_point = true;
            $start_year = (int)$date_start->format('Y');
            $start_month = (int)$date_start->format('m');
            $end_year = (int)$date_expected_completion->format('Y');
            $end_month = (int)$date_expected_completion->format('m');
            $plan_start_column = $number_colum_first + 1;
            $plan_end_column = 5 + $number_colum_first + 1;
            if ($start_year == $current_year) {
                if ($current_month - $start_month > 2) {
                    $has_start_point = false;
                } else if ($current_month - $start_month == 2) {
                    $plan_start_column = $number_colum_first + 1;
                } else if ($current_month - $start_month == 1) {
                    $plan_start_column = 1 + $number_colum_first + 1;
                } else if ($current_month - $start_month == 0) {
                    $plan_start_column = 2 + $number_colum_first + 1;
                } else if ($current_month - $start_month == -1) {
                    $plan_start_column = 3 + $number_colum_first + 1;
                } else if ($current_month - $start_month == -2) {
                    $plan_start_column = 4 + $number_colum_first + 1;
                } else {
                    $isDraw = false;
                }
            } elseif ($current_year - $start_year == 1) {
                if ($current_month < 3) {
                    if ($start_month > 10) {
                        if ($current_month == 1) {
                            if ($start_month == 11) {
                                $plan_start_column = $number_colum_first + 1;
                            } else {
                                $plan_start_column = 1 + $number_colum_first + 1;
                            }
                        } else { //$current_month == 2
                            if ($start_month == 12) {
                                $plan_start_column = $number_colum_first + 1;
                            } else { //$start_month == 11
                                $has_start_point = false;
                            }
                        }
                    } else {
                        $has_start_point = false;
                    }
                } else {
                    $has_start_point = false;
                }
            } elseif ($current_year - $start_year > 1) {
                $has_start_point = false;
            } elseif ($current_year - $start_year == -1) {
                if ($current_month > 10) {
                    if ($current_month == 11) {
                        if ($start_month == 1) {
                            $plan_start_column = 4 + $number_colum_first + 1;
                        } else {
                            $isDraw = false;
                        }
                    } else { //$current_month == 12
                        if ($start_month == 1) {
                            $plan_start_column = 3 + $number_colum_first + 1;
                        } elseif ($start_month == 2) {
                            $plan_start_column = 4 + $number_colum_first + 1;
                        } else { //$start_month > 2
                            $isDraw = false;
                        }
                    }
                } else {
                    $isDraw = false;
                }
            } elseif ($current_year - $start_year < -1) {
                $isDraw = false;
            }
            if ($isDraw) {
                if ($end_year == $current_year) {
                    if ($current_month - $end_month < -2) {
                        $has_end_point = false;
                    } else if ($current_month - $end_month == -2) {
                        $plan_end_column = 4 + $number_colum_first + 1;
                    } else if ($current_month - $end_month == -1) {
                        $plan_end_column = 3 + $number_colum_first + 1;
                    } else if ($current_month - $end_month == 0) {
                        $plan_end_column = 2 + $number_colum_first + 1;
                    } else if ($current_month - $end_month == 1) {
                        $plan_end_column = 1 + $number_colum_first + 1;
                    } else if ($current_month - $end_month == 2) {
                        $plan_end_column = 0 + $number_colum_first + 1;
                    } else {
                        $isDraw = false;
                    }
                } elseif ($current_year - $end_year == -1) {
                    if ($current_month > 10) {
                        if ($current_month == 11) {
                            if ($end_month == 1) {
                                $plan_end_column = 4 + $number_colum_first + 1;
                            } else { //$end_month > 1
                                $has_end_point = false;
                            }
                        } else { //$current_month == 12
                            if ($end_month == 1) {
                                $plan_end_column = 3 + $number_colum_first + 1;
                            } elseif ($end_month == 2) {
                                $plan_end_column = 4 + $number_colum_first + 1;
                            } else { //$end_month > 2
                                $has_end_point = false;
                            }
                        }
                    } else {
                        $has_end_point = false;
                    }
                } elseif ($current_year - $end_year < -1) {
                    $has_end_point = false;
                } elseif ($current_year - $end_year == 1) {
                    if ($current_month < 3) {
                        if ($current_month == 2) {
                            if ($end_month == 12) {
                                $plan_end_column = 0 + $number_colum_first + 1;
                            } else {
                                $isDraw = false;
                            }
                        } else { //$current_month == 1
                            if ($end_month == 12) {
                                $plan_end_column = 1 + $number_colum_first + 1;
                            } elseif ($end_month == 11) {
                                $plan_end_column = 0 + $number_colum_first + 1;
                            } else { //$end_month < 11
                                $isDraw = false;
                            }
                        }
                    } else {
                        $isDraw = false;
                    }
                } elseif ($current_year - $end_year > 1) {
                    $isDraw = false;
                }
            }
            ?>
            @if($isDraw)
                <script>
                    function coorPlan{{$theme_active_list_item->id}}() {
                        let table = '#table-active-theme',
                            color = 'black',
                            positionTop = 2.3,
                            planStartRow = {{$theme_stt}},
                            planStartColumn = {{ $plan_start_column }},
                            planEndRow = {{$theme_stt}},
                            planEndColumn = {{ $plan_end_column }};
                        let startPeriod = getPeriod({{$date_start->format('d')}});
                        let endPeriod = getPeriod({{$date_expected_completion->format('d')}});
                        let hasStartpoint = true;
                        let hasEndpoint = true;
                        @if(!$has_start_point)
                            startPeriod = -1;
                        hasStartpoint = false;
                        @endif
                                @if(!$has_end_point)
                            endPeriod = -1;
                        hasEndpoint = false;
                                @endif
                        var coors = [table, planStartRow, planStartColumn, planEndRow, planEndColumn, color, positionTop, startPeriod, endPeriod, hasEndpoint, hasStartpoint];
                        return coors;
                    }

                    var isDraw{{$theme_active_list_item->id}} = true;
                </script>
            @endif
            <?php $theme_stt++; ?>
        @endforeach
        </tbody>
    </table>
    <script>
        $(function () {
            @foreach($theme_active_list as $theme_active_list_item)
            if (typeof isDraw{{$theme_active_list_item->id}} !== 'undefined') {
                drawArrowOnTableToppage(coorPlan{{$theme_active_list_item->id}}());
                resizehandleToppage(coorPlan{{$theme_active_list_item->id}});
            }
            @endforeach
        });
    </script>
    <?php
    $denominator_meeting_current_year = 0;
    $denominator_meeting_next_year = 0;
    $denominator_time_current_year = 0;
    $denominator_time_next_year = 0;
    $denominator_complete_current_year = 0;
    $denominator_complete_next_year = 0;
    $denominator_kaizen_last_year = 0;
    $denominator_kaizen_current_year = 0;
    $denominator_kaizen_next_year = 0;
    $denominator_study_last_year = 0;
    $denominator_study_current_year = 0;
    $denominator_study_next_year = 0;

    $denominator_meeting_actual_last_year = 0;
    $denominator_meeting_actual_next_year = 0;
    $denominator_time_actual_last_year = 0;
    $denominator_time_actual_current_year = 0;
    $denominator_time_actual_next_year = 0;

    $is_display_current_year = false;
    $is_display_last_year = false;
    $is_display_next_year = false;
    ?>
    <table class="table-progress" id="tb2" style="table-layout: fixed">
        <thead>
        <tr>
            <th style="width: 5%">No</th>
            <th style="">管理項目</th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month > 2) {
                    $is_display_current_year = true;
                    echo '<span class="d-block year-on-top">' . $current_year . '</span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month - 2) . '</span>';
                } else {
                    $is_display_last_year = true;
                    $month_temp = 12;
                    if ($current_month == 1) {
                        $month_temp = 11;
                    }
                    echo '<span class="d-block year-on-top">' . ($current_year - 1) . '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month > 1) {
                    echo '<span class="d-block year-on-top">';
                    if (!$is_display_current_year) {
                        echo $current_year;
                        $is_display_current_year = true;
                    }
                    echo '</span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month - 1) . '</span>';
                } else {
                    $month_temp = 12;
                    echo '<span class="d-block year-on-top">';
                    if (!$is_display_last_year) {
                        echo $current_year - 1;
                        $is_display_last_year = true;
                    }
                    echo '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th class="theme-head-active" style="width: 12%; background-color:rgb(255, 204, 204)">
                    <span class="d-block year-on-top">
                    <?php
                        if (!$is_display_current_year) {
                            echo $current_year;
                            $is_display_current_year = true;
                        }
                        ?>
                    </span>
                <span class="month-current">{{ $current_month }}</span>
            </th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month < 12) {
                    echo '<span class="d-block year-on-top"></span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month + 1) . '</span>';
                } else {
                    $month_temp = 1;
                    $is_display_next_year = true;
                    echo '<span class="d-block year-on-top">' . ($current_year + 1) . '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
            <th style="width: 12%" class="theme-head-active">
                <?php
                if ($current_month < 11) {
                    echo '<span class="d-block year-on-top"></span>';
                    echo '<span class="d-block month-on-bottom">' . ($current_month + 2) . '</span>';
                } else {
                    $month_temp = 1;
                    if ($current_month == 12) {
                        $month_temp = 2;
                    }
                    echo '<span class="d-block year-on-top">';
                    if (!$is_display_next_year) {
                        echo $current_year + 1;
                    }
                    echo '</span>';
                    echo '<span class="d-block month-on-bottom">' . $month_temp . '</span>';
                }
                ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>会合回数（<span class="text-primary">目標 {{ isset($promotion_circle)? $promotion_circle->target_number_of_meeting : 0 }}回/月</span>）
            </td>
            <td>
                    <span class="text-primary d-block"><!--plan-->
                        <?php
                        if ($current_month > 2) {
                            if (isset($promotion_circle)) {
                                if ($current_month - 2 > 1) {
                                    for ($i = 1; $i < $current_month - 2; $i++) {
                                        $denominator_meeting_current_year += $promotion_circle->target_number_of_meeting;
                                    }
                                }
                                $denominator_meeting_current_year += $promotion_circle->target_number_of_meeting;
                                echo isset($promotion_circle->target_number_of_meeting) ? $promotion_circle->target_number_of_meeting : '0';
                                echo '/' . $denominator_meeting_current_year;
                            } else {
                                echo '0/0';
                            }
                        } else { //last year
                            if (isset($promotion_circle_last_year)) {
                                $month_temp = 12;
                                if ($current_month == 1) {
                                    $month_temp = 11;
                                }
                                $denominator_temp = 0;
                                for ($i = $month_temp; $i > 0; $i--) {
                                    $denominator_temp += $promotion_circle_last_year->target_number_of_meeting;
                                }
                                echo isset($promotion_circle_last_year->target_number_of_meeting) ? $promotion_circle_last_year->target_number_of_meeting : '0';
                                echo '/' . $denominator_temp;
                            } else {
                                echo '0/0';
                            }
                        }
                        ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    if ($current_month > 2) {
                        $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', ($current_month - 2))->count();
                        $count_denominator = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                })                                
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', '<=', ($current_month - 2))->count();
                        echo $count . '/' . $count_denominator;
                    } else { //last year
                        $month_temp = 12;
                        if ($current_month == 1) {
                            $month_temp = 11;
                        }
                        //get list meeting last year
                        $meeting_list_temp = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', ($current_year - 1))->get();
                        $denominator_temp = 0;
                        $numerator_temp = 0;
                        foreach ($meeting_list_temp as $meeting_list_temp_item) {
                            $date = new DateTime($meeting_list_temp_item->date_execution);
                            $month = $date->format('m');
                            if ($month <= $month_temp) {
                                $denominator_temp += 1;
                            }
                            if ($month == $month_temp) {
                                $numerator_temp++;
                            }
                        }
                        echo $numerator_temp . '/' . $denominator_temp;
                    }
                    ?>
                    </span>
            </td>
            <td>
                    <span class="text-primary d-block">
                        <?php
                        if ($current_month > 1) {
                            if (isset($promotion_circle)) {
                                $denominator_meeting_current_year += $promotion_circle->target_number_of_meeting;
                                echo isset($promotion_circle->target_number_of_meeting) ? $promotion_circle->target_number_of_meeting : '0';
                                echo '/' . $denominator_meeting_current_year;
                            } else {
                                echo '0/0';
                            }
                        } else { //last year
                            if (isset($promotion_circle_last_year)) {
                                $denominator_temp = 0;
                                for ($i = 12; $i > 0; $i--) {
                                    $denominator_temp += $promotion_circle_last_year->target_number_of_meeting;
                                }
                                echo isset($promotion_circle_last_year->target_number_of_meeting) ? $promotion_circle_last_year->target_number_of_meeting : '0';
                                echo '/' . $denominator_temp;
                            } else {
                                echo '0/0';
                            }
                        }
                        ?>
                    </span>
                <span style="display: block">
                        <?php
                    if ($current_month > 1) {
                        $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                 })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', ($current_month - 1))->count();
                        $count_denominator = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', '<=', ($current_month - 1))->count();
                        echo $count . '/' . $count_denominator;
                    } else { //last year
                        $month_temp = 12;
                        //get list meeting last year
                        $meeting_list_temp = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', ($current_year - 1))->get();
                        $denominator_temp = 0;
                        $numerator_temp = 0;
                        foreach ($meeting_list_temp as $meeting_list_temp_item) {
                            $date = new DateTime($meeting_list_temp_item->date_execution);
                            $month = $date->format('m');
                            if ($month <= $month_temp) {
                                $denominator_temp += 1;
                            }
                            if ($month == $month_temp) {
                                $numerator_temp++;
                            }
                        }
                        echo $numerator_temp . '/' . $denominator_temp;
                    }
                    ?>
                    </span>
            </td>
            <td><!--current month-->
                <span class="text-primary d-block">
                    <?php
                    if (isset($promotion_circle)) {
                        $denominator_meeting_current_year += $promotion_circle->target_number_of_meeting;
                        echo isset($promotion_circle->target_number_of_meeting) ? $promotion_circle->target_number_of_meeting : '0';
                        echo '/' . $denominator_meeting_current_year;
                    } else {
                        echo '0/0';
                    }
                    ?>
                    </span>
                <span style="display: block">
                        <?php
                    $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                            ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month)->count();
                    $count_denominator = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                            ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', '<=', $current_month)->count();
                    echo $count . '/' . $count_denominator;
                    ?>
                    </span>
            </td>
            <td>
                    <span class="text-primary d-block">
                        <?php
                        if ($current_month < 12) {
                            if (isset($promotion_circle)) {
                                $denominator_meeting_current_year += $promotion_circle->target_number_of_meeting;
                                echo isset($promotion_circle->target_number_of_meeting) ? $promotion_circle->target_number_of_meeting : '0';
                                echo '/' . $denominator_meeting_current_year;
                            } else {
                                echo '0/0';
                            }
                        } else { //next year
                            if (isset($promotion_circle_next_year)) {
                                $denominator_meeting_next_year += $promotion_circle_next_year->target_number_of_meeting;
                                echo isset($promotion_circle_next_year->target_number_of_meeting) ? $promotion_circle_next_year->target_number_of_meeting : '0';
                                echo '/' . $denominator_meeting_next_year;
                            } else {
                                echo '0/0';
                            }
                        }
                        ?>
                    </span>
                <span style="display: block">
                        <?php
                    if ($current_month < 12) {
                        $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month + 1)->count();
                        $count_denominator = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', '<=', $current_month + 1)->count();
                        echo $count . '/' . $count_denominator;
                    } else { //next year
                        $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', ($current_year + 1))->whereMonth('date_execution', 1)->count();
                        if ($count > 0) {
                            $denominator_meeting_actual_next_year += $count;
                        }
                        echo $count . '/' . $denominator_meeting_actual_next_year;
                    }

                    ?>
                    </span>
            </td>
            <td>
                    <span class="text-primary d-block">
                        <?php
                        if ($current_month < 11) {
                            if (isset($promotion_circle)) {
                                $denominator_meeting_current_year += $promotion_circle->target_number_of_meeting;
                                echo isset($promotion_circle->target_number_of_meeting) ? $promotion_circle->target_number_of_meeting : '0';
                                echo '/' . $denominator_meeting_current_year;
                            } else {
                                echo '0/0';
                            }
                        } else { //next year
                            if (isset($promotion_circle_next_year)) {
                                $denominator_meeting_next_year += $promotion_circle_next_year->target_number_of_meeting;
                                echo isset($promotion_circle_next_year->target_number_of_meeting) ? $promotion_circle_next_year->target_number_of_meeting : '0';
                                echo '/' . $denominator_meeting_next_year;
                            } else {
                                echo '0/0';
                            }
                        }
                        ?>
                    </span>
                <span style="display: block">
                        <?php
                    if ($current_month < 11) {
                        $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month + 2)->count();
                        $count_denominator = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', $current_year)->whereMonth('date_execution', '<=', $current_month + 2)->count();
                        echo $count . '/' . $count_denominator;
                    } else { //next year
                        $month_temp = 1;
                        if ($current_month == 12) {
                            $month_temp = 2;
                        }
                        $count = DB::table('activities')->where(function($query)
                                {
                                    $query->where('activity_category', \App\Enums\Activity::MEETING)
                                    ->orWhere('activity_category', \App\Enums\Activity::STUDY_GROUP)
                                    ->orWhere('activity_category', \App\Enums\Activity::OTHER)
                                    ->orWhere('activity_category', \App\Enums\Activity::KAIZEN);
                                    })
                                ->where('circle_id', session('circle.id'))->whereYear('date_execution', ($current_year + 1))->whereMonth('date_execution', $month_temp)->count();
                        if ($count > 0) {
                            $denominator_meeting_actual_next_year += $count;
                        }
                        echo $count . '/' . $denominator_meeting_actual_next_year;
                    }
                    ?>
                    </span>
            </td>
        </tr>
        <tr><!--Time meeting-->
            <td>2</td>
            <td>会合時間（<span class="text-primary">目標 {{ isset($promotion_circle)? number_format(round($promotion_circle->target_hour_of_meeting, 2),2) : 0.00 }}ｈ/月</span>）
            </td>
            <td><!--(current month - 2)-->
                <span class="text-primary d-block">
                        <?php
                    if ($current_month > 2) {
                        if (isset($promotion_circle)) {
                            if ($current_month - 2 > 1) {
                                for ($i = 1; $i < $current_month - 2; $i++) {
                                    $denominator_time_current_year += $promotion_circle->target_hour_of_meeting;
                                }
                            }
                            $denominator_time_current_year += $promotion_circle->target_hour_of_meeting;
                            echo number_format(round($promotion_circle->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_current_year, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    } else { //last year
                        if (isset($promotion_circle_last_year)) {
                            $month_temp = 12;
                            if ($current_month == 1) {
                                $month_temp = 11;
                            }
                            $denominator_temp = 0;
                            for ($i = $month_temp; $i > 0; $i--) {
                                $denominator_temp += $promotion_circle_last_year->target_hour_of_meeting;
                            }
                            echo number_format(round($promotion_circle_last_year->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_temp, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    }
                    ?>
                    </span>
                <span style="display: block">
                        <?php
                    $meeting_col_minus2 = 0;
                    $total_meeting_minus2 = 0;
                    if ($current_month == 1) {
                        $meeting_col_minus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year - 1)
                            ->whereMonth('date_execution', 11)
                            ->sum('time_span');
                        $total_meeting_minus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year - 1)
                            ->whereMonth('date_execution', '<>', 12)
                            ->sum('time_span');
                    } elseif ($current_month == 2) {
                        $meeting_col_minus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year - 1)
                            ->whereMonth('date_execution', 12)
                            ->sum('time_span');
                        $total_meeting_minus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year - 1)
                            ->sum('time_span');
                    } else {
                        $meeting_col_minus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', $current_month - 2)
                            ->sum('time_span');
                        $total_meeting_minus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', '<=', $current_month - 2)
                            ->sum('time_span');
                    }
                    echo number_format(round($meeting_col_minus2, 2), 2) . '/<br/>' . number_format(round($total_meeting_minus2, 2), 2);
                    ?>

                    </span>
            </td>
            <td><!--(current month - 1)-->
                <span class="text-primary d-block">
                        <?php
                    if ($current_month > 1) {
                        if (isset($promotion_circle)) {
                            $denominator_time_current_year += $promotion_circle->target_hour_of_meeting;
                            echo number_format(round($promotion_circle->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_current_year, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    } else { //last year
                        if (isset($promotion_circle_last_year)) {
                            $denominator_temp = 0;
                            for ($i = 12; $i > 0; $i--) {
                                $denominator_temp += $promotion_circle_last_year->target_hour_of_meeting;
                            }
                            echo number_format(round($promotion_circle_last_year->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_temp, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    }
                    ?>
                    </span>
                <span style="display: block">
                       <?php
                    $meeting_col_minus1 = 0;
                    $total_meeting_minus1 = 0;
                    if ($current_month == 1) {
                        $meeting_col_minus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year - 1)
                            ->whereMonth('date_execution', 12)
                            ->sum('time_span');
                        $total_meeting_minus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year - 1)
                            ->sum('time_span');
                    } else {
                        $meeting_col_minus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', $current_month - 1)
                            ->sum('time_span');
                        $total_meeting_minus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', '<=', $current_month - 1)
                            ->sum('time_span');
                    }
                    echo number_format(round($meeting_col_minus1, 2), 2) . '/<br/>' . number_format(round($total_meeting_minus1, 2), 2);
                    ?>
                    </span>
            </td>
            <td><!--current month-->
                <span class="text-primary d-block">
                        <?php
                    if (isset($promotion_circle)) {
                        $denominator_time_current_year += $promotion_circle->target_hour_of_meeting;
                        echo number_format(round($promotion_circle->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_current_year, 2);
                    } else {
                        echo '0.00/0.00';
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                       <?php
                    $meeting_col_current = 0;
                    $total_meeting_current = 0;
                    $meeting_col_current = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                        ->whereYear('date_execution', $current_year)
                        ->whereMonth('date_execution', $current_month)
                        ->sum('time_span');
                    $total_meeting_current = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                        ->whereYear('date_execution', $current_year)
                        ->whereMonth('date_execution', '<=', $current_month)
                        ->sum('time_span');
                    echo number_format(round($meeting_col_current, 2), 2) . '/<br/>' . number_format(round($total_meeting_current, 2), 2);
                    ?>
                    </span>
            </td>
            <td><!--(current month + 1)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($current_month < 12) {
                        if (isset($promotion_circle)) {
                            $denominator_time_current_year += $promotion_circle->target_hour_of_meeting;
                            echo number_format(round($promotion_circle->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_current_year, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    } else { //next year
                        if (isset($promotion_circle_next_year)) {
                            $denominator_time_next_year += $promotion_circle_next_year->target_hour_of_meeting;
                            echo number_format(round($promotion_circle_next_year->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_next_year, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    $meeting_col_plus1 = 0;
                    $total_meeting_plus1 = 0;
                    if ($current_month == 12) {
                        $meeting_col_plus1 = $total_meeting_plus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year + 1)
                            ->whereMonth('date_execution', 1)
                            ->sum('time_span');
                    } else {
                        $meeting_col_plus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', $current_month + 1)
                            ->sum('time_span');
                        $total_meeting_plus1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', '<=', $current_month + 1)
                            ->sum('time_span');
                    }
                    echo number_format(round($meeting_col_plus1, 2), 2) . '/<br/>' . number_format(round($total_meeting_plus1, 2), 2);
                    ?>
                    </span>
            </td>
            <td><!--(current month + 2)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($current_month < 11) {
                        if (isset($promotion_circle)) {
                            $denominator_time_current_year += $promotion_circle->target_hour_of_meeting;
                            echo number_format(round($promotion_circle->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_current_year, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    } else { //next year
                        if (isset($promotion_circle_next_year)) {
                            $denominator_time_next_year += $promotion_circle_next_year->target_hour_of_meeting;
                            echo number_format(round($promotion_circle_next_year->target_hour_of_meeting, 2), 2) . '/<br>' . number_format($denominator_time_next_year, 2);
                        } else {
                            echo '0.00/0.00';
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    $meeting_col_plus2 = 0;
                    $total_meeting_plus2 = 0;
                    if ($current_month == 11) {
                        $meeting_col_plus2 = $total_meeting_plus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year + 1)
                            ->whereMonth('date_execution', 1)
                            ->sum('time_span');
                    } elseif ($current_month == 12) {
                        $meeting_col_plus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year + 1)
                            ->whereMonth('date_execution', 2)
                            ->sum('time_span');
                        $total_meeting_plus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year + 1)
                            ->whereMonth('date_execution', '<=', 2)
                            ->sum('time_span');
                    } else {
                        $meeting_col_plus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', $current_month + 2)
                            ->sum('time_span');
                        $total_meeting_plus2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', \App\Enums\Activity::MEETING)
                            ->whereYear('date_execution', $current_year)
                            ->whereMonth('date_execution', '<=', $current_month + 2)
                            ->sum('time_span');
                    }
                    echo number_format(round($meeting_col_plus2, 2), 2) . '/<br/>' . number_format(round($total_meeting_plus2, 2), 2);
                    ?>
                    </span>
            </td>
        </tr>
        <?php
        $target_case_complete_current_year = 0;
        $target_case_complete_last_year = 0;
        $target_case_complete_next_year = 0;
        $complete_actual_current_year = 0;
        $complete_actual_next_year = 0;
        $surplus_complete_actual_current_year = 0;
        $surplus_complete_actual_last_year = 0;
        $surplus_complete_actual_next_year = 0;
        if (isset($promotion_circle)) {
            $target_case_complete_current_year = (int)$promotion_circle->target_case_complete;
            $surplus_complete_actual_current_year = $target_case_complete_current_year % 12;
        }
        if (isset($promotion_circle_next_year)) {
            $target_case_complete_next_year = (int)$promotion_circle_next_year->target_case_complete;
            $surplus_complete_actual_next_year = $target_case_complete_next_year % 12;
        }
        if (isset($promotion_circle_last_year)) {
            $target_case_complete_last_year = (int)$promotion_circle_last_year->target_case_complete;
            $surplus_complete_actual_last_year = $target_case_complete_last_year % 12;
        }
        ?>
        <tr><!--case complete-->
            <td>3</td>
            <td>テーマ完了件数（<span class="text-primary">目標 {{ $target_case_complete_current_year }}回/年</span>）</td>
            <td><!--(current month - 2)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($current_month > 2) {
                        if ($surplus_complete_actual_current_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_case_complete_current_year / 12) . '/' . ($target_case_complete_current_year / 12) * ($current_month - 2);
                        }
                    } else { //last year
                        $month_temp = 12;
                        if ($current_month == 1) {
                            $month_temp = 11;
                        }
                        if ($surplus_complete_actual_last_year != 0) {
                            if ($month_temp != 12) {
                                echo '0/0';
                            } else {
                                echo $target_case_complete_last_year . '/' . $target_case_complete_last_year;
                            }
                        } else {
                            echo ($target_case_complete_last_year / 12) . '/' . ($target_case_complete_last_year / 12) * $month_temp;
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    if ($current_month > 2) {
                        $count_complete_current_year_last_month_2 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', $current_year)->whereMonth('date_actual_completion', $current_month - 2)
                            ->count();
                        $complete_actual_current_year += $count_complete_current_year_last_month_2;
                        if ($current_month - 2 > 1) {
                            $count_complete_current_year_last_month_21 = DB::table('themes')->where('circle_id', session('circle.id'))
                                ->whereYear('date_actual_completion', $current_year)->whereMonth('date_actual_completion', '<=', $current_month - (2 + 1))
                                ->count();
                            $complete_actual_current_year += $count_complete_current_year_last_month_21;
                        }
                        echo $count_complete_current_year_last_month_2 . '/' . $complete_actual_current_year;
                    } else { //last year
                        $month_temp = 12;
                        if ($current_month == 1) {
                            $month_temp = 11;
                        }
                        $count_complete_last_year_last_month_2 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', ($current_year - 1))->whereMonth('date_actual_completion', $month_temp)
                            ->count();

                        $count_complete_last_year_last_month_21 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', ($current_year - 1))->whereMonth('date_actual_completion', '<=', ($month_temp - 1))
                            ->count();

                        echo $count_complete_last_year_last_month_2 . '/' . ($count_complete_last_year_last_month_2 + $count_complete_last_year_last_month_21);
                    }
                    ?>
                    </span>
            </td>
            <td><!--(current month - 1)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($current_month > 1) {
                        if ($surplus_complete_actual_current_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_case_complete_current_year / 12) . '/' . ($target_case_complete_current_year / 12) * ($current_month - 1);
                        }
                    } else { //last year
                        $month_temp = 12;
                        if ($surplus_complete_actual_last_year != 0) {

                            echo $target_case_complete_last_year . '/' . $target_case_complete_last_year;
                        } else {
                            echo ($target_case_complete_last_year / 12) . '/' . ($target_case_complete_last_year / 12) * $month_temp;
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    if ($current_month > 1) {
                        $count_complete_current_year_last_month_1 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', $current_year)->whereMonth('date_actual_completion', $current_month - 1)
                            ->count();
                        if ($count_complete_current_year_last_month_1 > 0) {
                            $complete_actual_current_year += $count_complete_current_year_last_month_1;
                        }
                        echo $count_complete_current_year_last_month_1 . '/' . $complete_actual_current_year;
                    } else { //last year
                        $month_temp = 12;
                        $count_complete_last_year_last_month_1 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', ($current_year - 1))->whereMonth('date_actual_completion', $month_temp)
                            ->count();

                        $count_complete_last_year_last_month_11 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', ($current_year - 1))->whereMonth('date_actual_completion', '<=', ($month_temp - 1))
                            ->count();

                        echo $count_complete_last_year_last_month_1 . '/' . ($count_complete_last_year_last_month_1 + $count_complete_last_year_last_month_11);
                    }
                    ?>
                    </span>
            </td>
            <td><!--(current month)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($surplus_complete_actual_current_year != 0) {
                        if ($current_month != 12) {
                            echo '0/0';
                        } else {
                            echo $target_case_complete_current_year . '/' . $target_case_complete_current_year;
                        }
                    } else {
                        $int = $target_case_complete_current_year / 12;
                        echo ($target_case_complete_current_year / 12) . '/' . ($target_case_complete_current_year / 12) * $current_month;
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    $count_complete_current = DB::table('themes')->where('circle_id', session('circle.id'))
                        ->whereYear('date_actual_completion', $current_year)->whereMonth('date_actual_completion', $current_month)
                        ->count();
                    if ($count_complete_current > 0) {
                        $complete_actual_current_year += $count_complete_current;
                    }
                    echo $count_complete_current . '/' . $complete_actual_current_year;
                    ?>
                    </span>
            </td>
            <td><!--(current month + 1)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($current_month < 12) { //current year
                        if ($surplus_complete_actual_current_year != 0) {
                            if (($current_month + 1) != 12) {
                                echo '0/0';
                            } else {
                                echo $target_case_complete_current_year . '/' . $target_case_complete_current_year;
                            }
                        } else {
                            $int = $target_case_complete_current_year / 12;
                            echo ($target_case_complete_current_year / 12) . '/' . ($target_case_complete_current_year / 12) * ($current_month + 1);
                        }
                    } else { //next year 1
                        if ($surplus_complete_actual_next_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_case_complete_next_year / 12) . '/' . ($target_case_complete_next_year / 12);
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    if ($current_month < 12) { //current year
                        $count_complete_current_year_next_month_1 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', $current_year)->whereMonth('date_actual_completion', $current_month + 1)
                            ->count();
                        if ($count_complete_current_year_next_month_1 > 0) {
                            $complete_actual_current_year += $count_complete_current_year_next_month_1;
                        }
                        echo $count_complete_current_year_next_month_1 . '/' . $complete_actual_current_year;
                    } else { //next year 1
                        $count_complete_next_year_1 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', $current_year + 1)->whereMonth('date_actual_completion', 1)
                            ->count();
                        if ($count_complete_next_year_1 > 0) {
                            $complete_actual_next_year += $count_complete_next_year_1;
                        }
                        echo $count_complete_next_year_1 . '/' . $complete_actual_next_year;
                    }
                    ?>
                    </span>
            </td>
            <td><!--(current month + 2)-->
                <span class="text-primary d-block"><!--plan-->
                        <?php
                    if ($current_month < 11) { //current year
                        if ($surplus_complete_actual_current_year != 0) {

                            if (($current_month + 2) != 12) {
                                echo '0/0';
                            } else {
                                echo $target_case_complete_current_year . '/' . $target_case_complete_current_year;
                            }
                        } else {
                            $int = $target_case_complete_current_year / 12;
                            echo ($target_case_complete_current_year / 12) . '/' . ($target_case_complete_current_year / 12) * ($current_month + 2);
                        }

                    } else { //next year 2
                        $month_temp = 1;
                        if ($current_month == 12) {
                            $month_temp = 2;
                        }
                        if ($surplus_complete_actual_next_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_case_complete_next_year / 12) . '/' . ($target_case_complete_next_year / 12) * $month_temp;
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                        <?php
                    if ($current_month < 11) {  //current year
                        $count_complete_current_year_next_month_2 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', $current_year)->whereMonth('date_actual_completion', $current_month + 2)
                            ->count();
                        if ($count_complete_current_year_next_month_2 > 0) {
                            $complete_actual_current_year += $count_complete_current_year_next_month_2;
                        }
                        echo $count_complete_current_year_next_month_2 . '/' . $complete_actual_current_year;
                    } else { //next year
                        $count_complete_next_year_2 = DB::table('themes')->where('circle_id', session('circle.id'))
                            ->whereYear('date_actual_completion', $current_year + 1)->whereMonth('date_actual_completion', ($current_month == 11) ? 1 : 2)
                            ->count();
                        if ($count_complete_next_year_2 > 0) {
                            $complete_actual_next_year += $count_complete_next_year_2;
                        }
                        echo $count_complete_next_year_2 . '/' . $complete_actual_next_year;
                    }
                    ?>
                    </span>
            </td>
        </tr>
        <?php
        $target_kaizen_current_year = 0;
        $target_kaizen_last_year = 0;
        $target_kaizen_next_year = 0;
        $kaizen_current_year = 0;
        $kaizen_next_year = 0;
        $surplus_kaizen_current_year = 0;
        $surplus_kaizen_last_year = 0;
        $surplus_kaizen_next_year = 0;
        if (isset($promotion_circle)) {
            $target_kaizen_current_year = (int)$promotion_circle->improved_cases;
            $surplus_kaizen_current_year = $target_kaizen_current_year % 12;
        }
        if (isset($promotion_circle_next_year)) {
            $target_kaizen_next_year = (int)$promotion_circle_next_year->improved_cases;
            $surplus_kaizen_next_year = $target_kaizen_next_year % 12;
        }
        if (isset($promotion_circle_last_year)) {
            $target_kaizen_last_year = (int)$promotion_circle_last_year->improved_cases;
            $surplus_kaizen_last_year = $target_kaizen_last_year % 12;
        }
        ?>
        <tr><!--kaizen-->
            <td>4</td>
            <td>改善（個人）件数（<span class="text-primary">目標 {{ isset($promotion_circle)? $promotion_circle->improved_cases : 0 }}回/年</span>）
            </td>
            <td><!--(current month - 2)-->
                <span class="text-primary d-block"><!--plan-->
			            <?php
                    if ($current_month > 2) {
                        if ($surplus_kaizen_current_year != 0) {
                            echo '0/0';
                        } else {
                            $int = $target_kaizen_current_year / 12;
                            echo ($target_kaizen_current_year / 12) . '/' . ($target_kaizen_current_year / 12) * ($current_month - 2);
                        }
                    } else { //last year
                        $month_temp = ($current_month == 1) ? 11 : 12;
                        if ($surplus_kaizen_last_year != 0) {
                            if ($month_temp != 12) {
                                echo '0/0';
                            } else {
                                echo $target_kaizen_last_year . '/' . $target_kaizen_last_year;
                            }
                        } else {
                            echo ($target_kaizen_last_year / 12) . '/' . ($target_kaizen_last_year / 12) * $month_temp;
                        }
                    }
                    ?>
                </span>
                <span style="display: block"><!--actual-->
                    <?php
                    $kaizen_col_minus2 = 0;
                    $total_kaizen_minus2 = 0;
                    $actual_kaizen_month_previous_year = DB::table('activity_approvals_statistics')
                                                    ->where('circle_id', session('circle.id'))
                                                    ->where('year', $current_year - 1)->first();
                    $kaizen_month_1 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_1 : 0;
                    $kaizen_month_2 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_2 : 0;
                    $kaizen_month_3 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_3 : 0;
                    $kaizen_month_4 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_4 : 0;
                    $kaizen_month_5 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_5 : 0;
                    $kaizen_month_6 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_6 : 0;
                    $kaizen_month_7 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_7 : 0;
                    $kaizen_month_8 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_8 : 0;
                    $kaizen_month_9 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_9 : 0;
                    $kaizen_month_10 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_10 : 0;
                    $kaizen_month_11 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_11 : 0;
                    $kaizen_month_12 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_12 : 0;
                    $kaizen_month_total = $kaizen_month_1 + $kaizen_month_2 + $kaizen_month_3
                                        + $kaizen_month_4 + $kaizen_month_5 + $kaizen_month_6
                                        + $kaizen_month_7 + $kaizen_month_8 + $kaizen_month_9
                                        + $kaizen_month_10 + $kaizen_month_11 + $kaizen_month_12;
                    if ($current_month == 1) {
                        $kaizen_col_minus2 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_11 : 0;
                        $total_kaizen_minus2 = $kaizen_month_total - $kaizen_month_12;
                    } elseif ($current_month == 2) {
                        $kaizen_col_minus2 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_12 : 0;
                        $total_kaizen_minus2 = $kaizen_month_total;
                    } else {
                        $actual_kaizen_month_current_year = DB::table('activity_approvals_statistics')
                                                                    ->where('circle_id', session('circle.id'))
                                                                    ->where('year', $current_year)->first();
                        $kaizen_month_1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_1 : 0;
                        $kaizen_month_2 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_2 : 0;
                        $kaizen_month_3 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_3 : 0;
                        $kaizen_month_4 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_4 : 0;
                        $kaizen_month_5 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_5 : 0;
                        $kaizen_month_6 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_6 : 0;
                        $kaizen_month_7 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_7 : 0;
                        $kaizen_month_8 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_8 : 0;
                        $kaizen_month_9 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_9 : 0;
                        $kaizen_month_10 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_10 : 0;
                        $kaizen_month_11 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_11 : 0;
                        $kaizen_month_12 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_12 : 0;
                        $kaizen_month_total = $kaizen_month_1 + $kaizen_month_2 + $kaizen_month_3
                                            + $kaizen_month_4 + $kaizen_month_5 + $kaizen_month_6
                                            + $kaizen_month_7 + $kaizen_month_8 + $kaizen_month_9
                                            + $kaizen_month_10 + $kaizen_month_11 + $kaizen_month_12;
                        $kaizen_col_minus2 = isset($actual_kaizen_month_current_year) ?  $actual_kaizen_month_current_year->{'kaizen_month_' . ($current_month - 2)} : 0;
                        for($i = 1; $i <= $current_month - 2;$i++)
                        {
                            $total_kaizen_minus2 += isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . $i} : 0;
                        }                         
                    }
                    echo $kaizen_col_minus2 . '/' . $total_kaizen_minus2;
                    ?>
                </span>
            </td>
            <td><!--(current month - 1)-->
                <span class="text-primary d-block"><!--plan-->
			            <?php
                    if ($current_month > 1) {
                        if ($surplus_kaizen_current_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_kaizen_current_year / 12) . '/' . ($target_kaizen_current_year / 12) * ($current_month - 1);
                        }
                    } else { //last year
                        $month_temp = 12;
                        if ($surplus_kaizen_last_year != 0) {
                            echo $target_kaizen_last_year . '/' . $target_kaizen_last_year;
                        } else {
                            echo ($target_kaizen_last_year / 12) . '/' . ($target_kaizen_last_year / 12) * $month_temp;
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                    <?php
                    $kaizen_col_minus1 = 0;
                    $total_kaizen_minus1 = 0;
                    if ($current_month == 1) {
                        $actual_kaizen_month_previous_year = DB::table('activity_approvals_statistics')
                                                        ->where('circle_id', session('circle.id'))
                                                        ->where('year', $current_year - 1)->first();
                        $kaizen_month_1 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_1 : 0;
                        $kaizen_month_2 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_2 : 0;
                        $kaizen_month_3 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_3 : 0;
                        $kaizen_month_4 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_4 : 0;
                        $kaizen_month_5 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_5 : 0;
                        $kaizen_month_6 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_6 : 0;
                        $kaizen_month_7 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_7 : 0;
                        $kaizen_month_8 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_8 : 0;
                        $kaizen_month_9 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_9 : 0;
                        $kaizen_month_10 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_10 : 0;
                        $kaizen_month_11 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_11 : 0;
                        $kaizen_month_12 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_12 : 0;
                        $kaizen_month_total = $kaizen_month_1 + $kaizen_month_2 + $kaizen_month_3
                                            + $kaizen_month_4 + $kaizen_month_5 + $kaizen_month_6
                                            + $kaizen_month_7 + $kaizen_month_8 + $kaizen_month_9
                                            + $kaizen_month_10 + $kaizen_month_11 + $kaizen_month_12;
                        $kaizen_col_minus1 = isset($actual_kaizen_month_previous_year) ? $actual_kaizen_month_previous_year->kaizen_month_12 : 0;
                        $total_kaizen_minus1 = $kaizen_month_total;
                    } else {
                        $actual_kaizen_month_current_year = DB::table('activity_approvals_statistics')
                                                                    ->where('circle_id', session('circle.id'))
                                                                    ->where('year', $current_year)->first();
                        $kaizen_month_1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_1 : 0;
                        $kaizen_month_2 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_2 : 0;
                        $kaizen_month_3 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_3 : 0;
                        $kaizen_month_4 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_4 : 0;
                        $kaizen_month_5 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_5 : 0;
                        $kaizen_month_6 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_6 : 0;
                        $kaizen_month_7 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_7 : 0;
                        $kaizen_month_8 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_8 : 0;
                        $kaizen_month_9 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_9 : 0;
                        $kaizen_month_10 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_10 : 0;
                        $kaizen_month_11 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_11 : 0;
                        $kaizen_month_12 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_12 : 0;

                        $kaizen_col_minus1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . ($current_month - 1)} : 0;
                        for($i = 1; $i <= $current_month - 1;$i++)
                        {
                            $total_kaizen_minus1 += isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . $i} : 0;
                        }                        
                    }
                    echo $kaizen_col_minus1 . '/' . $total_kaizen_minus1;
                    ?>
                </span>
            </td>
            <td><!--(current month)-->
                <span class="text-primary d-block"><!--plan-->
			            <?php
                    if ($surplus_kaizen_current_year != 0) {
                        if ($current_month != 12) {
                            echo '0/0';
                        } else {
                            echo $target_kaizen_current_year . '/' . $target_kaizen_current_year;
                        }
                    } else {
                        echo ($target_kaizen_current_year / 12) . '/' . ($target_kaizen_current_year / 12) * $current_month;
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                    <?php
                    $kaizen_col_current = 0;
                    $total_kaizen_current = 0;
                    $actual_kaizen_month_current_year = DB::table('activity_approvals_statistics')
                                                                ->where('circle_id', session('circle.id'))
                                                                ->where('year', $current_year)->first();
                    $kaizen_month_1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_1 : 0;
                    $kaizen_month_2 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_2: 0;
                    $kaizen_month_3 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_3: 0;
                    $kaizen_month_4 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_4: 0;
                    $kaizen_month_5 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_5: 0;
                    $kaizen_month_6 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_6: 0;
                    $kaizen_month_7 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_7: 0;
                    $kaizen_month_8 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_8: 0;
                    $kaizen_month_9 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_9: 0;
                    $kaizen_month_10 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_10: 0;
                    $kaizen_month_11 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_11: 0;
                    $kaizen_month_12 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_12: 0;

                    $kaizen_col_current = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . ($current_month)} : 0;
                    for($i = 1; $i <= $current_month;$i++)
                    {
                        $total_kaizen_current += isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . $i} : 0;
                    }                      
                    echo $kaizen_col_current . '/' . $total_kaizen_current;
                    ?>
                </span>
            </td>
            <td><!--(current month + 1)-->
                <span class="text-primary d-block"><!--plan-->
			            <?php
                    if ($current_month < 12) { //current year
                        if ($surplus_kaizen_current_year != 0) {
                            if (($current_month + 1) != 12) {
                                echo '0/0';
                            } else {
                                echo $target_kaizen_current_year . '/' . $target_kaizen_current_year;
                            }
                        } else {
                            $int = $target_kaizen_current_year / 12;
                            echo ($target_kaizen_current_year / 12) . '/' . ($target_kaizen_current_year / 12) * ($current_month + 1);
                        }
                    } else { //next year 1
                        if ($surplus_kaizen_next_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_kaizen_next_year / 12) . '/' . ($target_kaizen_next_year / 12);
                        }
                    }
                    ?>
                    </span>
                <span style="display: block"><!--actual-->
                    <?php
                    $kaizen_col_plus1 = 0;
                    $total_kaizen_plus1 = 0;
                    if ($current_month == 12) {
                        $actual_kaizen_month_next_year = DB::table('activity_approvals_statistics')
                                                        ->where('circle_id', session('circle.id'))
                                                        ->where('year', $current_year + 1)->first();
                        $kaizen_month_1 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_1 : 0;
                        $kaizen_col_plus1 = $total_kaizen_plus1 = $kaizen_month_1;
                    } else {
                        $actual_kaizen_month_current_year = DB::table('activity_approvals_statistics')
                                                                    ->where('circle_id', session('circle.id'))
                                                                    ->where('year', $current_year)->first();
                        $kaizen_month_1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_1 : 0;
                        $kaizen_month_2 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_2 : 0;
                        $kaizen_month_3 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_3 : 0;
                        $kaizen_month_4 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_4 : 0;
                        $kaizen_month_5 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_5 : 0;
                        $kaizen_month_6 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_6 : 0;
                        $kaizen_month_7 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_7 : 0;
                        $kaizen_month_8 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_8 : 0;
                        $kaizen_month_9 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_9 : 0;
                        $kaizen_month_10 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_10 : 0;
                        $kaizen_month_11 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_11 : 0;
                        $kaizen_month_12 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_12 : 0;   
                        
                        $kaizen_col_plus1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . ($current_month + 1)} : 0;
                        for($i = 1; $i <= $current_month + 1;$i++)
                        {
                            $total_kaizen_plus1 += isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . $i} : 0;
                        }                         
                    }
                    echo $kaizen_col_plus1 . '/' . $total_kaizen_plus1;
                    ?>
                </span>
            </td>
            <td><!--(current month + 2)-->
                <span class="text-primary d-block"><!--plan-->
			            <?php
                    if ($current_month < 11) { //current year
                        if ($surplus_kaizen_current_year != 0) {
                            if (($current_month + 2) != 12) {
                                echo '0/0';
                            } else {
                                echo $target_kaizen_current_year . '/' . $target_kaizen_current_year;
                            }
                        } else {
                            $int = $target_kaizen_current_year / 12;
                            echo ($target_kaizen_current_year / 12) . '/' . ($target_kaizen_current_year / 12) * ($current_month + 2);
                        }
                    } else { //next year 2
                        $month_temp = ($current_month == 12) ? 2 : 1;
                        if ($surplus_kaizen_next_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_kaizen_next_year / 12) . '/' . ($target_kaizen_next_year / 12) * $month_temp;
                        }
                    }
                    ?>
		            </span>
                <span style="display: block"><!--actual-->
                    <?php
                    $kaizen_col_plus2 = 0;
                    $total_kaizen_plus2 = 0;
                     $actual_kaizen_month_next_year = DB::table('activity_approvals_statistics')
                                                     ->where('circle_id', session('circle.id'))
                                                     ->where('year', $current_year + 1)->first();
                     $kaizen_month_1 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_1 : 0;
                     $kaizen_month_2 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_2 : 0;
                     $kaizen_month_3 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_3 : 0;
                     $kaizen_month_4 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_4 : 0;
                     $kaizen_month_5 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_5 : 0;
                     $kaizen_month_6 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_6 : 0;
                     $kaizen_month_7 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_7 : 0;
                     $kaizen_month_8 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_8 : 0;
                     $kaizen_month_9 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_9 : 0;
                     $kaizen_month_10 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_10 : 0;
                     $kaizen_month_11 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_11 : 0;
                     $kaizen_month_12 = isset($actual_kaizen_month_next_year) ? $actual_kaizen_month_next_year->kaizen_month_12 : 0;

                    if ($current_month == 11) {
                        $kaizen_col_plus2 = $total_kaizen_plus2 = $kaizen_month_1;
                    } elseif ($current_month == 12) {
                        $kaizen_col_plus2 = $kaizen_month_2;
                        $total_kaizen_plus2 = $kaizen_month_1 + $kaizen_month_2;
                    } else {
                        $actual_kaizen_month_current_year = DB::table('activity_approvals_statistics')
                                                                    ->where('circle_id', session('circle.id'))
                                                                    ->where('year', $current_year)->first();
                        $kaizen_month_1 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_1 : 0;
                        $kaizen_month_2 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_2 : 0;
                        $kaizen_month_3 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_3 : 0;
                        $kaizen_month_4 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_4 : 0;
                        $kaizen_month_5 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_5 : 0;
                        $kaizen_month_6 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_6 : 0;
                        $kaizen_month_7 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_7 : 0;
                        $kaizen_month_8 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_8 : 0;
                        $kaizen_month_9 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_9 : 0;
                        $kaizen_month_10 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_10 : 0;
                        $kaizen_month_11 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_11 : 0;
                        $kaizen_month_12 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->kaizen_month_12 : 0;

                        $kaizen_col_plus2 = isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . ($current_month + 2)} : 0;
                        for($i = 1; $i <= $current_month + 2;$i++)
                        {
                            $total_kaizen_plus2 += isset($actual_kaizen_month_current_year) ? $actual_kaizen_month_current_year->{'kaizen_month_' . $i} : 0;
                        }                             
                    }
                    echo $kaizen_col_plus2 . '/' . $total_kaizen_plus2;
                    ?>
                </span>
            </td>
        </tr>
        <?php
        $target_study_current_year = 0;
        $target_study_last_year = 0;
        $target_study_next_year = 0;
        $study_current_year = 0;
        $study_next_year = 0;
        $surplus_study_current_year = 0;
        $surplus_study_last_year = 0;
        $surplus_study_next_year = 0;
        if (isset($promotion_circle)) {
            $target_study_current_year = (int)$promotion_circle->objectives_of_organizing_classe;
            $surplus_study_current_year = $target_study_current_year % 12;
        }
        if (isset($promotion_circle_next_year)) {
            $target_study_next_year = (int)$promotion_circle_next_year->objectives_of_organizing_classe;
            $surplus_study_next_year = $target_study_next_year % 12;
        }
        if (isset($promotion_circle_last_year)) {
            $target_study_last_year = (int)$promotion_circle_last_year->objectives_of_organizing_classe;
            $surplus_study_last_year = $target_study_last_year % 12;
        }
        ?>
        <tr><!--study group-->
            <td>5</td>
            <td>ＱＣ手法勉強会（<span class="text-primary">目標 {{ isset($promotion_circle)? $promotion_circle->objectives_of_organizing_classe : 0 }}回/年</span>）
            </td>
            <td><!--(current month - 2)-->
                <span class="text-primary d-block"><!--plan-->
		                <?php
                    if ($current_month > 2) {
                        if ($surplus_study_current_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_study_current_year / 12) . '/' . ($target_study_current_year / 12) * ($current_month - 2);
                        }
                    } else { //last year
                        $month_temp = 12;
                        if ($current_month == 1) {
                            $month_temp = 11;
                        }
                        if ($surplus_study_last_year != 0) {
                            if ($month_temp != 12) {
                                echo '0/0';
                            } else {
                                echo $target_study_last_year . '/' . $target_study_last_year;
                            }
                        } else {
                            echo ($target_study_last_year / 12) . '/' . ($target_study_last_year / 12) * $month_temp;
                        }
                    }
                    ?>
	                </span>
                <span style="display: block"><!--actual-->
		                <?php
                    if ($current_month > 2) {
                        $count_study_current_year_last_month_2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month - 2)
                            ->count();
                        $study_current_year += $count_study_current_year_last_month_2;
                        if ($current_month - 2 > 1) {
                            $count_study_current_year_last_month_21 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                                ->whereYear('date_execution', $current_year)->whereMonth('date_execution', '<=', $current_month - (2 + 1))
                                ->count();
                            $study_current_year += $count_study_current_year_last_month_21;
                        }
                        echo $count_study_current_year_last_month_2 . '/' . $study_current_year;
                    } else { //last year
                        $month_temp = ($current_month == 1) ? 11 : 12;
                        $count_study_last_year_last_month_2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', ($current_year - 1))->whereMonth('date_execution', $month_temp)
                            ->count();

                        $count_study_last_year_last_month_21 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', ($current_year - 1))->whereMonth('date_execution', '<=', ($month_temp - 1))
                            ->count();

                        echo $count_study_last_year_last_month_2 . '/' . ($count_study_last_year_last_month_2 + $count_study_last_year_last_month_21);
                    }
                    ?>
	                </span>
            </td>
            <td><!--(current month - 1)-->
                <span class="text-primary d-block"><!--plan-->
		                <?php
                    if ($current_month > 1) {
                        if ($surplus_study_current_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_study_current_year / 12) . '/' . ($target_study_current_year / 12) * ($current_month - 1);
                        }
                    } else { //last year
                        $month_temp = 12;
                        if ($surplus_study_last_year != 0) {
                            echo $target_study_last_year . '/' . $target_study_last_year;
                        } else {
                            echo ($target_study_last_year / 12) . '/' . ($target_study_last_year / 12) * $month_temp;
                        }
                    }
                    ?>
	                </span>
                <span style="display: block"><!--actual-->
		                <?php
                    if ($current_month > 1) {
                        $count_study_current_year_last_month_1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month - 1)
                            ->count();
                        $study_current_year += $count_study_current_year_last_month_1;
                        echo $count_study_current_year_last_month_1 . '/' . $study_current_year;
                    } else { //last year
                        $month_temp = 12;
                        $count_study_last_year_last_month_1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', ($current_year - 1))->whereMonth('date_execution', $month_temp)
                            ->count();

                        $count_study_last_year_last_month_11 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', ($current_year - 1))->whereMonth('date_execution', '<=', ($month_temp - 1))
                            ->count();

                        echo $count_study_last_year_last_month_1 . '/' . ($count_study_last_year_last_month_1 + $count_study_last_year_last_month_11);
                    }
                    ?>
	                </span>
            </td>
            <td><!--(current month)-->
                <span class="text-primary d-block"><!--plan-->
		                <?php
                    if ($surplus_study_current_year != 0) {
                        if ($current_month != 12) {
                            echo '0/0';
                        } else {
                            echo $target_study_current_year . '/' . $target_study_current_year;
                        }
                    } else {
                        $int = $target_study_current_year / 12;
                        echo ($target_study_current_year / 12) . '/' . ($target_study_current_year / 12) * $current_month;
                    }
                    ?>
	                </span>
                <span style="display: block"><!--actual-->
		                <?php
                    $count_study_current = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                        ->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month)
                        ->count();
                    $study_current_year += $count_study_current;
                    echo $count_study_current . '/' . $study_current_year;
                    ?>
	                </span>
            </td>
            <td><!--(current month + 1)-->
                <span class="text-primary d-block"><!--plan-->
		                <?php
                    if ($current_month < 12) { //current year
                        if ($surplus_study_current_year != 0) {
                            if (($current_month + 1) != 12) {
                                echo '0/0';
                            } else {
                                echo $target_study_current_year . '/' . $target_study_current_year;
                            }
                        } else {
                            $int = $target_study_current_year / 12;
                            echo ($target_study_current_year / 12) . '/' . ($target_study_current_year / 12) * ($current_month + 1);
                        }
                    } else { //next year 1
                        if ($surplus_study_next_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_study_next_year / 12) . '/' . ($target_study_next_year / 12);
                        }
                    }
                    ?>
	                </span>
                <span style="display: block"><!--actual-->
		            <?php
                    if ($current_month < 12) { //current year
                        $count_complete_current_year_next_month_1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month + 1)
                            ->count();
                        $study_current_year += $count_complete_current_year_next_month_1;

                        echo $count_complete_current_year_next_month_1 . '/' . $study_current_year;
                    } else { //next year 1
                        $count_complete_next_year_1 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', $current_year + 1)->whereMonth('date_execution', 1)
                            ->count();
                        $study_next_year += $count_complete_next_year_1;

                        echo $count_complete_next_year_1 . '/' . $study_next_year;
                    }
                    ?>
	                </span>
            </td>
            <td><!--(current month + 2)-->
                <span class="text-primary d-block"><!--plan-->
		                <?php
                    if ($current_month < 11) { //current year
                        if ($surplus_study_current_year != 0) {
                            if (($current_month + 2) != 12) {
                                echo '0/0';
                            } else {
                                echo $target_study_current_year . '/' . $target_study_current_year;
                            }
                        } else {
                            $int = $target_study_current_year / 12;
                            echo ($target_study_current_year / 12) . '/' . ($target_study_current_year / 12) * ($current_month + 2);
                        }
                    } else { //next year 2
                        $month_temp = 1;
                        if ($current_month == 12) {
                            $month_temp = 2;
                        }
                        if ($surplus_study_next_year != 0) {
                            echo '0/0';
                        } else {
                            echo ($target_study_next_year / 12) . '/' . ($target_study_next_year / 12) * $month_temp;
                        }
                    }
                    ?>
	                </span>
                <span style="display: block"><!--actual-->
		                <?php
                    if ($current_month < 11) {  //current year
                        $count_complete_current_year_next_month_2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', $current_year)->whereMonth('date_execution', $current_month + 2)
                            ->count();
                        $study_current_year += $count_complete_current_year_next_month_2;
                        echo $count_complete_current_year_next_month_2 . '/' . $study_current_year;
                    } else { //next year
                        $count_complete_next_year_2 = DB::table('activities')->where('circle_id', session('circle.id'))->where('activity_category', App\Enums\Activity::STUDY_GROUP)
                            ->whereYear('date_execution', $current_year + 1)->whereMonth('date_execution', ($current_month == 11) ? 1 : 2)
                            ->count();
                        $study_next_year += $count_complete_next_year_2;
                        echo $count_complete_next_year_2 . '/' . $study_next_year;
                    }
                    ?>
	                </span>
            </td>
        </tr>
        </tbody>
    </table>
</div>
