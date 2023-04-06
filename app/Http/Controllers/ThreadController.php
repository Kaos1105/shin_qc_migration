<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class ThreadController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($category_id)
    {
        $category = Category::find($category_id);
        return view('thread.add', ['category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'thread_name' => 'required',
            ],
            [
                'thread_name.required' => StaticConfig::$Required,
            ]
        );
        $thread = new Thread();
        $thread->category_id = $request->category_id;
        $thread->thread_name = $request->thread_name;
        $thread->note = $request->note;
        $thread->is_display = $request->is_display;

        $thread->use_classification = 2;
        $thread->display_order = 100;
        $thread->statistic_classification = 2;
        $thread->created_by = Auth::id();
        $thread->updated_by = Auth::id();
        $thread->save();
        return redirect()->route('thread.show', $request->category_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($category_id)
    {
        $category = Category::find($category_id);
        $user_created_by_name = '';
        $user_created_by = User::find($category->created_by);
        if ($user_created_by != null) {
            $user_created_by_name = $user_created_by->name;
        }
        $sort = 'updated_at';
        $sortType = 'desc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        $topic_count = DB::table('topics')->select('thread_id', DB::raw('count("thread_id") as topic_count'))->groupBy('thread_id');
        $thread = DB::table('threads')
            ->where('category_id', $category_id)
            ->leftJoinSub($topic_count, 'topic_counted', function ($join) {
                $join->on('threads.id', '=', 'topic_counted.thread_id');
            })
            ->orderBy($sort, $sortType)
            ->paginate(20);
        return view('thread.list',
            [
                'category' => $category,
                'user_created_by_name' => $user_created_by_name,
                'paginate' => $thread,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $thread = Thread::find($id);
        $user_created_by_name = '';
        $user_updated_by_name = '';
        $user_created_by = User::find($thread->created_by);
        if ($user_created_by != null) {
            $user_created_by_name = $user_created_by->name;
        }
        $user_updated_by = User::find($thread->updated_by);
        if ($user_updated_by != null) {
            $user_updated_by_name = $user_updated_by->name;
        }
        $category = Category::find($thread->category_id);
        return view('thread.edit', ['category' => $category, 'thread' => $thread, 'user_created_by_name' => $user_created_by_name, 'user_updated_by' => $user_updated_by_name]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'thread_name' => 'required',
            ],
            [
                'thread_name.required' => StaticConfig::$Required,
            ]
        );
        $thread = Thread::find($id);
        $thread->thread_name = $request->thread_name;
        $thread->statistic_classification = 10;
        $thread->is_display = $request->is_display;
        $thread->note = $request->note;
        $thread->updated_by = Auth::id();
        $thread->save();
        return redirect()->route('topic.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::where('thread_id', $id);
        $topic->delete();
        $thread = Thread::find($id);
        $category_id = $thread->category_id;
        $thread->delete();
        return redirect()->route('thread.show', $category_id);
    }

    public function user($id)
    {
        $user = User::find($id);
        $user_created_by_name = '';
        $user_updated_by_name = '';
        $user_created_by = User::find($user->created_by);
        if ($user_created_by != null) {
            $user_created_by_name = $user_created_by->name;
        }
        $user_updated_by = User::find($user->updated_by);
        if ($user_updated_by != null) {
            $user_updated_by_name = $user_updated_by->name;
        }
        return view('thread.view-user', ['user' => $user, 'user_created_by_name' => $user_created_by_name, 'user_updated_by' => $user_updated_by_name]);
    }
}
