@extends('layouts.app')

@section('breadcrumbs')
    {{
      Breadcrumbs::for('toppage', function ($trail) {
        $trail->push('トップページ', route('toppageoffice'));
        }),
      Breadcrumbs::for('list', function ($trail) {
        $trail->parent('toppage');
        $trail->push('掲示板',route('category.index'));
        }),
      Breadcrumbs::for('index', function ($trail) {
        $trail->parent('list');
        $trail->push('トピック一覧');
        })
    }}

    {{ Breadcrumbs::render('index') }}
@endsection

@section('content')
    <div class="float-right btn-area">
        @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)
            <a class="btn btn-add" id="btn-register" href="{{ route('thread.edit', [$thread->id]) }}">修正</a>
        @endif
        @if(isset($holdsort))
            <button class="btn btn-back" onclick="window.history.back()">戻る</button>
        @else
            <a class="btn btn-back" href="{{ URL::to('thread/'.$category->id) }}">戻る</a>
        @endif
    </div>
    <div class="container-fluid">
        <table class="table-form" border="1">
            <tr>
                <td class="td-first">{{ __('登録ID') }}</td>
                <td>
                    <span>{{ isset($thread)? \App\Enums\Common::show_id($thread['id']) : null }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">カテゴリ名</td>
                <td>
                    <span class="line-brake-preserve font-weight-bold">{{ $category->category_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">スレッド名</td>
                <td>
                    <span id="thread_name" class="line-brake-preserve"> {{ $thread->thread_name }}</span>
                </td>
            </tr>
            <tr>
                <td class="td-first">説明</td>
                <td>
                    <span id="note" class="line-brake-preserve"><span class="ql-editor" style="all:unset;">{!! $thread->note !!}</span></span>
                </td>
            </tr>
            <tr>
                <td class="td-first">更新通知</td>
                <td>
                    @if ($thread->is_display == 1 )  <span>通知しない</span>  @else <span>通知する</span> @endif
                </td>
            </tr>
            <tr>
                <td class="td-first">新規登録日</td>
                <td>
                    <span>{{ date_format(date_create($thread->created_at),"Y/m/d H:i:s") }} {{$user_created_by_name}}</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="container-fluid bottom-fix">
        <div class="float-right btn-area-list full-width position-relative" style="margin-top: 1rem">
            @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)
                <a class="btn btn-add float-right" style="margin-right: -15px"
                   href="{{ URL::to('topic/add/'.$thread->id) }}">追加</a>
            @endif
        </div>
        @if(count($paginate) >0)
            @include('common.paginate')
            <table class="table-topic  full-width">
                <tbody>
                <?php $stt = ($paginate->currentPage() - 1) * 20 + 1; ?>
                @foreach ($paginate as $topic)
                    <tr>
                        <td>
                            <table class="table topic-row">
                                <tbody>
                                <tr>
                                    <td rowspan="2" style="width: 2%">No.{{ $stt++ }}</td>
                                    <td style="width: 83%" class="line-brake-preserve"><div class="ql-editor" style="all:unset;"> {!! $topic->topic !!}</div></td>
                                    <td style="width: 15%">
                                        @if(Auth::user() -> access_authority == \App\Enums\AccessAuthority::ADMIN)
                                            @if(session('toppage') != \App\Enums\AccessAuthority::USER)
                                                <button class="delete btn btn-add float-right"
                                                        style="box-shadow: none; width: 78px;"
                                                        onclick="deleteTopic({{ $topic->id }})">削除
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php
                                        $allowedMimeTypes = ['jpeg', 'gif', 'png', 'bmp', 'svg+xml', 'jpg', 'tiff', 'tif'];
                                        if (isset($topic->file)) {
                                            $extension = strtolower(pathinfo($topic->file, PATHINFO_EXTENSION));
                                            if (!in_array($extension, $allowedMimeTypes)) {
                                                $isImage = false;
                                            } else {
                                                $isImage = true;
                                            }
                                        }

                                        $fileOrigin = $topic->file;
                                        $fileNameDisp = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin) - stripos($fileOrigin, '.'));
                                        ?>
                                        @if(isset($topic->file))
                                            @if($isImage)
                                                <img src="/storage/uploaded-files/topic-upload/{{ $topic->file }}"
                                                     alt="{{ $topic->file }}" class="img-auto-resize"/>
                                            @else
                                                <img src="{{asset('/images/icons8-file-80.png')}}" alt="file-icon"
                                                     width="48px" height="48px" style="margin-top: -10px"/>
                                                <div class="d-inline-block file-ext"
                                                     style="background-color: {{\App\Enums\Common::generateColor($extension)}}">{{$extension}}</div>
                                                <span>{{$fileNameDisp}}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="topic-date-align">
                                        <?php
                                        $topic_updater = DB::table('users')->where('id', $topic->updated_by);
                                        $topic_updater_name = isset($topic_updater) ? $topic_updater->value('name') : null;
                                        ?>
                                        {{ date_format(date_create($topic->updated_at),"Y/m/d H:i") }} {{ $topic_updater_name }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('common.paginate')
        @endif
    </div>
    {!! Form::open(['id'=> 'form-delete-topic','method' => 'Delete', 'route' => ['topic.destroy', 123]]) !!}
    <input type="hidden" name="topicID" id="topicID"/>
    {!! Form::close() !!}
    <script>
        function deleteTopic(topicID) {
            document.getElementById("topicID").value = topicID;
            var r = confirm("{{ App\Enums\StaticConfig::$Delete_Topic }}");
            if (r == true) {
                $('#form-delete-topic').submit();
            }
        }
    </script>
@endsection
