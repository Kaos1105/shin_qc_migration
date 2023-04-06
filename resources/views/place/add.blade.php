@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
                $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('place', function ($trail) {
            $trail->parent('toppage');
            $trail->push('職場マスタ',route('place.index'));
        }),
        Breadcrumbs::for('add', function ($trail) {
            $trail->parent('place');
            $trail->push('新規登録', route('place.create'));
        })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <button class="btn btn-back" onclick="window.history.back()">戻る</button>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" action="{{ action('PlaceController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">職場名</td>
                    <td>
                        <input type="text" class="qc-form-input input-m" name="place_name" value="{{ old('place_name') }}" required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('place_name'))
                            <span class="qc-form-error-message">{{ $errors->first('place_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">所属部門</td>
                    <td>
                        <select name="department_id" class="qc-form-select">
                            @foreach($department as $item)
                                <option value="{{ $item->id }}"> {{ $item->department_name }}</option>
                            @endforeach
                        </select>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('department_id'))
                            <span class="qc-form-error-message">{{ $errors->first('department_id') }}</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="td-first">職場世話人</td>
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
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input id="display_order" type="number" class="qc-form-input qc-form-input-10" value="{{ old('display_order', \App\Enums\StaticConfig::$Default_Display_Order) }}" name="display_order">
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
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span></span>
                        <input id="created_at" type="hidden" name="created_at">
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
