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
        Breadcrumbs::for('edit', function ($trail) {
            $trail->parent('list');
            $trail->push('修正');
            })
    }}

    {{ Breadcrumbs::render('edit') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ route('story.getShow', $story->id) }}">戻る</a>
    </div>
    <div class="container-fluid">
        {{--{{ Form::model($story, array('route' => array('story.update', $story->id), 'method' => 'PUT', 'id' => 'form-register')) }}--}}
        <form id="form-register" method="POST" enctype="multipart/form-data" action="">
            @csrf
            <table class="table-form" border="1">
                <tr>
                    <td class="td-first">{{ __('登録ID') }}</td>
                    <td>
                        <span>{{ isset($story)? \App\Enums\Common::show_id($story['id']) : null }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ストーリー区分</td>
                    <td>
                        <input type="text" class="qc-form-input input-m" id="story_classification" name="story_classification" value="{{$story->story_classification}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">ストーリー名</td>
                    <td>
                        <input id="story_name" type="text" class="qc-form-input qc-form-input-50" name="story_name" value="{{ $story->story_name}}" required>
                        <span class="qc-form-required" style="top: 35%">必須</span>
                        @if ($errors->has('story_name'))
                            <span class="qc-form-error-message">{{ $errors->first('story_name') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-first">説明</td>
                    <td>
                        <textarea class="qc-form-area input-l" id="description" name="description">{{$story->description}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="td-first">表示順番号</td>
                    <td>
                        <span>{{$story->display_order}}</span>
                        <input type="hidden" name="display_order" value="{{$story->display_order}}"/>
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
                        <span>{{date_format(date_create($story->updated_at),"Y/m/d H:i:s")}} {{$user_updated_by}}</span>
                        <input id="created_at" type="hidden" name="created_at">
                    </td>
                </tr>
            </table>
        </form>
        {{--{{ Form::close() }}--}}
    </div>
    <script>
        $("#btn-register").click(function(){
            $("#form-register").submit();
        });
    </script>
    <script>
        var use_classification1 = $('#use_classification1'),
            use_classification2 = $('#use_classification2');
        if (use_classification1.val() == {{$story->use_classification}}) {
            use_classification1.prop('checked', true);
        } else {
            use_classification2.prop('checked', true);
        }
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
                    "ui-autocomplete": "l-suggestion-item"
                }
            }).bind('focus', function(){ $(this).autocomplete("search"); } );
        });
    </script>
@endsection
