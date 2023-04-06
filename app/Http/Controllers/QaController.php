<?php

namespace App\Http\Controllers;

use App\Enums\Common;
use App\Enums\UseClassificationEnum;
use App\Qa;
use App\QaQuestion;
use App\QaAnswer;
use App\User;
use Illuminate\Http\Request;
use App\Enums\StaticConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Response;
use Storage;

class QaController extends Controller
{
    public function getList()
    {
        $sort = 'display_order';
        $sortType = 'asc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if (isset($_GET['sortType']) && ($_GET['sortType'] == 'asc' || $_GET['sortType'] = 'desc')) {
            $sortType = $_GET['sortType'];
        }

        $arr_where = array();
        if (isset($_GET['storyFilter']) && $_GET['storyFilter'] != 'all') {
            $arr_where['story_id'] = $_GET['storyFilter'];
        }
        if (isset($_GET['filter']) && $_GET['filter'] != 'all') {
            $arr_where['use_classification'] = $_GET['filter'];
        }

        $story_raw = DB::table('stories')->orderBy('display_order', 'asc')
            ->selectRaw('stories.id as storyId, stories.story_name, stories.story_classification');
        $qa_raw = DB::table('qas')
            ->leftJoinSub($story_raw, 'tb1', function ($join) {
                $join->on('qas.story_id', '=', 'tb1.storyId');
            });

        $aa = DB::table('qas')->selectRaw('qas.id as linkedId, qas.screen_id');
        $bb = DB::table('qa_answers')
            ->leftJoinSub($aa, 'tb2', function ($join) {
                $join->on('qa_answers.qa_linked', '=', 'tb2.linkedId');
            })->selectRaw('qa_id, linkedId, screen_id as linked_screen, content');
        $qa_answers = $qa_raw->leftJoinSub($bb, 'tb3', function ($join) {
            $join->on('id', '=', 'tb3.qa_id');
        })->selectRaw('*, GROUP_CONCAT(CONCAT(content, "(", linked_screen, ")") SEPARATOR "\n") as answer')
            ->groupBy('id');

        $qa = $qa_answers->where($arr_where)->orderBy($sort, $sortType)
            ->paginate(20);

        $max_display_order = DB::table('qas')->where($arr_where)->max('display_order');
        $min_display_order = DB::table('qas')->where($arr_where)->min('display_order');

