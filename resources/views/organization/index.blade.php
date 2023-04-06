@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('organization', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ＱＣサークル組織図');
        })
    }}

    {{ Breadcrumbs::render('organization') }}
@endsection
@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-back" href="{{ route('toppageoffice') }}">戻る</a>
    </div>
    <div class="is3D container-fluid container bottom-fix" style="margin-bottom: 3rem">
        <div class="org-container bottom-fix position-relative" style="margin-bottom: 3rem">
            <div class="org-group1 group-gutter readjust node-color-1 vertical-text general">推進委員</div>

            <div class="org-group2 group-gutter readjust node-color-2 vertical-text general">部門</div>

            <div class="org-group3 group-gutter readjust node-color-3 vertical-text general">職場</div>

            <div class="org-group4 group-gutter readjust node-color-4 vertical-text general"
                 style="margin-bottom: 0">サークル
            </div>

            <div class="org-area2" id="area2"></div>

            <div class="org-area1" id="area1"></div>

            <div id="org-side-branch" class="org-side"></div>

            <div class="cnt-2 org-connection-horizontal-bottom" id="cnt2"></div>
        </div>
    </div>
@endsection

@section('scripts')

    <script type="text/javascript" src="{{ asset('js/loader.js') }}"></script>
    <script>
        var node = [];
        const color1 = '#ffcc99',
            color2 = '#ffff99',
            color3 = '#d2d2f4',
            color4 = '#c2fff0';

        const level2 = '部門責任者',
            level3 = '部門世話人',
            level4 = '職場世話人',
            level5 = 'サークル推進者',
            level6 = 'リーダー',
            levelOther = "";

        function addNewNode(level, ID, link, Name, parentID, toolTip, depLink, depName) {

            var nodeColor = "";
            var position = "";
            var bossLevel = 0;

            if (level === undefined || ID === undefined) {
                console.log('undefined node');
            } else if (level === 1) {
                console.log('cannot make level 1 node');
            } else if (level === 2) {
                nodeColor = color2;
                position = level2;
                bossLevel = 1;
            } else if (level === 3) {
                nodeColor = color2;
                position = level3;
                bossLevel = 2;
            } else if (level === 4) {
                nodeColor = color3;
                position = level4;
                bossLevel = 3;
            } else if (level === 5) {
                nodeColor = color4;
                position = level5;
                bossLevel = 4;
            } else if (level === 6) {
                nodeColor = color4;
                position = level6;
                bossLevel = 5;
            } else {
                nodeColor = color4;
                position = "";
                bossLevel = level - 1;
            }

            var nodeLocation = level.toString() + '-' + ID;
            var boss = bossLevel + '-' + parentID;

            var dataRow = [nodeLocation, position, link, Name, boss, toolTip, nodeColor, depLink, depName];
            node.push(dataRow); //parentID is the NameOrUniqueID of the person directly above in the chart.
        }

        @if(isset($department))
        @foreach($department as $department_item)
        <?php
        $bs = \App\User::find($department_item->bs_id);
        $sw = \App\User::find($department_item->sw_id);
        $place_list = DB::table('places')->where('use_classification', 2)->where('department_id', $department_item->id)->get();
        ?>
        addNewNode(2, "departmentbs{{$department_item->id}}", '{{ URL::to('user/'.$department_item->bs_id.'?callback=yes') }}', '{{ $bs->name }}', 'theBoss', "", "{{ URL::to('department/'.$department_item->id.'?callback=yes') }}", "{{ $department_item->department_name }}");
        addNewNode(3, "departmentsw{{$department_item->id}}", "{{ URL::to('user/'.$department_item->sw_id.'?callback=yes') }}", "{{ $sw->name }}", "departmentbs{{$department_item->id}}", "");
        @if(isset($place_list))
        @foreach($place_list as $place_list_item)
        <?php
        $place_user = \App\User::find($place_list_item->user_id);
        $circle_list = DB::table('circles')->where('place_id', $place_list_item->id)->where('use_classification', \App\Enums\UseClassificationEnum::USES)->get();
        ?>
        addNewNode(4, "place{{$place_list_item->id}}", "{{ URL::to('user/'.$place_list_item->user_id.'?callback=yes') }}", "{{ $place_user->name }}", "departmentsw{{$department_item->id}}", "", "{{ URL::to('place/'.$place_list_item->id.'?callback=yes') }}", "{{ $place_list_item->place_name }}");
        @if(isset($circle_list))
        @foreach($circle_list as $circle_list_item)
        <?php
        $circle_user = \App\User::find($circle_list_item->user_id);
        $leader = DB::table('members')->where('circle_id', $circle_list_item->id)->where('is_leader', 2)->first();
        $member_list = DB::table('members')->where('circle_id', $circle_list_item->id)->where('is_leader', 1)->get();
        ?>
        addNewNode(5, "circle{{$circle_list_item->id}}", "{{ URL::to('user/'.$circle_list_item->user_id.'?callback=yes') }}", "{{ $circle_user->name }}", "place{{$place_list_item->id}}", "", "{{ URL::to('circle/'.$circle_list_item->id.'?callback=yes') }}", "{{ $circle_list_item->circle_name }}");
        @if(isset($leader))
        <?php $leader_user = \App\User::find($leader->user_id); ?>
        addNewNode(6, "memberleader{{$leader->id}}", "{{ URL::to('user/'.$leader->user_id.'?callback=yes') }}", "{{ $leader_user->name }}", "circle{{$circle_list_item->id}}", "");
        @if(isset($member_list))
        <?php $member_stt = 7; ?>
        @for($i = 0; $i < count($member_list); $i++)
        <?php $member_user = \App\User::find($member_list[$i]->user_id); ?>
        addNewNode({{$member_stt++}}, "member{{$member_list[$i]->id}}", "{{ URL::to('user/'.$member_list[$i]->user_id.'?callback=yes') }}", "{{ $member_user->name }}", @if($i == 0) "memberleader{{$leader->id}}"
        @else "member{{$member_list[$i-1]->id}}" @endif, ""
        )
        ;
        @endfor
        @endif
        @endif
        @endforeach
        @endif
        @endforeach
        @endif
        @endforeach
        @endif
    </script>

    <script type="text/javascript">
        google.charts.load('current', {packages: ["orgchart"]});
        google.charts.setOnLoadCallback(drawArea1);
        google.charts.setOnLoadCallback(drawArea2);
        google.charts.setOnLoadCallback(drawGroupSide);

        function drawArea1() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'NameID'); //Name and ID of node, has to be unique
            data.addColumn('string', 'BossNameID'); // Name of parent node
            data.addColumn('string', 'ToolTip'); // optional

            data.addRows([
                [{
                    v: '1-theBoss',
                    f: '<div id="the-boss">推進責任者<div>@if(isset($promotion_officer)) <a href="{{ URL::to('user/'.$promotion_officer->id.'?callback=yes') }}">{{ $promotion_officer->name }}</a>@endif'
                }, '', '']
                //[{v:'1-theBoss', f:'<div></div><a href="http://google.com"></a>'}, '1-biggestBoss', ''],
            ]);
            for (var i = 0; i < node.length; i++) {
                if (node[i][7] || node[i][8]) {
                    data.addRows([
                        [{
                            v: node[i][0],
                            f: '<a href=' + node[i][7] + '>' + node[i][8] + '</a><div>' + node[i][1] + '<div><a href=' + node[i][2] + '>' + node[i][3] + '</a>'
                        },
                            node[i][4], node[i][5]],
                    ]);
                    data.setRowProperty(i + 1, 'style', 'background-color: ' + node[i][6]);
                } else {
                    data.addRows([
                        [{
                            v: node[i][0],
                            f: '<div>' + node[i][1] + '<div><a href=' + node[i][2] + '>' + node[i][3] + '</a>'
                        },
                            node[i][4], node[i][5]],
                    ]);
                    data.setRowProperty(i + 1, 'style', 'background-color: ' + node[i][6]);
                }
            }
            // Create the chart.
            var group = new google.visualization.OrgChart(document.getElementById('area1'));
            data.setRowProperty(0, 'style', 'background-color:' + color1);
            //data.setRowProperty( 1, 'style', 'box-shadow: none; border-radius: 0; background: linear-gradient(to right, #F8FAFC 0%, #F8FAFC 49.1%, #000 49.1%, #000 50.3%, transparent 50.3%, transparent 100%);');
            google.visualization.events.addListener(group, 'ready', afterDraw);

            group.draw(data, {
                allowHtml: true,
                nodeClass: "org-node",
                selectedNodeClass: 'non-select'
            });
        }

        function drawArea2() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            // For each orgchart box, provide the name, manager, and tooltip to show.
            data.addRows([
                [{
                    v: 'level1',
                    f: '<div>推進委員<div>@if(isset($promotion_committee))<a href="{{ URL::to('user/'.$promotion_committee->id.'?callback=yes') }}">{{ $promotion_committee->name }}</a>@endif'
                },
                    '', ''],
            ]);
            // Create the chart.
            var group = new google.visualization.OrgChart(document.getElementById('area2'));
            // Draw the chart, setting the allowHtml option to true for the tooltips.
            group.draw(data, {
                allowHtml: true,
                nodeClass: "org-node node-color-1",
                selectedNodeClass: 'non-select'
            });
        }

        function drawGroupSide() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            // For each orgchart box, provide the name, manager, and tooltip to show.
            data.addRows([
                [{
                    v: 'level1',
                    f: '<div>事務局長<div>@if(isset($executive_director))<a href="{{ URL::to('user/'.$executive_director->id.'?callback=yes') }}">{{ $executive_director->name }}</a>@endif'
                },
                    '', ''],
                    @if(isset($office_worker))
                    @for($i = 0; $i < count($office_worker); $i++)
                [{
                    v: 'level{{ $office_worker[$i]->id }}',
                    f: '<div>事務局員<div><a href="{{ URL::to('user/'.$office_worker[$i]->id.'?callback=yes') }}">{{ $office_worker[$i]->name }}</a>'
                }, @if($i == 0) 'level1' @else 'level{{ $office_worker[$i-1]->id }}' @endif, ''],
                @endfor
                @endif
            ]);
            // Create the chart.
            var group = new google.visualization.OrgChart(document.getElementById('org-side-branch'));
            // Draw the chart, setting the allowHtml option to true for the tooltips.
            group.draw(data, {
                allowHtml: true,
                nodeClass: "org-node node-color-1",
                selectedNodeClass: 'non-select'
            });
        }

    </script>
    <script>
        function afterDraw() {
            //let d = $("#the-boss").offsetParent().width();
            let d = $("#the-boss").parent().position();
            let style1 = {
                position: 'absolute',
                top: 0,
                left: d.left + 250,
                webkitTransform: 'translate(-200px, 0)'
            };
            let style2 = {
                marginRight: d.left*0.2
            };

            $('#area2').css(style1);
            $('#cnt2').css(style2);
            $('#area1 table tbody tr:nth-child(2) td:first-child').css('background-color', '#F8FAFC');
            $('#area1 table tbody tr:nth-child(2) td:nth-child(3)').css("background-image", "linear-gradient(to right, transparent 0, transparent 100px, #F8FAFC 100px, #F8FAFC)");
        }

    </script>
@endsection