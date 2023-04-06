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
use Storage;
use File;

class TopicController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($thread_id)
    {
        $thread = Thread::find($thread_id);
        $category = Category::find($thread->category_id);
        return view('topic.add', ['category'=> $category, 'thread' => $thread]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'topic'=> 'required',
                'image' => 'nullable|file|max:5120'
            ],
            [
                'topic.required' => StaticConfig::$Required,
                'image.max' => StaticConfig::$Image_Size
            ]
        );
        $topic = new Topic();
        $topic->thread_id = $request->thread_id;
        $topic->topic = $request->topic;

        if ($request->hasFile('image')) {
            if(!Storage::exists('/storage/app/public/uploaded-files/topic-upload')) {
                Storage::makeDirectory('/storage/app/public/uploaded-files/topic-upload', 0775, true); //creates directory
            }
            if ($request->hasFile('image')) {
                $stamp = now()->timestamp;
                $fileName = $stamp . '.' . $request->file('image')->getClientOriginalName();
                $topic->file = $fileName;

                $request->file('image')->move(base_path() . '/storage/app/public/uploaded-files/topic-upload', $fileName);
                $topic->file = $fileName;
            }
        }
        $topic->created_by = Auth::id();
        $topic->updated_by = Auth::id();
        $topic->save();



        return redirect()->route('topic.show', $request->thread_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($thread_id)
    {
        $thread = Thread::find($thread_id);
        $user_created_by_name = '';
        $user_created_by = User::find($thread->created_by);
        if ($user_created_by != null) {
            $user_created_by_name = $user_created_by->name;
        }

        $category = Category::find($thread->category_id);
        $topic = DB::table('topics')->where('thread_id', $thread_id)->orderBy('created_at', 'desc')->paginate(20);
        return view('topic.list',
            [
                'category' => $category,
                'thread' => $thread,
                'user_created_by_name' => $user_created_by_name,
                'paginate' => $topic,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
            ]);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->topicID;
        $topic = Topic::find($id);
        $thread_id = $topic->thread_id;
        if(isset($topic->file)){
            $img = base_path() . '/storage/app/public/uploaded-files/topic-upload/'.$topic->file;
            if(File::exists($img)){
                File::delete($img);
            }
        }
        $topic->delete();

        return redirect()->route('topic.show', [$thread_id]);
    }
}
