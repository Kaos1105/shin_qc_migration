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
        <a class="btn btn-add" id="btn-delete" disabled>削除</a>
        <a class="btn btn-back" href="{{ route('circle.show', $circle->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('MemberController@store') }}">
            @csrf
            <input type="hidden" value="{{ $circle->id }}" name="circle_id" />
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">メンバー</td>
                    <td>
                        <select name="user_id" class="qc-form-select">
                            @foreach($user as $item)
                                <option value="{{ $item->id }}">@if(isset($item->position)) ({{ $item->position }}) @endif {{ $item->name }}</option>
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
                                       checked value="1"><span>メンバー</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="is_leader2" name="is_leader"
                                       value="2"><span>リーダー</span>
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
                        <input id="department" type="text" class="qc-form-input qc-form-input-50" value="{{ old('department') }}" name="department">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">役割</td>
                    <td>
                        <input id="classification" type="text" class="qc-form-input qc-form-input-50" value="{{ old('classification') }}" name="classification">
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input id="display_order" type="number" class="qc-form-input qc-form-input-10" value="{{ old('display_order', '9999') }}" name="display_order">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note" >{{ old('note') }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label" >
                                <input type="radio" class="form-check-input" id="statistic_classification1" name="statistic_classification"
                                       value="1"><span>集計しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="statistic_classification2" name="statistic_classification"
                                       checked value="2"><span>集計対象とする</span>
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
                                       value="1"><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2" name="use_classification"
                                       checked value="2"><span>使用する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span></span>
                        <input id="updated_at" type="hidden" name="updated_at">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit()
        });
    </script>
@endsection
