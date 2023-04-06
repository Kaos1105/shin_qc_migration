@extends('layouts.app')

@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
           $trail->push('トップページ', route('toppageoffice'));
       }),
        Breadcrumbs::for('list', function ($trail) {
            $trail->parent('toppage');
            $trail->push('ストーリー登録（一覧）',route('story.getList'));
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
        <a class="btn btn-back" href="{{ URL::to('story/list') }}">戻る</a>
    </div>
    <div class="container-fluid">
        <form id="form-register" method="POST" enctype="multipart/form-data" action="">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td> </td>
                </tr>
                <tr>
                    <td class="td-first">ストーリー区分</td>
                    <td>
                        <input class="qc-form-input input-m" type="text" id="story_classification" name="story_classification"
                               value="{{old('story_classification')}}"/>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('story_classification'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('story_classification') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ストーリー名</td>
                    <td>
                        <input class="qc-form-input input-l" type="text" id="story_name" name="story_name" value="{{old('story_name')}}"/>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('story_name'))
                            <span class="qc-form-error-message"
                                  style="vertical-align: middle">{{ $errors->first('story_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
                        <textarea class="qc-form-area input-l" id="description" name="description">{{old('description')}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <span>{{$new_display_order}}</span>
                        <input type="hidden" name="display_order" value="{{$new_display_order}}"/>
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
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit();
        });
    </script>
    <script>
        $(document).ready(function() {
            var arrayOfOptions = [
            @foreach($suggestion as $suggestion_item)
               '{{$suggestion_item->story_classification}}',
            @endforeach
            ];
            $("#story_classification").autocomplete({
                source: arrayOfOptions,
                minLength: 0,
                search: "",
                classes: {
                    "ui-autocomplete": "l-suggestion"
                }
            }).bind('focus', function(){ $(this).autocomplete("search"); } );
        });
    </script>
@endsection
