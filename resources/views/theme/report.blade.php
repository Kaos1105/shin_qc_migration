@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                    $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('テーマ登録',URL::to('theme'));
        }),
        Breadcrumbs::for('report', function ($trail) {
            $trail->parent('list');
            $trail->push('活動報告書のダウンロード');
        })
    }}
    {{ Breadcrumbs::render('report') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-back" id="export">ダウンロード</button>
        <a class="btn btn-back" href="{{ URL::to('theme/'.$theme->id) }}">戻る</a>
    </div>
    <div class="container-fluid bottom-fix">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">テーマ名</td>
                <td>
                    <span>{{$theme->theme_name}}</span>
                    <input type="hidden" id="theme_id" value="{{$theme->id}}">
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル①</td>
                <td>
                    <span>{{$circle1->circle_code.' | '.$circle1->department_name.' | '.$circle1->place_name.'  | '.$circle1->circle_name.' | '.$circle1->leader.' | '.$circle1->numMem}}</span>
                    <input type="hidden" id="circle1" value="{{$circle1->id}}">
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル②</td>
                <td>
                    <select class="qc-form-input input-l" name="circle2" id="circle2" onchange="gather()">
                        <option value="0">なし</option>
                        @foreach($circle_other as $item)
                            <option value="{{$item->id}}">{{$item->circle_code.' | '.$item->department_name.' | '.$item->place_name.'  | '.$item->circle_name.' | '.$item->leader.' | '.$item->numMem}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル③</td>
                <td>
                    <select class="qc-form-input input-l" name="circle3" id="circle3" onchange="gather()">
                        <option value="0">なし</option>
                        @foreach($circle_other as $item2)
                            <option value="{{$item2->id}}">{{$item2->circle_code.' | '.$item2->department_name.' | '.$item2->place_name.'  | '.$item2->circle_name.' | '.$item2->leader.' | '.$item2->numMem}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="td-first">掲示板データ</td>
                <td>
                    <select class="qc-form-input input-l" name="thread" id="thread" onchange="gather()">
                        <option value="0">なし</option>
                        @foreach($thread as $thread_item)
                            <option value="{{$thread_item->id}}">{{$thread_item->category_name.' | '.$thread_item->thread_name.' | '}}{{date_format(date_create($thread_item->created_at),"Y/m/d")}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <script>
        var url = "{{ url('/') }}";
        var keys = keys = {
            theme: $('#theme_id').val(),
            circle1: $('#circle1').val(),
            circle2: $('#circle2').val(),
            circle3: $('#circle3').val(),
            thread: $('#thread').val()
        };
        function gather() {
            keys = {
                theme: $('#theme_id').val(),
                circle1: $('#circle1').val(),
                circle2: $('#circle2').val(),
                circle3: $('#circle3').val(),
                thread: $('#thread').val()
            }
        }
        var formData = new FormData();
        formData.append('_token', '{{csrf_token()}}');

        $('#export').on('click', function () {
            $.ajax({
                url: url + "/export-input",
                type: 'POST',
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                success: function () {
                    let target = url + "/test-export?theme=" + keys.theme + "&circle1=" +
                        keys.circle1 + "&circle2=" + keys.circle2 + "&circle3=" + keys.circle3 +
                        "&thread=" + keys.thread;
                    window.open(target, '_blank');
                },
                error: function () {
                    alert("Bad submit");
                }
            });
        });
    </script>
@endsection
