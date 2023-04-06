@extends('layouts.app')

    @section('breadcrumbs')
        {{
            Breadcrumbs::for('toppage', function ($trail) {
                $trail->push('トップページ', URL::to('top-page'));
            }),
            Breadcrumbs::for('user', function ($trail) {
                $trail->parent('toppage');
                $trail->push('利用者マスタ');
            })
        }}
        {{ Breadcrumbs::render('user') }}

    @endsection

    @section('content')
        <div class="float-right btn-area-list">
            <select class="btn btn-back filter-btn" onchange="Filter(this)">
                <option value="0" @if(isset($_GET['filter']) && $_GET['filter'] == 0) selected @endif>全て表示</option>
                <option value="2" @if((isset($_GET['filter']) && $_GET['filter'] == 2) || !isset($_GET['filter'])) selected @endif>使用中</option>
                <option value="1" @if(isset($_GET['filter']) && $_GET['filter'] == 1) selected @endif>未使用</option>
            </select>
            <a class="btn btn-add" href="{{ route('user.create') }}">追加</a>
            <a class="btn btn-back" href="{{ URL::to('top-page') }}">戻る</a>
        </div>
        <div class="container-fluid">
            @include('common.paginate')
            <table id="myTable" class="table-frame dataTable">
                <thead>
                <tr>
                    <th scope="col" style="text-align: center">No</th>
                    <th scope="col">利用者コード</th>
                    <th scope="col">氏名</th>
                    <th scope="col">役割区分</th>
                    <th scope="col">役職</th>
                    <th scope="col">メールアドレス</th>
                    <th scope="col">電話番号</th>
                    <th scope="col">表示順</th>
                    <th scope="col">ログインＩＤ</th>
                </tr>
                </thead>
                <tbody>
                <?php $stt = ($paginate->currentPage() - 1) * 20 + 1; ?>
                @foreach ($paginate as $user)
                    <tr>
                        <td style="padding: 0px; ">{{ $stt++ }}</td>
                        <td>{{$user->user_code}}</td>
                        <td><a class="a-black font-weight-bold" href="{{ URL::to('/user/'.$user->id.'?holdsort=yes')}}">{{$user->name}}</a></td>
                        <td>
                            @if($user->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_OFFICER)
                                <span>推進責任者</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::PROMOTION_COMMITTEE)
                                <span>推進委員</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::DEPARTMENT_MANAGER)
                                <span>部門責任者</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::DEPARTMENT_CARETAKER)
                                <span>部門世話人</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::PLACE_CARETAKER)
                                <span>職場世話人</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::CIRCLE_PROMOTER)
                                <span>サークル推進者</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::MEMBER)
                                <span>メンバー</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::EXECUTIVE_DIRECTOR)
                                <span>事務局長</span>
                            @elseif($user->role_indicator == \App\Enums\RoleIndicatorEnum::OFFICE_WORKER)
                                <span>事務局員</span>
                            @endif
                        </td>
                        <td>{{$user->position}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->display_order}}</td>
                        <td>{{$user->login_id}}</td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            @include('common.paginate')

            <form id="keepSort" style="display: none">
                @csrf
                <input type="hidden" name="master" value="user">
                <input type="hidden" id="sort-keys" name="sort_keys" value="">
            </form>
        </div>

    @endsection


@section('scripts')
    <script>
        var columns = ["no", "user_code", "name", "role_indicator", "position", "email", "phone", "display_order", "login_id"];
        var index_old = 8;
        var view = "user";
        $(document).ready(sortAndFilter(columns, index_old, view));
    </script>
    <script>
        $(function () {
            let url = location.href;
            let sortKeys;
            if (url.indexOf('?') !== -1) {
                sortKeys = url.slice(url.indexOf('?'), url.length);
            } else {
                sortKeys = "?all";
            }
            $('#sort-keys').val(sortKeys);

            $.ajax({
                url: "{{route('keep-sort')}}",
                type: "POST",
                data: $('#keepSort').serialize()
            });
        });
    </script>
@endsection