        $story_list = $story_raw->where('use_classification', UseClassificationEnum::USES)->distinct()->get();
        return view('qa.list',
            [
                'paginate' => $qa,
                'max_display_order' => isset($max_display_order) ? $max_display_order : 1,
                'min_display_order' => isset($min_display_order) ? $min_display_order : 1,
                'story_list' => isset($story_list) ? $story_list : null,
                'sortType' => $sortType,
                'sort' => $sort
            ]);
    }

    public function getAdd()
    {
        $qa = new Qa();
        $story = DB::table('stories')->where('use_classification', UseClassificationEnum::USES)
            ->orderBy('display_order', 'asc')->select('id', 'story_name')->get();
        $new_display_order = DB::table('qas')->max('display_order');
        return view('qa.add',
            [
                'qa' => $qa,
                'story' => $story,
                'new_display_order' => $new_display_order + 1
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
                'story_id' => 'required',
                'title' => 'required|max:100',
                'screen_id' => 'required|unique:qas,screen_id|max:5',
            ],
            [
                'story_id.required' => StaticConfig::$Required,
                'title.required' => StaticConfig::$Required,
                'screen_id.required' => StaticConfig::$Required,
                'title.max' => StaticConfig::$Max_Length_100,
                'screen_id.max' => StaticConfig::$Max_Length_5,
                'screen_id.unique' => StaticConfig::$Unique,
            ]
        );

        $qa = new Qa();
        $qa->title = $request->title;
        $qa->story_id = $request->story_id;
        $qa->screen_id = $request->screen_id;

        $qa->use_classification = $request->use_classification;
        $qa->note = $request->note;
        $qa->display_order = $request->display_order;
        $qa->created_by = Auth::id();
        $qa->updated_by = Auth::id();
        $qa->save();

        return redirect()->route('qa.getShow', $qa->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getShow($id)
    {
        $qa = Qa::find($id);
        $story = DB::table('stories')->where('id', $qa->story_id)->select('story_name')->first();
        $user_updated_by = User::find($qa->updated_by);

        $qa_question_core = DB::table('qa_questions')->where('qa_id', $qa->id)->orderBy('display_order', 'asc');
        $qa_question = $qa_question_core->get();
        $new_question_disp_order = $qa_question_core->max('display_order') + 1;

        $stories = DB::table('stories')->selectRaw('id as storyID, story_name');
        $qa_linked = DB::table('qas')
            ->leftJoinSub($stories, 'storii', function ($join) {
                $join->on('qas.story_id', '=', 'storyID');
            });
        $qa_linked_dialog = (clone $qa_linked)->selectRaw('id as qaLinkedID, display_order as disp, use_classification, CONCAT(qas.screen_id, " ", qas.title) AS qaLinked, storii.story_name as story')
            ->where('use_classification', UseClassificationEnum::USES)->where('id', "<>", $id)->orderBy('disp', 'asc')->get();
        $story_list = (clone $qa_linked)->selectRaw('id as qaLinkedID, display_order as disp, use_classification, CONCAT(qas.screen_id, " ", qas.title) AS qaLinked, storii.story_name as story')
            ->groupBy('story')->get();

        $qa_answer_core = DB::table('qa_answers')->where('qa_id', $qa->id)->orderBy('display_order', 'asc');

        $qa_linked2 = (clone $qa_linked)->selectRaw('id as qaLinkedID, display_order as disp, use_classification, CONCAT("(", story_name, ") ", qas.screen_id, " ", qas.title) AS qaLinked, storii.story_name as story');
        $qa_answer = $qa_answer_core->leftJoinSub($qa_linked2, 'qaLink', function ($join) {
            $join->on('qa_linked', '=', 'qaLink.qaLinkedID');
        })->get();

//        return $qa_answer;

        $new_answer_disp_order = $qa_answer_core->max('display_order') + 1;

        $deletable = DB::table('qa_answers')->where('qa_linked', $id)->doesntExist();

        return view('qa.view',
            [
                'qa' => $qa,
                'user_updated_by' => isset($user_updated_by) ? $user_updated_by->name : '',
                'story' => $story,
                'new_question_disp_order' => $new_question_disp_order,
                'qa_question' => $qa_question,
                'qa_answer' => $qa_answer,
                'deletable' => $deletable,
                'qa_linked_dialog' => $qa_linked_dialog,
                'story_list' => $story_list,
                'new_answer_disp_order' => $new_answer_disp_order,
                'callback' => isset($_GET['callback']) ? 'yes' : null,
                'holdsort' => isset($_GET['holdsort']) ? 'yes' : null
            ]
        );

    }

    public function getShowQa($id)
    {
        $qa = Qa::find($id);
        $story = DB::table('stories')->where('id', $qa->story_id)->select('story_name')->first();
        $user_updated_by = User::find($qa->updated_by);

        $qa_question_core = DB::table('qa_questions')->where('qa_id', $qa->id);
        $qa_question = $qa_question_core->orderBy('display_order', 'asc')->get();

        $stories = DB::table('stories')->selectRaw('id as storyID, story_name');
        $qa_linked = DB::table('qas')
            ->leftJoinSub($stories, 'storii', function ($join) {
                $join->on('qas.story_id', '=', 'storyID');
            })->selectRaw('id as qaLinkedID, display_order as disp, use_classification, CONCAT("(", storii.story_name, ") ", qas.screen_id, " ", qas.title) AS qaLinked');
        $qa_linked_dialog = $qa_linked->where('use_classification', UseClassificationEnum::USES)->orWhere('id', $id)->orderBy('disp', 'asc')->get();
        $qa_answer_core = DB::table('qa_answers')->where('qa_id', $qa->id);
        $qa_answer = $qa_answer_core->leftJoinSub($qa_linked, 'qaLink', function ($join) {
            $join->on('qa_linked', '=', 'qaLink.qaLinkedID');
        })->orderBy('display_order', 'asc')->get();

        return view('qa.view-no-edit',
            [
                'qa' => $qa,
                'user_updated_by' => isset($user_updated_by) ? $user_updated_by->name : '',
                'story' => $story,
                'qa_question' => $qa_question,
                'qa_answer' => $qa_answer,
                'qa_linked_dialog' => $qa_linked_dialog,
                'callback' => isset($_GET['callback']) ? 'yes' : null,
                'holdsort' => isset($_GET['holdsort']) ? 'yes' : null
            ]
        );
    }

    public function getEdit($id)
    {
        $qa = Qa::find($id);
        $user_updated_by = User::find($qa->updated_by);
        $story = DB::table('stories')->where('use_classification', UseClassificationEnum::USES)
            ->orWhere('id', $qa->story_id)->orderBy('display_order', 'asc')->select('id', 'story_name')->get();

        return view('qa.edit',
            [
                'qa' => $qa,
                'user_updated_by' => isset($user_updated_by) ? $user_updated_by->name : '',
                'story' => $story,
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
                'story_id' => 'required',
                'title' => 'required|max:100',
                'screen_id' => 'required|unique:qas,screen_id,' . $id . '|max:5',
            ],
            [
                'story_id.required' => StaticConfig::$Required,
                'title.required' => StaticConfig::$Required,
                'screen_id.required' => StaticConfig::$Required,
                'title.max' => StaticConfig::$Max_Length_100,
                'screen_id.max' => StaticConfig::$Max_Length_5,
                'screen_id.unique' => StaticConfig::$Unique,
            ]
        );

        $qa = Qa::find($id);
        $qa->story_id = $request->story_id;
        $qa->screen_id = $request->screen_id;
        $qa->title = $request->title;
        $qa->note = $request->note;

        $qa->use_classification = $request->use_classification;
        $qa->display_order = $request->display_order;
        $qa->updated_by = Auth::id();
        $qa->save();

        return redirect()->route('qa.getShow', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete($id)
    {
        $qa = Qa::find($id);
        $qa_question = QaQuestion::where('qa_id', $id);
        $qa_answer = QaAnswer::where('qa_id', $id);
        $list_file_ans = $qa_answer->select('file_name')->get();
        $list_file_q = $qa_question->select('file_name')->get();
        foreach ($list_file_ans as $item_ans) {
            if (isset($item_ans->file_name)) {
                $oldFile = base_path() . StaticConfig::$Upload_Path_QaAnswer . $item_ans->file_name;
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
        }
        $qa_answer->delete();

        foreach ($list_file_q as $item_q) {
            if (isset($item_q->file_name)) {
                $oldFile = base_path() . StaticConfig::$Upload_Path_QaQuestion . $item_q->file_name;
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
        }
        $qa_question->delete();
        $qa->delete();
        return redirect()->route('qa.getList');
    }

    public function postChangeDisplayOrder(Request $request)
    {
        $id = $request->id;
        $qa = Qa::find($id);
        $sortType = $request->sort_type;
        $filter = 0;
        if (isset($_GET['filter'])) {
            $filter = (int)$_GET['filter'];
        }

        if (!$sortType || $sortType == 'asc') {
            if ($request->up_down == 'up') {
                if ($filter == 0) {
                    $list = DB::table('qas')->orderBy('display_order', 'asc')
                        ->where('display_order', '<=', $qa->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('qas')->where('use_classification', $filter)->orderBy('display_order', 'asc')
                        ->where('display_order', '<=', $qa->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos - 1];
            } else {
                if ($filter == 0) {
                    $list = DB::table('qas')->orderBy('display_order', 'asc')
                        ->where('display_order', '>=', $qa->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('qas')->where('use_classification', $filter)->orderBy('display_order', 'asc')
                        ->where('display_order', '>=', $qa->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos + 1];
            }
        } else {
            if ($request->up_down == 'up') {
                if ($filter == 0) {
                    $list = DB::table('qas')->orderBy('display_order', 'desc')
                        ->where('display_order', '>=', $qa->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('qas')->where('use_classification', $filter)->orderBy('display_order', 'desc')
                        ->where('display_order', '>=', $qa->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos - 1];
            } else {
                if ($filter == 0) {
                    $list = DB::table('qas')->orderBy('display_order', 'desc')
                        ->where('display_order', '<=', $qa->display_order)->select('id')->get()->toArray();
                } else {
                    $list = DB::table('qas')->where('use_classification', $filter)->orderBy('display_order', 'desc')
                        ->where('display_order', '<=', $qa->display_order)->select('id')->get()->toArray();
                }
                $list_id = array_column($list, 'id');
                $id_pos = array_search($id, $list_id);
                $id_adjacent = $list_id[$id_pos + 1];
            }
        }

        $adjacent_qa = Qa::find($id_adjacent);
        $med = $adjacent_qa->display_order;
        $adjacent_qa->display_order = $qa->display_order;
        $qa->display_order = $med;
        $qa->save();
        $adjacent_qa->save();
        return redirect()->back();
    }

    public function postQuestion(Request $request)
    {
        $qa_question_new = new QaQuestion();
        $qa_question_new->qa_id = $request->qa_id;
        $qa_question_new->screen_classification = $request->screen_classification;
        $qa_question_new->display_order = $request->display_order;
        $qa_question_new->alignment = $request->alignment;
        if (!empty($request->delta)) {
            $qa_question_new->delta = $request->delta;
        } else {
            $qa_question_new->delta = StaticConfig::$Empty_Delta;
        }

        $qa_question_new->created_by = Auth::id();
        $qa_question_new->updated_by = Auth::id();
        $qa_question_new->save();
        return $qa_question_new;
    }

    public function deleteQuestion()
    {
        $id = $_GET['id'];
        $qa_question = QaQuestion::find($id);
        if (isset($qa_question->file_name)) {
            $oldFile = base_path() . StaticConfig::$Upload_Path_QaQuestion . $qa_question->file_name;
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        }
        $qa_question->delete();
    }

    public function dispQuestion()
    {
        $id1 = $_GET['id1'];
        $id2 = $_GET['id2'];
        $qa_question1 = QaQuestion::find($id1);
        $qa_question2 = QaQuestion::find($id2);

        $med = $qa_question2->display_order;
        $qa_question2->display_order = $qa_question1->display_order;
        $qa_question1->display_order = $med;

        $qa_question2->save();
        $qa_question1->save();
    }


    public function alignQuestion()
    {
        $id = $_GET['id'];
        $align = $_GET['align'];

        $qa_question = QaQuestion::find($id);
        $qa_question->alignment = $align;

        $qa_question->save();
    }

    public function editQuestion(Request $request)
    {
        $qa_question = QaQuestion::find($request->id);
        $type = $request->screen_classification;
        switch ($type) {
            case 1:
                $qa_question->content = $request->contentias;
                $qa_question->delta = $request->delta;
                break;
            case 2:
                $qa_question->comment = $request->comment;
                $qa_question->content = "none";
                if ($request->hasFile('image')) {
                    if (!Storage::exists(StaticConfig::$Upload_Path_QaQuestion)) {
                        Storage::makeDirectory(StaticConfig::$Upload_Path_QaQuestion, 0775, true); //creates directory
                    }

                    if (isset($qa_question->file_name)) {
                        $oldFile = base_path() . StaticConfig::$Upload_Path_QaQuestion . $qa_question->file_name;
                        if (File::exists($oldFile)) {
                            File::delete($oldFile);
                        }
                    }

                    if ($request->hasFile('image')) {
                        $stamp = now()->timestamp;
                        $fileName = $stamp . '.' . $request->file('image')->getClientOriginalName();
                        $qa_question->file_name = $fileName;
                        $request->file('image')->move(
                            base_path() . StaticConfig::$Upload_Path_QaQuestion, $fileName
                        );
                    }
                }
                $qa_question->height = $request->height;
                $qa_question->length = $request->length;
                break;
            case 3:
                $qa_question->content = $request->comment;
                if ($request->hasFile('file')) {
                    if (!Storage::exists(StaticConfig::$Upload_Path_QaQuestion)) {
                        Storage::makeDirectory(StaticConfig::$Upload_Path_QaQuestion, 0775, true); //creates directory
                    }

                    if (isset($qa_question->file_name)) {
                        $oldFile = base_path() . StaticConfig::$Upload_Path_QaQuestion . $qa_question->file_name;
                        if (File::exists($oldFile)) {
                            File::delete($oldFile);
                        }
                    }

                    if ($request->hasFile('file')) {
                        $stamp = now()->timestamp;
                        $fileName = $stamp . '.' . $request->file('file')->getClientOriginalName();
                        $qa_question->file_name = $fileName;
                        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                        $request->file('file')->move(
                            base_path() . StaticConfig::$Upload_Path_QaQuestion, $fileName
                        );
                    }
                }
                break;
            case 4:
                $qa_question->content = $request->contentias;
                $qa_question->height = $request->height;
                $qa_question->length = $request->length;
                break;
            case 5:
                $qa_question->content = $request->comment;
                $qa_question->file_size = $request->file_size;
                break;
        }
        $qa_question->save();
        if (isset($ext)) {
            $color = Common::generateColor($ext);
            $datas = array($qa_question, $color);
        } else {
            $datas = array($qa_question);
        }
        return $datas;
    }

    public function postAnswer(Request $request)
    {
        $qa_answer_new = new QaAnswer();
        $qa_answer_new->qa_id = $request->qa_id;
        $qa_answer_new->screen_classification = $request->screen_classification;
        $qa_answer_new->display_order = $request->display_order;
        $qa_answer_new->alignment = $request->alignment;

        $qa_answer_new->created_by = Auth::id();
        $qa_answer_new->updated_by = Auth::id();
        $qa_answer_new->save();
        return $qa_answer_new;
    }

    public function editAnswer(Request $request)
    {
        $qa_answer = QaAnswer::find($request->id);
        $type = $request->screen_classification;
        switch ($type) {
            case 1:
                $qa_answer->content = $request->contentias;
                $qa_answer->qa_linked = $request->qa_linked;
                break;
            case 2:
                $qa_answer->content = "none";
                $qa_answer->comment = $request->comment;
                if ($request->hasFile('image')) {
                    if (!Storage::exists(StaticConfig::$Upload_Path_QaAnswer)) {
                        Storage::makeDirectory(StaticConfig::$Upload_Path_QaAnswer, 0775, true); //creates directory
                    }

                    if (isset($qa_answer->file_name)) {
                        $oldFile = base_path() . StaticConfig::$Upload_Path_QaAnswer . $qa_answer->file_name;
                        if (File::exists($oldFile)) {
                            File::delete($oldFile);
                        }
                    }

                    if ($request->hasFile('image')) {
                        $stamp = now()->timestamp;
                        $fileName = $stamp . '.' . $request->file('image')->getClientOriginalName();
                        $qa_answer->file_name = $fileName;

                        $request->file('image')->move(
                            base_path() . StaticConfig::$Upload_Path_QaAnswer, $fileName
                        );
                    }
                }
                $qa_answer->height = $request->height;
                $qa_answer->length = $request->length;
                $qa_answer->qa_linked = $request->qa_linked;
                break;
        }
        $qa_answer->save();

        $stories = DB::table('stories')->selectRaw('id as storyID, story_name');
        $qa_linked = DB::table('qas')
            ->leftJoinSub($stories, 'storii', function ($join) {
                $join->on('qas.story_id', '=', 'storyID');
            })->selectRaw('id as qaLinkedID, CONCAT("(", storii.story_name, ") ", qas.screen_id, " ", qas.title) AS qaLinked, story_name');
        $qa_answer_core = DB::table('qa_answers')->where('id', $qa_answer->id);
        $qa_answer_updated = $qa_answer_core->joinSub($qa_linked, 'qaLink', function ($join) {
            $join->on('qa_linked', '=', 'qaLink.qaLinkedID');
        })->select('content', 'height', 'length', 'qaLinked', 'screen_classification', 'story_name', 'comment')
            ->get();

        return $qa_answer_updated;
    }

    public function deleteAnswer()
    {
        $id = $_GET['id'];
        $qa_question = QaAnswer::find($id);
        if (isset($qa_question->file_name)) {
            $oldFile = base_path() . StaticConfig::$Upload_Path_QaAnswer . $qa_question->file_name;
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        }
        $qa_question->delete();
    }

    public function dispAnswer()
    {
        $id1 = $_GET['id1'];
        $id2 = $_GET['id2'];
        $qa_question1 = QaAnswer::find($id1);
        $qa_question2 = QaAnswer::find($id2);

        $med = $qa_question2->display_order;
        $qa_question2->display_order = $qa_question1->display_order;
        $qa_question1->display_order = $med;

        $qa_question2->save();
        $qa_question1->save();
    }

    public function alignAnswer()
    {
        $id = $_GET['id'];
        $align = $_GET['align'];

        $qa_question = QaAnswer::find($id);
        $qa_question->alignment = $align;

        $qa_question->save();
    }
}