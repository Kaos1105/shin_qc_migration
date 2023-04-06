@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('circlelist', function ($trail) {
            $trail->parent('toppage');
            $trail->push('サークル一覧',route('circle.index'));
        }),
        Breadcrumbs::for('circle', function ($trail, $circle_name, $circle_id) {
            $trail->parent('circlelist');
            $trail->push('サークル情報（'.$circle_name.'）',route('circle.show', [$circle_id]));
        }),
        Breadcrumbs::for('member', function ($trail, $circle_name, $circle_id) {
            $trail->parent('circle', $circle_name, $circle_id);
            $trail->push('メンバー追加／修正');
        })
    }}

    {{ Breadcrumbs::render('member', $circle->circle_name, $circle->id) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-add" id="btn-delete">削除</a>
        <a class="btn btn-back" href="{{ route('circle.show', $circle->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
         {{ Form::model($member, array('route' => array('member.update', $member->id), 'method' => 'PUT', 'id' => 'form-register')) }}
            @csrf
            <input type="hidden" value="{{ $circle->id }}" name="circle_id" />
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($member)? \App\Enums\Common::show_id($member['id']):null }}</span>
                        <input type="hidden" name="id" value="{{ old('id', isset($member)?$member['id']:null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">メンバー</td>
                    <td>
                        <select name="user_id" class="qc-form-select">
                            @foreach($user as $item)
                                <option value="{{ $item->id }}" @if($member->user_id == $item->id) selected @endif >@if(isset($item->position)) ({{ $item->position }}) @endif {{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('user_id'))
                            <span class="qc-form-error-message">{{ $errors->first('user_id') }}</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="td-first">リーダーフラグ</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label" >
                                <input type="radio" class="form-check-input" id="is_leader1" name="is_leader"
                                       @if($member->is_leader == 1) checked @endif value="1"><span>メンバー</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="is_leader2" name="is_leader"
                                       @if($member->is_leader == 2) checked @endif value="2"><span>リーダー</span>
                            </label>
                            <span class="qc-form-required">必須</span>
                            @if ($errors->has('is_leader'))
                                <span class="qc-form-error-message">{{ $errors->first('is_leader') }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">所属グループ</td>
                    <td>
                        <input id="department" type="text" class="qc-form-input qc-form-input-50" value="{{ old('department', isset($member)?$member['department']:null)}}" name="department">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">役割</td>
                    <td>
                        <input id="classification" type="text" class="qc-form-input qc-form-input-50" value="{{ old('classification', isset($member)?$member['classification']:null)}}" name="classification">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input id="display_order" type="number" class="qc-form-input qc-form-input-10" value="{{ old('display_order', isset($member)?$member['display_order']:null)}}" name="display_order">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note" >{{ old('note', isset($member)?$member['note']:null)}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label" >
                                <input type="radio" class="form-check-input" id="statistic_classification1" name="statistic_classification"
                                       @if($member->statistic_classification == 1) checked @endif value="1"><span>集計しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="statistic_classification2" name="statistic_classification"
                                       @if($member->statistic_classification == 2) checked @endif  value="2"><span>集計対象とする</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1" name="use_classification"
                                       @if($member->use_classification == 1) checked @endif value="1"><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2" name="use_classification"
                                       @if($member->use_classification == 2) checked @endif  value="2"><span>使用する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span>{{ $member->updated_at }} {{ $user_updated_by }}</span>
                    </td>
                </tr>
            </table>
        {{ Form::close() }}
        {!! Form::open(['id'=> 'form-delete','method' => 'Delete', 'route' => ['member.destroy', $member->id]]) !!}
        {!! Form::close() !!}
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit()
        });
        $("#btn-delete").click(function(){
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Member }}");
            if (r == true) {
                $("#form-delete").submit()
            }
        });
    </script>
@endsection
