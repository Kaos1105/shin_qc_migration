<?php

namespace App\Http\Controllers;

use App\Enums\StaticConfig;
use App\Enums\UseClassificationEnum;
use Illuminate\Http\Request;
use App\Models\NaviDetails;
use App\Models\NaviHistory;
use App\Models\QaQuestion;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Thread;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Validator;
use File;
use Response;

class NaviController extends Controller
{
    public function getStoryClassification()
    {
        $story_classification_list = DB::table('stories')->whereNotNull('story_classification')->distinct()->pluck('story_classification');
        return view('navi.story-classification',
            [
                'story_classification_list' => $story_classification_list
            ]);
    }

    public function getStoryList($classification)
    {
        $qa_list = DB::table('qas')->pluck('story_id');
        $story_list = DB::table('stories')->where('use_classification', UseClassificationEnum::USES)->where('story_classification', $classification)
            ->whereIn('id', $qa_list)
            ->select('id', 'story_classification', 'story_name', 'description')
            ->orderBy('display_order', 'ASC')->get();

        return view('navi.story-list',
            [
                'story_list' => $story_list,
                'classification' => $classification
            ]);
    }

    public function getStoryNavi()
    {
        if (isset($_GET['historyId'])) {
            $hist = NaviHistory::find($_GET['historyId']);
            if (!isset($hist)) {
                abort(404);
            }
        }
        $qa_id = $_GET['qaId'];
        $story = DB::table('stories')->where('use_classification', UseClassificationEnum::USES)->selectRaw('stories.id as storyId, stories.story_name, stories.story_classification as classification');
        $qa = DB::table('qas')->leftJoinSub($story, 'story', function ($join) {
            $join->on('qas.story_id', '=', 'story.storyId');
        })->where('id', $qa_id)
            ->where('use_classification', UseClassificationEnum::USES)
            ->orderBy('display_order', 'asc')
            ->select('id', 'title', 'screen_id', 'story_name', 'classification', 'display_order', 'story_id')
            ->first();

        $navi_category = DB::table('categories')->where('deletable', 0)->first();
        $navi_category_id = isset($navi_category) ? $navi_category->id : null;
        if (!isset($navi_category)) {
            $category = new Category();
            $category->category_name = StaticConfig::$QC_Category;
            $category->is_display = 1;
            $category->display_order = 1;
            $category->statistic_classification = 2;
            $category->use_classification = 2;
            $category->deletable = 0;
            $category->created_by = Auth::id();
            $category->updated_by = Auth::id();
            $category->save();

            $navi_category_id = $category->id;
        }
        if (isset($_GET['newThread'])) {
            $category_id = $navi_category_id;
            $thread = new Thread();
            $thread->category_id = $category_id;
            $thread->thread_name = $qa->story_name . " " . $qa->screen_id . " " . $qa->title;
            $thread->circle_id = Session::has('circle') ? session('circle.id') : null;
            $thread->is_display = 2;
            $thread->display_order = 100;
            $thread->statistic_classification = 2;
            $thread->use_classification = 2;
            $thread->created_by = Auth::id();
            $thread->updated_by = Auth::id();
            $thread->save();
            $thread_id = $thread->id;
        } else {
            $history_id = $_GET['historyId'];
            $thread_id = DB::table('navi_histories')->where('id', $history_id)->value('thread_id');
        }


        if (isset($_GET['newThread'])) {
            $history = new NaviHistory();
            $history->date_start = date('Y/m/d H:i:s');
            $history->user_id = Auth::id();
            $history->story_id = $qa->story_id;
            $history->starting_qa = $qa_id;
            $history->thread_id = $thread_id;
            $history->done_status = 0;
            $history->created_by = Auth::id();
            $history->updated_by = Auth::id();
            $history->save();
            $history_id = $history->id;
        } else {
            $history_id = $_GET['historyId'];
        }

        return view('navi.story-navi',
            [
                'qa' => $qa,
                'prevId' => isset($_GET['prevId']) ? $_GET['prevId'] : null,
                'history_id' => $history_id
            ]
        );

    }

