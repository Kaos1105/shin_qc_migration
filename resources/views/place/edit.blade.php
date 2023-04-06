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
        Breadcrumbs::for('edit', function ($trail) {
            $trail->parent('place');
            $trail->push('修正');
        })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ route('place.show', $place->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($place, array('route' => array('place.update', $place->id), 'method' => 'PUT', 'id' => 'form-register')) }}
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($place)? \App\Enums\Common::show_id($place['id']) : null }}</span>
                        <input type="hidden" name="id" value="{{ old('id', isset($place)?$place['id']:null)}}" />
                    </td>
                </tr>
                <tr>
                    <td class="td-first">職場名</td>
                    <td>
                        <input id="place_name" type="text" class="qc-form-input input-m" name="place_name" value="{{ old('place_name', isset($place)?$place['place_name']:null)}}" required>
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
                                <option value="{{ $item->id }}" @if($place->department_id == $item->id) selected @endif > {{ $item->department_name }}</option>
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
                                <option value="{{ $item->id }}" @if($place->user_id == $item->id) selected @endif >@if(isset($item->position)) ({{ $item->position }}) @endif {{ $item->name }}</option>
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
                        <input id="display_order" type="number" class="qc-form-input qc-form-input-10" value="{{ old('display_order', isset($place)?$place['display_order']:null)}}" name="display_order">
                        <span class="qc-form-required">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">備考</td>
                    <td>
                        <textarea id="note" type="text"  class="qc-form-area qc-form-input-50" name="note" >{{ old('note', isset($place)?$place['note']:null)}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">集計区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label" >
                                <input type="radio" class="form-check-input" id="statistic_classification1" name="statistic_classification"
                                       @if($place->statistic_classification == 1) checked @endif value="1"><span>集計しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="statistic_classification2" name="statistic_classification"
                                       @if($place->statistic_classification == 2) checked @endif  value="2"><span>集計対象とする</span>
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
                                       @if($place->use_classification == 1) checked @endif value="1"><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2" name="use_classification"
                                       @if($place->use_classification == 2) checked @endif  value="2"><span>使用する</span>
                            </label>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">新規登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($place->created_at)) }} {{ $user_created_by_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span>{{ date("Y/m/d H:i:s", strtotime($place->updated_at)) }} {{ $user_updated_by }}</span>
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
