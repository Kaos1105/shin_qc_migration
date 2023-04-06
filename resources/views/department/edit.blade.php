@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', URL::to('top-page'));
        }),
        Breadcrumbs::for('department', function ($trail) {
            $trail->parent('toppage');
            $trail->push('部門マスタ', URL::to('department'));
        }),
        Breadcrumbs::for('edit', function ($trail) {
            $trail->parent('department');
            $trail->push('修正');
        })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ URL::to('department/'.$department->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($department, array('route' => array('department.update', $department->id), 'method' => 'PUT', 'id' => 'form-register')) }}
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($department)? \App\Enums\Common::show_id($department['id']) : null }}</span>
                        <input type="hidden" name="id" value="{{ old('id', isset($department)? $department['id'] : null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">部門名</td>
                    <td>
                        <input id="department_name" type="text" class="qc-form-input input-m" name="department_name" value="{{ old('department_name', isset($department)?$department['department_name']:null)}}" required>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('department_name'))
                            <span class="qc-form-error-message">{{ $errors->first('department_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">部門責任者</td>
                    <td>
                        <select name="bs_id" class="qc-form-select">
                            @foreach($department_manager as $item)
                                <option value="{{ $item->id }}" @if($department->bs_id == $item->id) selected @endif >@if(isset($item->position)) ({{ $item->position }}) @endif {{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('bs_id'))
                            <span class="qc-form-error-message">{{ $errors->first('bs_id') }}</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="td-first">部門世話人</td>
                    <td>
                        <select name="sw_id" class="qc-form-select">
                            @foreach($department_caretaker as $item)
                                <option value="{{ $item->id }}" @if($department->sw_id == $item->id) selected @endif >@if(isset($item->position)) ({{ $item->position }}) @endif {{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('sw_id'))
                            <span class="qc-form-error-message">{{ $errors->first('sw_id') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input id="display_order" type="number" class="qc-form-input qc-form-input-10" value="{{ old('display_order', isset($department)?$department['display_order']:null)}}" name="display_order">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note" >{{ old('note', isset($department)?$department['note']:null)}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label" >
                                <input type="radio" class="form-check-input" id="statistic_classification1" name="statistic_classification"
                                       @if($department->statistic_classification == 1) checked @endif value="1"><span>集計しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="statistic_classification2" name="statistic_classification"
                                       @if($department->statistic_classification == 2) checked @endif  value="2"><span>集計対象とする</span>
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
                                       @if($department->use_classification == 1) checked @endif value="1"><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2" name="use_classification"
                                       @if($department->use_classification == 2) checked @endif  value="2"><span>使用する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($department->created_at)) }} {{ $user_created_by_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($department->updated_at)) }} {{ $user_updated_by }}</span>
                    </td>
                </tr>
            </table>
        {{ Form::close() }}
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit()
        });
    </script>
@endsection
