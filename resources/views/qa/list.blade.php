@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('index', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ＱＡ画面登録（一覧）');
        })
    }}

    {{ Breadcrumbs::render('index') }}

@endsection

@section('content')
    <div class="float-right btn-area-list">
        <div class="filter-has-long-thing">
            <select class="btn btn-back filter-btn" style="width: 213px; margin-bottom: 0" onchange="changeStoryFilter(this)">
                <option value="all"
                        @if(isset($_GET['storyFilter']) && $_GET['storyFilter'] == 'all' || !isset($_GET['storyFilter'])) selected @endif>
                    全て
                </option>
                @foreach($story_list as $story_list_item)
                    <option value="{{$story_list_item->storyId}}"
                            @if(isset($_GET['storyFilter']) && $_GET['storyFilter'] == $story_list_item->storyId) selected @endif>{{$story_list_item->story_name}}</option>
                @endforeach
            </select>
            <select class="btn btn-back filter-btn" onchange="changeFilter(this)">
                <option value="all"
                        @if(isset($_GET['filter']) && $_GET['filter'] == 'all' || !isset($_GET['filter'])) selected @endif>
                    全て
                </option>
                <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) ) selected @endif>使用中</option>
                <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
            </select>
        </div>
        <div class="float-right">
            <a class="btn btn-add" href="{{ route('qa.getAdd') }}">追加</a>
            <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
        </div>
    </div>

    <div class="container-fluid">
        @include('common.paginate')
        <table id="myTable" class="table-frame">
            <thead>
            <tr>
                <th scope="col" style="width: 3%">No</th>
                <th scope="col" style="width: 15%">ストーリー区分</th>
                <th scope="col" style="width: 20%">ストーリー名</th>
                <th scope="col" style="width: 10%">画面番号</th>
                <th scope="col" style="width: 15%">画面タイトル</th>
                <th scope="col" style="width: 7%" class="text-center">表示順</th>
                <th scope="col" style="width: 15%">回答</th>
                <th scope="col" style="width: 15%">最終更新</th>
            </tr>
            </thead>
            <tbody>
            <?php $stt = ($paginate->currentPage() - 1) * 20; $qa = $paginate ?>
            @for ($i = 0; $i < sizeof($qa); $i++)
                <?php
                $stt++;
                $user_updated_by = DB::table('users')->where('id', $qa[$i]->updated_by)->first();
                ?>
                <tr>
                    <th class="font-weight-normal">{{$stt}}</th>
                    <td class="line-brake-preserve">
                        <span>{{$qa[$i]->story_classification}}</span>
                    </td>
                    <td>
                        <a class="a-black" href="{{URL::to('/story/show/'.$qa[$i]->story_id.'?callback=yes')}}">
                            <span>{{$qa[$i]->story_name}}</span>
                        </a>
                    </td>
                    <td>
                        <a class="a-black font-weight-bold" href="{{ URL::to('/qa/show?holdsort=yes', [$qa[$i]->id]) }}">
                            <span>{{$qa[$i]->screen_id}}</span>
                        </a>
                    </td>
                    <td>
                        <span class="line-brake-preserve">{{$qa[$i]->title}}</span>
                    </td>
                    <td class="text-center">
                        <form id="change_display_order_{{$qa[$i]->display_order}}" method="post"
                              action="change">
                            @csrf
                            <input type="hidden" name="id" value="{{$qa[$i]->id}}"/>
                            <input type="hidden" name="sort_type" value="{{isset($_GET['sortType']) ? $_GET['sortType'] : null}}"/>
                            @if($sortType == "asc")
                                @if($qa[$i]->display_order == $min_display_order)
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @elseif($qa[$i]->display_order == $max_display_order)
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                @else
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @endif
                            @elseif($sortType == 'desc')
                                @if($qa[$i]->display_order == $max_display_order)
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @elseif($qa[$i]->display_order == $min_display_order)
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                @else
                                    <button type="button" class="btn-disp-order disp-order-up" disabled>&#9651;</button>
                                    <button type="button" class="btn-disp-order disp-order-down" disabled>&#9661;</button>
                                @endif
                            @endif
                            <input type="hidden" class="up-down" name="up_down" value=""/>
                        </form>
                    </td>
                    <td>
                        <span class="line-brake-preserve">{{$qa[$i]->answer}}</span>
                    </td>
                    <td>
                        <span>{{date_format(date_create($qa[$i]->updated_at),"Y/m/d")}} {{ isset($user_updated_by)? $user_updated_by->name : ''}} </span>
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
        @include('common.paginate')
    </div>
    <script>
        var columns = ["no", "story_classification", "story_name", "screen_id", "title", "display_order", 'answer',"updated_at"];
        var index_old = 5;
        var view = 'list';
        $(document).ready(sortAndFilter(columns, index_old, view));
        var url = document.location.href;
        var currentUrl;
        if (url.indexOf("storyFilter=") == -1 || url.indexOf("filter=") == -1) {
            if (url.indexOf('page') == -1) {
                currentUrl = url + '?storyFilter=all&filter=all';
            } else {
                currentUrl = url.slice(0, url.indexOf('page=') - 1) + '?storyFilter=all&filter=all';
            }
        } else {
            if (url.indexOf('page') == -1) {
                currentUrl = url;
            } else {
                let page = url.slice(url.indexOf('page='), url.indexOf('&') + 1);
                currentUrl = url.replace(page, "");
            }
        }

        function changeStoryFilter(own) {
            var index = currentUrl.indexOf('storyFilter=all');
            if (index != -1) {
                currentUrl = currentUrl.replace("storyFilter=all", "storyFilter=" + own.value);
            } else {
                index = currentUrl.indexOf('storyFilter=');
                let textReplace = currentUrl.slice(index, url.lastIndexOf('&'));
                currentUrl = currentUrl.replace(textReplace, 'storyFilter=' + own.value);
            }
            window.location.href = currentUrl;
        }

        function changeFilter(own) {
            var index = currentUrl.indexOf('filter=all');
            if (index != -1) {
                currentUrl = currentUrl.replace("filter=all", "filter=" + own.value);
            } else {
                index = currentUrl.indexOf('filter=');
                var textReplace = currentUrl.substr(index, currentUrl.length - index);
                currentUrl = currentUrl.replace(textReplace, 'filter=' + own.value);
            }
            window.location.href = currentUrl;
        }
    </script>
    <script>
        let sort = '{{$sort}}';
            <?php
            $count = \DB::table('qas')->count();
            ?>
        let count = {{$count}};
        $(function () {
            if (count > 1 && (!sort || sort == 'display_order')) {
                $('.btn-disp-order').prop('disabled', false);
            }
        });
    </script>
    <script>
        $(function () {
            $('.disp-order-up').on('click', function () {
                $(this).siblings('.up-down').val('up');
                let this_form = $(this).closest('form');
                this_form.submit();
            });
            $('.disp-order-down').on('click', function () {
                $(this).siblings('.up-down').val('down');
                let this_form = $(this).closest('form');
                this_form.submit();
            });
        });
    </script>
@endsection