    public function addDetails(Request $request)
    {
        $detail = new NaviDetails();
        $detail->history_id = $request->history_id;
        $detail->qa_id = $request->qa_id;
        $detail->answer_id = $request->answer_id;

        $detail->date_answer = date('Y/m/d H:i:s');
        $detail->created_by = Auth::id();
        $detail->updated_by = Auth::id();
        $detail->save();

        $thread_id = DB::table('navi_histories')->where('id', $request->history_id)->value('thread_id');

        if (isset($request->text)) {
            for ($i = 0; $i < count($request->text); $i++) {
                if (!empty($request->text[$i])) {
                    $topic = new Topic();
                    $topic->thread_id = $thread_id;
                    $topic->topic = $request->text[$i];
                    $topic->created_by = Auth::id();
                    $topic->updated_by = Auth::id();
                    $topic->save();
                }
            }
        }
        if (isset($request->file)) {
            for ($i = 0; $i < count($request->file); $i++) {
                if ($request->hasFile('file')) {
                    $topic = new Topic();
                    $topic->thread_id = $thread_id;
                    $topic->topic = "qa-navi";

                    if (!Storage::exists(StaticConfig::$Path_Topic)) {
                        Storage::makeDirectory(StaticConfig::$Path_Topic, 0775, true); //creates directory
                    }

                    $stamp = now()->timestamp;
                    $fileName = $stamp . '.' . $request->file[$i]->getClientOriginalName();
                    $topic->file = $fileName;
                    $request->file[$i]->move(
                        base_path() . StaticConfig::$Path_Topic, $fileName
                    );


                    $topic->created_by = Auth::id();
                    $topic->updated_by = Auth::id();
                    $topic->save();
                }
            }
        }

        return array(
            'prev_id' => $request->prev_id,
            'qa_id' => $request->linked_id,
            'history_id' => $request->history_id
        );
    }

