@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ホームページリンク管理',route('homepage.index'));
        }),
         Breadcrumbs::for('add', function ($trail) {
            $trail->parent('list');
            $trail->push('新規登録／複写登録');
        })
    }}

    {{ Breadcrumbs::render('add') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ route('homepage.index') }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" enctype="multipart/form-data" action="{{ action('HomepageController@store') }}">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">リンク区分</td>
                    <td>
                        <input type="text" class="qc-form-input input-m" id="classification" name="classification" value="{{old('classification')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">タイトル</td>
                    <td>
                        <input id="title" type="text" class="qc-form-input qc-form-input-50" name="title" value="{{ old('title') }}" required>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('title'))
                            <span class="qc-form-error-message">{{ $errors->first('title') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">URL</td>
                    <td>
                        <input class="qc-form-input qc-form-input-50" type="text" name="url" id="url" value="{{old('url')}}" />
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('url'))
                            <span class="qc-form-error-message">{{ $errors->first('url') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
                        <textarea class="qc-form-area qc-form-input-50" name="note" >{{ old('note') }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">掲載期間</td>
                    <td>
                        <span>開始</span>
                            <input id='date_timepicker_start' type='text'
                                   class="qc-form-input input-s"
                                   name="date_start" autocomplete="off"
                                   value="{{old('date_start')}}"
                            />
                            <img id="picker-start-icon" class="date-selector-icon" src="{{ asset('images/calendar_24px.png')}}" alt="date" height="24" width="24">

                        <span style="margin-left: 20px">終了</span>
                            <input id='date_timepicker_end' type='text'
                                   class="qc-form-input input-s"
                                   name="date_end"
                                   autocomplete="off"
                                   value="{{old('date_end')}}"
                            />
                            <img id="picker-end-icon" class="date-selector-icon" src="{{ asset('images/calendar_24px.png')}}" alt="date" height="24" width="24">

                        <span class="qc-form-required" style="top: 35%; margin-left: 32px;">未指定常時掲載</span>
                        @if ($errors->has('date_start'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('date_start') }}</span>
                        @endif
                        @if ($errors->has('date_end'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('date_end') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <input type="number" id="display_order" name="display_order" class="qc-form-input input-s" value="{{ old('display_order', '999') }}"/>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('display_order'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('display_order') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">使用区分</td>
                    <td>
                        <div class="qc-form-radio-group">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification1"
                                       name="use_classification"
                                       value="1"><span>使用しない</span>
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="use_classification2"
                                       name="use_classification"
                                       checked value="2"><span>使用する</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">更新登録日</td>
                    <td>
                        <span></span>
                        <input id="created_at" type="hidden" name="created_at">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function(){
                $("#form-register").submit();
        });

        $(function () {
            dtpicker();
        });
    </script>
    <script>
        $(document).ready(function() {
            var arrayOfOptions = [
                @if(isset($suggestion))
                    @foreach($suggestion as $suggestion_item)
                    '{{$suggestion_item->library_type}}',
                    @endforeach
                @endif
            ];
            $("#classification").autocomplete({
                source: arrayOfOptions,
                minLength: 0,
                search: "",
                classes: {
                    "ui-autocomplete": "l-suggestion-item"
                }
            }).bind('focus', function(){ $(this).autocomplete("search"); } );
        });
    </script>
@endsection
