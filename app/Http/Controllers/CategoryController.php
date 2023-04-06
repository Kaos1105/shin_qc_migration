<?php

namespace App\Http\Controllers;

use App\Category;
use App\Thread;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $sort = 'updated_at';
        $sortType = 'desc';
        $filter = 2;
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['sortType']) && ($_GET['sortType'] =='asc' || $_GET['sortType'] = 'desc')){
            $sortType = $_GET['sortType'];
        }

        if(isset($_GET['filter'])){
            $filter = (int) $_GET['filter'];
        }

        $thread_count = DB::table('threads')->select('category_id', DB::raw('count("category_id") as thread_count'))->groupBy('category_id');

        if($filter == 0){
            $category = DB::table('categories')
                ->leftJoinSub($thread_count, 'thread_count', function ($join) {
                    $join->on('categories.id', '=', 'thread_count.category_id');
                })->orderBy($sort, $sortType)
                ->paginate(20);
        }else{
            $category = DB::table('categories')
                ->leftJoinSub($thread_count, 'thread_count', function ($join) {
                    $join->on('categories.id', '=', 'thread_count.category_id');
                })->where('use_classification', $filter)
                ->orderBy($sort, $sortType)
                ->paginate(20);
        }
        return view('category.list', ['paginate' => $category]);
    }

    public function create()
    {
        return view('category.add');
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
                'category_name' => 'required|max:500'
            ],
            [
                'category_name.required' => StaticConfig::$Required,
                'category_name.max' => StaticConfig::$Max_Length_500
            ]
        );

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->use_classification = $request->use_classification;
        $category->note = $request->note;
        $category->is_display = 1;
        $category->display_order = 100;
        $category->statistic_classification = 2;
        $category->created_by = Auth::id();
        $category->updated_by = Auth::id();
        $category->save();
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return view('category.edit',
            [
                'category' => $category,
                'callback'  => isset($_GET['callback'])? 'yes' : null,
                'holdsort'  => isset($_GET['holdsort'])? 'yes' : null
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        $user_created_by_name = '';
        $user_updated_by_name = '';
        $user_created_by = User::find($category->created_by);
        if($user_created_by != null){
            $user_created_by_name = $user_created_by->name;
        }
        $user_updated_by = User::find($category->updated_by);
        if($user_updated_by != null){
            $user_updated_by_name = $user_updated_by->name;
        }
        return view('category.edit',['category' => $category, 'user_created_by_name' => $user_created_by_name, 'user_updated_by' => $user_updated_by_name]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'category_name' => 'required|max:500'
            ],
            [
                'category_name.required' => StaticConfig::$Required,
                'category_name.max' => StaticConfig::$Max_Length_500
            ]
        );
        $category = Category::find($id);
        $category->category_name = $request->category_name;
        $category->use_classification = $request->use_classification;
        $category->note = $request->note;
        $category->updated_by = Auth::id();
        $category->save();
        return redirect()->route('thread.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $thread = Thread::where('category_id', $id);
        $thread_id = $thread->select('id')->get()->toArray();
        $thread_id_array = array_column($thread_id, 'id');
        $topic = Topic::whereIn('thread_id', $thread_id_array);
        $topic->delete();
        $thread->delete();
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('category.index');
    }
}