    public function downloadNavi()
    {
        $id = $_GET['id'];
        $question = QaQuestion::find($id);
        $fileOrigin = $question->file_name;
        $filePath = 'storage/uploaded-files/qa-question-upload/' . $question->file_name;

        $downloadName = substr($fileOrigin, stripos($fileOrigin, '.') + 1, strlen($fileOrigin) - stripos($fileOrigin, '.'));
        if (file_exists($filePath)) {
            return response()->download($filePath, $downloadName);
        } else {
            $validator = Validator::make([], []);
            $validator->getMessageBag()->add('download_error', StaticConfig::$File_Not_Exist);
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function finishNavi($history_id, $classification)
    {
        $history = NaviHistory::find($history_id);
        $history->done_status = 1;
        $history->save();

        return redirect(route('navi.getStoryList', $classification));
    }

    public function getHistory()
    {
        $sort = 'date_start';
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

        $user = DB::table('users')->selectRaw('users.id as userId, users.name as user_name');
        $story = DB::table('stories')->selectRaw('stories.id as storyId, stories.story_name, stories.story_classification');
        $qa = DB::table('qas')->selectRaw('qas.id as qaId, qas.screen_id, qas.title');
        $detail = DB::table('navi_details')->selectRaw('navi_details.history_id, MAX(navi_details.date_answer) as latest_qa')->groupBy('navi_details.history_id');
        $history_core = DB::table('navi_histories')
            ->leftJoinSub($user, 'user', function ($join) {
                $join->on('navi_histories.user_id', '=', 'user.userId');
            })->leftJoinSub($story, 'story', function ($join) {
                $join->on('navi_histories.story_id', '=', 'story.storyID');
            })->leftJoinSub($qa, 'qa', function ($join) {
                $join->on('navi_histories.starting_qa', '=', 'qa.qaId');
            })->leftJoinSub($detail, 'detail', function ($join) {
                $join->on('navi_histories.id', '=', 'detail.history_id');
            })->selectRaw('*, concat("(", story_classification, ") ", story_name) as storii, concat(screen_id, " ", title) as startQa, TIME_FORMAT(TIMEDIFF(ADDTIME(latest_qa, "00:01:00"), date_start), "%H:%i" ) as duration');

        if ($filter == 0) {
            $history = $history_core->orderBy($sort, $sortType)->paginate(20);
        } else {
            $history = $history_core->where('user_id', $filter)->orderBy($sort, $sortType)->paginate(20);
        }

        $user_list = $user->where('use_classification', UseClassificationEnum::USES)->distinct()->get();

        return view('navi.navi-history',
            [
                'paginate' => $history,
                'user_list' => $user_list,
                'filter' => $filter
            ]);
    }


    public function getDetail($history_id)
    {
        $story = DB::table('stories')
            ->leftJoin('qas', 'stories.id', '=', 'qas.story_id')
            ->selectRaw('qas.id AS qasID, stories.story_name, stories.id as story_id');
        $navHis = DB::table('navi_histories')->selectRaw('navi_histories.id as navHisID, navi_histories.date_start, navi_histories.done_status');
        $qa = DB::table('qas')->selectRaw('id as qaID, CONCAT(screen_id, " ",title) as qaTitle');
        $tb2 = DB::table('qa_answers')->selectRaw('id as answerID, CONCAT(display_order, " ", content) as answerChosen');
        $his_date_start = DB::table('navi_histories')->where('id', $history_id)->value('date_start');
        $tb3 = DB::table('navi_details as p')->where('history_id', $history_id)
            ->selectRaw('id AS id2, TIME_TO_SEC(TIMEDIFF(date_answer, IFNULL((SELECT date_answer FROM  navi_details p2 WHERE p2.ID < p.id AND history_id =' . $history_id . ' ORDER BY id DESC LIMIT 1 ),"' . $his_date_start . '" ))) AS secDiff');
        $detail_core = DB::table('navi_details')
            ->leftJoinSub($story, 'story', function ($join) {
                $join->on('navi_details.qa_id', '=', 'story.qasID');
            })->leftJoinSub($navHis, 'navHis', function ($join) {
                $join->on('navi_details.history_id', '=', 'navHis.navHisID');
            })->leftJoinSub($qa, 'qa', function ($join) {
                $join->on('qa_id', '=', 'qa.qaID');
            })->leftJoinSub($tb2, 'tb2', function ($join) {
                $join->on('answer_id', '=', 'tb2.answerID');
            })->leftJoinSub($tb3, 'tb3', function ($join) {
                $join->on('id', '=', 'tb3.id2');
            })->where('history_id', $history_id);

        $sort = 'date_answer';
        $sortType = 'asc';
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        if (isset($_GET['sortType']) && ($_GET['sortType'] == 'asc' || $_GET['sortType'] = 'desc')) {
            $sortType = $_GET['sortType'];
        }

        $detail = (clone $detail_core)->orderBy($sort, $sortType)->paginate(20);

        $user = DB::table('users')->selectRaw('users.id as userId, users.name as user_name');
        $story = DB::table('stories')->selectRaw('stories.id as storyId, stories.story_name, stories.story_classification');
        $qa = DB::table('qas')->selectRaw('qas.id as qaId, qas.screen_id, qas.title');
        $details = DB::table('navi_details')->selectRaw('navi_details.history_id, MAX(navi_details.date_answer) as latest_qa')->groupBy('navi_details.history_id');
        $history_core = DB::table('navi_histories')
            ->leftJoinSub($user, 'user', function ($join) {
                $join->on('navi_histories.user_id', '=', 'user.userId');
            })->leftJoinSub($story, 'story', function ($join) {
                $join->on('navi_histories.story_id', '=', 'story.storyID');
            })->leftJoinSub($qa, 'qa', function ($join) {
                $join->on('navi_histories.starting_qa', '=', 'qa.qaId');
            })->leftJoinSub($details, 'detail', function ($join) {
                $join->on('navi_histories.id', '=', 'detail.history_id');
            })->where('id', $history_id)
            ->selectRaw('*, concat(story_classification, " (", story_name, ") ", screen_id, " ", title) as historii, TIME_FORMAT(TIMEDIFF(ADDTIME(latest_qa, "00:01:00"), date_start), "%H:%i" ) as duration');
        $history = $history_core->first();

        $resume_qa = (clone $detail_core)->orderBy('id', 'desc')->select('history_id', 'qa_id', 'done_status')
            ->first();

        return view('navi.navi-detail',
            [
                'paginate' => $detail,
                'resume_qa' => $resume_qa,
                'history' => $history,
                'hist_id' => $history_id
            ]
        );
    }

    public function deleteDetail($history_id)
    {
        $history = NaviHistory::find($history_id);
        $history_detail = DB::table('navi_details')->where('history_id', $history_id);
        $history_detail->delete();
        $history->delete();
        return redirect()->route('navi.history');
    }
}
