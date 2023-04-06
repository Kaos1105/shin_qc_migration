@extends('layouts.app')

@section('breadcrumbs')
    {{
        Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
        }),
        Breadcrumbs::for('circle', function ($trail) {
            $trail->parent('toppage');
            $trail->push('サークル',route('circle.index'));
        }),
        Breadcrumbs::for('show', function ($trail, $circle_id) {
            $trail->parent('circle');
            $trail->push('サークル情報', route('circle.show',$circle_id));
        }),
        Breadcrumbs::for('edit', function ($trail, $circle_id) {
            $trail->parent('show', $circle_id);
            $trail->push('修正');
        })
    }}

    {{ Breadcrumbs::render('edit', $circle->id) }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <a class="btn btn-add" id="btn-register">登録</a>
        <a class="btn btn-back" href="{{ URL::to('circle/'.$circle->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{ Form::model($circle, array('route' => array('circle.update', $circle->id), 'method' => 'PUT', 'id' => 'form-register')) }}
        @csrf
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($circle)? \App\Enums\Common::show_id($circle['id']) : null }}</span>
                    <input type="hidden" name="id" value="{{ old('id', isset($circle)?$circle['id'] : null)}}"/>
                </td>
            </tr>
            <tr>
                <td class="td-first">登録番号</td>
                <td>
                    <input id="circle_code" type="text" class="qc-form-input input-s" name="circle_code"
                           value="{{ old('circle_code', isset($circle)?$circle['circle_code']:null)}}">
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('circle_code'))
                        <span class="qc-form-error-message">{{ $errors->first('circle_code') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">所属職場</td>
                <td>
                    <select name="place_id" class="qc-form-select">
                        @foreach($place as $item)
                            <option value="{{ $item->id }}"
                                    @if($circle->place_id == $item->id) selected @endif>{{$item->department_name}} {{ $item->place_name }}</option>
                        @endforeach
                    </select>
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('place_id'))
                        <span class="qc-form-error-message">{{ $errors->first('place_id') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル名</td>
                <td>
                    <input id="circle_name" type="text" class="qc-form-input qc-form-input-50" name="circle_name"
                           value="{{ old('circle_name', isset($circle)?$circle['circle_name']:null)}}">
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('circle_name'))
                        <span class="qc-form-error-message">{{ $errors->first('circle_name') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">サークル推進者</td>
                <td>
                    <select name="user_id" class="qc-form-select">
                        @foreach($user as $item)
                            <option value="{{ $item->id }}"
                                    @if($circle->user_id == $item->id) selected @endif>@if($item->position)
                                    ({{ $item->position }}) @endif {{ $item->name }}</option>
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
                    <input id="display_order" type="number" class="qc-form-input qc-form-input-10"
                           value="{{ old('display_order', isset($circle)?$circle['display_order']:null)}}"
                           name="display_order">
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('display_order'))
                        <span class="qc-form-error-message">{{ $errors->first('display_order') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">登録日</td>
                <td>
                    <input id="date_register" type="text" class="qc-form-input input-s"
                           value="{{ old('date_register', isset($circle)?date("Y/m/d", strtotime($circle['date_register'])):null)}}"
                           name="date_register" autocomplete="off">
                    <img id="date-icon-1" class="date-selector-icon" src="{{asset('/images/calendar_24px.png')}}" alt="date-icon" >
                    <span class="qc-form-required">必須</span>
                    @if ($errors->has('date_register'))
                        <span class="qc-form-error-message">{{ $errors->first('date_register') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">最終活動日</td>
                <td>
                    <span>{{ isset($activity_lastest)? date("Y/m/d", strtotime($activity_lastest->date_execution)) :null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">備考</td>
                <td>
                    <textarea id="note" type="text" class="qc-form-area qc-form-input-50"
                              name="note">{{ old('note', isset($circle)?$circle['note']:null)}}</textarea>
                </td>
            </tr>
            <tr>
                <td class="td-first">集計区分</td>
                <td>
                    <div class="qc-form-radio-group">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="statistic_classification1"
                                   name="statistic_classification"
                                   @if($circle->statistic_classification == 1) checked
                                   @endif value="1"><span>集計しない</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="statistic_classification2"
                                   name="statistic_classification"
                                   @if($circle->statistic_classification == 2) checked
                                   @endif  value="2"><span>集計対象とする</span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-first">使用区分</td>
                <td>
                    <div class="qc-form-radio-group">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="use_classification1"
                                   name="use_classification"
                                   @if($circle->use_classification == 1) checked @endif value="1"><span>使用しない</span>
                        </label>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="use_classification2"
                                   name="use_classification"
                                   @if($circle->use_classification == 2) checked @endif  value="2"><span>使用する</span>
                        </label>

                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-first">新規登録日</td>
                <td>
                    <span>{{ date("Y/m/d H:i:s", strtotime($circle->created_at)) }} {{ $user_created_by_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">更新登録日</td>
                <td>
                    <span>{{ date("Y/m/d H:i:s", strtotime($circle->updated_at)) }} {{ $user_updated_by }}</span>
                </td>
            </tr>
        </table>
        {{ Form::close() }}
    </div>
    <script>
        $(document).ready(function () {
            $("#date_register").datepicker(options);
            $('#date-icon-1').click(function () {
                $('#date_register').focus();
            });
        });
        $("#btn-register").click(function () {
            $("#form-register").submit()
        });
    </script>
@endsection
