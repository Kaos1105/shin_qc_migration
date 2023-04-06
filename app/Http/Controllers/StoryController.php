<?php

namespace App\Http\Controllers;

use App\Enums\AccessAuthority;
use App\Enums\UseClassificationEnum;
use App\Story;
use App\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Response;
use Storage;

class StoryController extends Controller
{
    public function getList()
    {
        $sort = 'display_order';
        $sortType = 'asc';
        $filter = 0;
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if (isset($_GET['sortType']) && ($_GET['sortType'] == 'asc' || $_GET['sortType'] = 'desc')) {
            $sortType = $_GET['sortType'];
        }
        if (isset($_GET['filter'])) {
            $filter = (int)$_GET['filter'];
        }

        if ($filter == 0) {
            $story = DB::table('stories')->orderBy($sort, $sortType)->paginate(20);
            $max_display_order = DB::table('stories')->orderBy($sort, $sortType)->max('display_order');
            $min_display_order = DB::table('stories')->orderBy($sort, $sortType)->min('display_order');
        } else {
            $story = DB::table('stories')->where('use_classification', $filter)->orderBy($sort, $sortType)->paginate(20);
            $max_display_order = DB::table('stories')->where('use_classification', $filter)->orderBy($sort, $sortType)->max('display_order');
            $min_display_order = DB::table('stories')->where('use_classification', $filter)->orderBy($sort, $sortType)->min('display_order');
        }

        return view('story.list',
            [
                'paginate' => $story,
                'max_display_order' => isset($max_display_order) ? $max_display_order : null,
                'min_display_order' => isset($min_display_order) && $min_display_order != $max_display_order ? $min_display_order : null,
                'sortType' => $sortType,
                'sort' => $sort
            ]);
    }

    public function getAdd()
    {
        $story = new Story();
        $suggestion = DB::table('stories')->where('use_classification', UseClassificationEnum::USES)
            ->select('story_classification')->distinct()->get();
        $new_display_order = DB::table('stories')->max('display_order');
        return view('story.add',
            [
                'story' => $story,
                'suggestion' => $suggestion,
                'new_display_order' => $new_display_order + 1,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request)
    {
        $this->validate($request,
            [
                'story_classification' => 'required|max:100',
                'story_name' => 'required|unique:stories,story_name|max:100',
            ],
            [
                'story_classification.max' => StaticConfig::$Max_Length_100,
                'story_classification.required' => StaticConfig::$Required,
                'story_name.required' => StaticConfig::$Required,
                'story_name.max' => StaticConfig::$Max_Length_100,
                'story_name.unique' => StaticConfig::$Unique,
            ]
        );

        $story = new Story();
        $story->story_classification = $request->story_classification;
        $story->story_name = $request->story_name;
        $story->use_classification = $request->use_classification;
        $story->description = $request->description;
        $story->display_order = $request->display_order;
        $story->created_by = Auth::id();
        $story->updated_by = Auth::id();
        $story->save();

        return redirect()->route('story.getShow', $story->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getShow($id)
    {
        $story = Story::find($id);
        $user_updated_by = User::find($story->updated_by);

        return view('story.view',
            [
                'story' => $story,
                'user_updated_by' => isset($user_updated_by) ? $user_updated_by->name : '',
                'callback' => isset($_GET['callback']) ? 'yes' : null,
                'holdsort' => isset($_GET['holdsort']) ? 'yes' : null
            ]
        );
    }

    public function getShowStory($id)
    {
        $story = Story::find($id);
        $user_updated_by = User::find($story->updated_by);

        return view('story.view-no-edit',
            [
                'story' => $story,
                'user_updated_by' => isset($user_updated_by) ? $user_updated_by->name : '',
                'callback' => isset($_GET['callback']) ? 'yes' : null,
                'holdsort' => isset($_GET['holdsort']) ? 'yes' : null
            ]
        );
    }

    public function getEdit($id)
    {
        $story = Story::find($id);
        $user_updated_by = User::find($story->updated_by);
        $suggestion = DB::table('stories')->where('use_classification', UseClassificationEnum::USES)
            ->orWhere('id', $story->id)->select('story_classification')->distinct()->get();

        return view('story.edit',
            [
                'story' => $story,
                'user_updated_by' => isset($user_updated_by) ? $user_updated_by->name : '',
                'suggestion' => $suggestion
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit(Request $request, $id)
    {
        $this->validate($request,
            [
                'story_classification' => 'nullable|max:100',
                'story_name' => 'required|unique:stories,story_name,' . $id . '|max:100',
            ],
            [
                'story_classification.max' => StaticConfig::$Max_Length_100,
                'story_name.required' => StaticConfig::$Required,
                'story_name.max' => StaticConfig::$Max_Length_100,
                'story_name.unique' => StaticConfig::$Unique,
            ]
        );

        $story = Story::find($id);
        $story->story_classification = $request->story_classification;
        $story->story_name = $request->story_name;
        $story->use_classification = $request->use_classification;
        $story->description = $request->description;
        $story->display_order = $request->display_order;
        $story->updated_by = Auth::id();
        $story->save();

        return redirect()->route('story.getShow', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete($id)
    {
        $story = Story::find($id);
        $qa = DB::table('qas')->where('story_id', $id)->first();
        if (isset($qa)) {
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('delete_error', StaticConfig::$Cannot_Delete_Story);
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $story->delete();
        }
        return redirect()->route('story.getList');
    }


    public function postChangeDisplayOrder(Request $request)
    {
        $id = $request->id;
        $story = Story::find($id);
        $sortType = $request->sort_type;

        $filter = 0;
        if (isset($_GET['filter'])) {
            $filter = (int)$_GET['filter'];
        }
        if (!$sortType || $sortType == 'asc') {
            if ($request->up_down == 'up') {
                if ($filter == 0) {
                    $list = DB::table('stories')->orderBy('display_order', 'asc')
                        ->where('display_order', '<=', $story->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('stories')->where('use_classification', $filter)->orderBy('display_order', 'asc')
                        ->where('display_order', '<=', $story->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos - 1];
            } else {
                if ($filter == 0) {
                    $list = DB::table('stories')->orderBy('display_order', 'asc')
                        ->where('display_order', '>=', $story->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('stories')->where('use_classification', $filter)->orderBy('display_order', 'asc')
                        ->where('display_order', '>=', $story->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos + 1];
            }
        } else {
            if ($request->up_down == 'up') {
                if ($filter == 0) {
                    $list = DB::table('stories')->orderBy('display_order', 'desc')
                        ->where('display_order', '>=', $story->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('stories')->where('use_classification', $filter)->orderBy('display_order', 'desc')
                        ->where('display_order', '>=', $story->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos - 1];
            } else {
                if ($filter == 0) {
                    $list = DB::table('stories')->orderBy('display_order', 'desc')
                        ->where('display_order', '<=', $story->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('stories')->where('use_classification', $filter)->orderBy('display_order', 'desc')
                        ->where('display_order', '<=', $story->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos + 1];
            }
        }
        $adjacent_story = Story::find($id_adjacent);

        $med = $adjacent_story->display_order;
        $adjacent_story->display_order = $story->display_order;
        $story->display_order = $med;
        $story->save();
        $adjacent_story->save();
        return redirect()->back();
    }
}