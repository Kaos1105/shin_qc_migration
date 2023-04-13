<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ActivityApprovalController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityOfficeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CircleController;
use App\Http\Controllers\CircleLevelController;
use App\Http\Controllers\CircleLevelOfficeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EducationalMaterialsCircleController;
use App\Http\Controllers\EducationalMaterialsController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KeepSortController;
use App\Http\Controllers\LibraryCircleController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NaviController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlanByMonthController;
use App\Http\Controllers\PlanByYearController;
use App\Http\Controllers\PromotionCircleController;
use App\Http\Controllers\PromotionCircleOfficeController;
use App\Http\Controllers\PromotionThemeController;
use App\Http\Controllers\QaController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ThemeOfficeController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ToppageController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\MemberMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::guest() && !Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('toppageoffice');
});

Auth::routes();
Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'doLogin'])->name('doLogin');
Route::get('logout', [LoginController::class, 'doLogout'])->name('doLogout');

Route::get('/home', [HomeController::class,'index'])->name('home');
Route::get('/cannot-login', [HomeController::class, 'cannotLogin'])->name('cannotLogin');
Route::get('/send-question', [HomeController::class, 'sendQuestion'])->name('sendQuestion');
Route::get('/update-session', [HomeController::class, 'updateSession'])->name('updateSession')->middleware('auth');
Route::get('/top-page', [ToppageController::class, 'index'])->name('toppageoffice')->middleware('auth');
Route::get('/test-session', [HomeController::class, 'testSession'])->name('testSession')->middleware('auth');
Route::get('/test-url',  [HomeController::class, 'testUrl'])->name('testUrl');

Route::post('/keep-sort', [KeepSortController::class, 'postSortKeys'])->name('keep-sort');

Route::middleware([AdminMiddleware::class, 'auth'])->group(function () {
    Route::resource('/user', UserController::class);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::get('/user/{id}/edit', [UserController::class, 'edit']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);

    Route::resource('/department', DepartmentController::class);
    Route::get('/department/{id}', [DepartmentController::class, 'show']);
    Route::get('/department/{id}/edit', [DepartmentController::class, 'edit']);
    Route::put('/department/{id}', [DepartmentController::class, 'update']);
    Route::delete('/department/{id}', [DepartmentController::class, 'destroy']);

    Route::resource('/place', PlaceController::class);
    Route::get('/place/{id}', [PlaceController::class, 'show']);
    Route::get('/place/{id}/edit', [PlaceController::class, 'edit']);
    Route::put('/place/{id}', [PlaceController::class, 'update']);
    Route::delete('/place/{id}', [PlaceController::class, 'destroy']);

    Route::resource('/circle', CircleController::class);
    Route::get('/circle/{id}', [CircleController::class, 'show']);
    Route::get('/circle/{id}/edit', [CircleController::class, 'edit']);
    Route::put('/circle/{id}', [CircleController::class, 'update']);
    Route::delete('/circle/{id}', [CircleController::class, 'destroy']);

    Route::resource('/member', MemberController::class);
    Route::get('/member/{id}/create', [MemberController::class, 'create']);
    Route::get('/member/{id}/edit', [MemberController::class, 'edit']);
    Route::put('/member/{id}', [MemberController::class, 'update']);
    Route::delete('/member/{id}', [MemberController::class, 'destroy']);

    Route::resource('/planbyyear', PlanByYearController::class);
    Route::get('/planbyyear/history', [PlanByYearController::class, 'show']);
    Route::get('/planbyyear/{year}/create', [PlanByYearController::class, 'create']);
    Route::get('/planbyyear/{id}/edit', [PlanByYearController::class, 'edit']);
    Route::put('/planbyyear/{id}', [PlanByYearController::class,'update']);

    Route::resource('/planbymonth', PlanByMonthController::class);
    Route::get('/planbymonth/{id}/create', [PlanByMonthController::class, 'create']);
    Route::get('/planbymonth/{id}/edit', [PlanByMonthController::class, 'edit']);
    Route::put('/planbymonth/{id}', [PlanByMonthController::class, 'update']);
    Route::delete('/planbymonth/{id}', [PlanByMonthController::class, 'destroy']);

    Route::resource('/notification', NotificationController::class)->except(['update']);
    Route::get('/notification/index', [NotificationController::class, 'index']);
    Route::get('/notification/create', [NotificationController::class, 'create'])->name('notification.create');
    Route::get('/notification/view', [NotificationController::class, 'show']);
    Route::get('/notification/copy/{id}', [NotificationController::class, 'copy'])->name('notification.copy');
    Route::get('/notification/{id}/edit', [NotificationController::class, 'edit']);
    Route::put('/notification/{id}', [NotificationController::class, 'update'])->name('notification.update');
    Route::get('notification/delete/{id}', [NotificationController::class, 'destroy'])->name('notification.delete');

    Route::resource('/library', LibraryController::class)->except(['show', 'update']);
    Route::get('/library/create', [LibraryController::class,'create'])->name('library.create');
    Route::get('/library/{id}', [LibraryController::class, 'show'])->name('library.show');
    Route::get('/library/{id}/edit', [LibraryController::class, 'edit']);
    Route::put('/library/{id}', [LibraryController::class, 'update'])->name('library.update');
    Route::get('/download/{filePath}', [LibraryController::class, 'download'])->name('library.download');
    Route::get('library/delete/{id}', [LibraryController::class, 'destroy'])->name('library.delete');

    Route::resource('/educational-materials', EducationalMaterialsController::class)->except(['show', 'update']);
    Route::get('/educational-materials/create', [EducationalMaterialsController::class, 'create'])->name('educational-materials.create');
    Route::get('/educational-materials/{id}', [EducationalMaterialsController::class, 'show'])->name('educational-materials.show');
    Route::get('/educational-materials/{id}/edit', [EducationalMaterialsController::class, 'edit']);
    Route::put('/educational-materials/{id}', [EducationalMaterialsController::class, 'update'])->name('educational-materials.update');
    Route::get('/educational-materials-download/{filePath}', [EducationalMaterialsController::class, 'download'])->name('educational-materials.download');
    Route::get('educational-materials/delete/{id}', [EducationalMaterialsController::class, 'destroy'])->name('educational-materials.delete');

    Route::resource('/homepage', HomepageController::class)->except(['show', 'update']);
    Route::get('/homepage/create', [HomepageController::class, 'create'])->name('homepage.create');
    Route::get('/homepage/{id}', [HomepageController::class, 'show'])->name('homepage.show');
    Route::get('/homepage/{id}/edit', [HomepageController::class, 'edit']);
    Route::put('/homepage/{id}', [HomepageController::class, 'update'])->name('homepage.update');
    Route::get('homepage/delete/{id}', [HomepageController::class, 'destroy'])->name('homepage.delete');

    Route::get('/promotion-circle-office', [PromotionCircleOfficeController::class, 'index']);
    Route::get('/promotion-circle-office/{id}',  [PromotionCircleOfficeController::class, 'show']);
    Route::post('/promotion-circle-office/remove-stamp',  [PromotionCircleOfficeController::class, 'removeStamp'])->name('promotion-circle-office.remove-stamp');

    Route::resource('/organization', OrganizationController::class );

    Route::resource('/circlelevel-office', CircleLevelOfficeController::class);
    //    Route::get('/circlelevel-office/{id}', 'CircleLevelOfficeController@index');

    Route::group(['prefix' => 'story'], function () {
        Route::get('list', [StoryController::class, 'getList'])->name('story.getList');
        Route::get('show/{id}', [StoryController::class, 'getShow'])->name('story.getShow');
        Route::get('add', [StoryController::class, 'getAdd'])->name('story.getAdd');
        Route::post('add', [StoryController::class, 'postAdd'])->name('story.postAdd');
        Route::post('delete/{id}', [StoryController::class, 'postDelete'])->name('story.postDelete');
        Route::get('edit/{id}', [StoryController::class, 'getEdit'])->name('story.getEdit');
        Route::post('edit/{id}', [StoryController::class, 'postEdit'])->name('story.postEdit');
        Route::post('change', [StoryController::class, 'postChangeDisplayOrder'])->name('story.postChangeDisplayOrder');
    });

    Route::group(['prefix' => 'qa'], function () {
        Route::get('list', [QaController::class, 'getList'])->name('qa.getList');
        Route::get('show/{id}', [QaController::class, 'getShow'])->name('qa.getShow');
        Route::get('add', [QaController::class, 'getAdd'])->name('qa.getAdd');
        Route::post('add', [QaController::class, 'postAdd'])->name('qa.postAdd');
        Route::post('delete/{id}', [QaController::class, 'postDelete'])->name('qa.postDelete');
        Route::get('edit/{id}', [QaController::class, 'getEdit'])->name('qa.getEdit');
        Route::post('edit/{id}', [QaController::class, 'postEdit'])->name('qa.postEdit');
        Route::post('change', [QaController::class, 'postChangeDisplayOrder'])->name('qa.postChangeDisplayOrder');
        Route::post('question-add', [QaController::class, 'postQuestion'])->name('qa.postQuestion');
        Route::get('question-delete', [QaController::class, 'deleteQuestion'])->name('qa.deleteQuestion');
        Route::get('question-disp', [QaController::class, 'dispQuestion'])->name('qa.dispQuestion');
        Route::get('question-align', [QaController::class, 'alignQuestion'])->name('qa.alignQuestion');
        Route::post('question-edit', [QaController::class, 'editQuestion'])->name('qa.editQuestion');
        Route::post('answer-add', [QaController::class, 'postAnswer'])->name('qa.postAnswer');
        Route::get('answer-delete', [QaController::class, 'deleteAnswer'])->name('qa.deleteAnswer');
        Route::get('answer-disp', [QaController::class, 'dispAnswer'])->name('qa.dispAnswer');
        Route::get('answer-align', [QaController::class, 'alignAnswer'])->name('qa.alignAnswer');
        Route::post('answer-edit', [QaController::class, 'editAnswer'])->name('qa.editAnswer');
    });

    Route::group(['prefix' => 'theme-office'], function () {
        Route::get('list', [ThemeOfficeController::class, 'getList'])->name('theme-office.getList');
        Route::get('show/{id}', [ThemeOfficeController::class, 'getShow'])->name('theme-office.getShow');
    });

    Route::group(['prefix' => 'activity-office'], function () {
        Route::get('list', [ActivityOfficeController::class, 'getList'])->name('activity-office.getList');
        Route::get('show/{id}', [ActivityOfficeController::class, 'getShow'])->name('activity-office.getShow');
    });
    Route::put('/activity-office/updateSecretariatEntry', [ActivityOfficeController::class, 'updateSecretariatEntry'])->name('activityoffice.updateSecretariatEntry');
    Route::get('/managementfile/downloadFile/{id}', [UploadFileController::class, 'downloadFile'])->name('uploadfile.downloadFileActivityManagement');
});

Route::group(['middleware' => 'auth'], function () {

    Route::resource('/category', CategoryController::class)->except(['update']);
    Route::get('/category/list', [CategoryController::class, 'index']);
    Route::get('/category/add', [CategoryController::class, 'create']);
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit']);
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    Route::resource('/thread', ThreadController::class)->except(['update']);
    Route::get('/thread/{id}', [ThreadController::class, 'show']);
    Route::get('/thread/add/{id}', [ThreadController::class, 'create']);
    Route::get('/thread/{id}/edit', [ThreadController::class, 'edit']);
    Route::put('/thread/{id}', [ThreadController::class, 'update'])->name('thread.update');
    Route::delete('/thread/{id}', [ThreadController::class, 'destroy']);

    Route::resource('/topic', TopicController::class);
    Route::get('/topic/{id}', [TopicController::class, 'show']);
    Route::get('/topic/add/{id}', [TopicController::class, 'create']);
    Route::get('/topic/{id}/edit', [TopicController::class, 'edit']);
    Route::get('topic/delete/{topic_id}/{thread_id}', [TopicController::class, 'destroy'])->name('topic.delete');

    Route::resource('/calendar', CalendarController::class)->except(['destroy', 'update']);
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::put('/calendar', [CalendarController::class, 'update'])->name('calendar.update');
    Route::put('/calendar', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    Route::resource('/theme', ThemeController::class);
    Route::get('/theme/{id}', [ThemeController::class, 'show']);

    Route::resource('/promotion-circle', PromotionCircleController::class);
    Route::get('/promotion-circle', [PromotionCircleController::class, 'index'])->name('promotioncircle.index');
    Route::get('/promotion-circle/history', [PromotionCircleController::class, 'show'])->name('promotioncircle.history');

    Route::resource('/circlelevel', CircleLevelController::class);

    Route::resource('/activity', ActivityController::class)->except(['destroy', 'update']);

    Route::resource('/library-circle', LibraryCircleController::class);
    Route::get('/download-circle/{filePath}', [LibraryCircleController::class, 'download'])->name('library-circle.download');

    Route::resource('/educational-materials-circle', EducationalMaterialsCircleController::class);
    Route::get('/educational-materials-download-circle/{filePath}', [EducationalMaterialsCircleController::class, 'download'])->name('educational-materials-circle.download');

    Route::group(['prefix' => 'qc-navi'], function () {
        Route::get('story-classification', [NaviController::class, 'getStoryClassification'])->name('navi.getStoryClassification');
        Route::get('story-list/{id}', [NaviController::class, 'getStoryList'])->name('navi.getStoryList');
        Route::get('story-navi', [NaviController::class, 'getStoryNavi'])->name('navi.NaviController');
        Route::get('story-navi-download', [NaviController::class, 'downloadNavi'])->name('navi.downloadNavi');
        Route::get('navi-history', [NaviController::class, 'getHistory'])->name('navi.history');
        Route::get('navi-detail/{history_id}', [NaviController::class, 'getDetail'])->name('navi.detail');
        Route::get('navi-finish/{history_id}/{classification}', [NaviController::class, 'finishNavi'])->name('navi.finish');
        Route::get('navi-delete-detail/{id}', [NaviController::class, 'deleteDetail'])->name('navi.deleteDetail');
        Route::post('navi-add-details', [NaviController::class, 'addDetails'])->name('navi.addDetails');
    });

    Route::get('qa/show-qa/{id}', [QaController::class, 'getShowQa'])->name('qa.getShowQa');
    Route::get('story/show-story/{id}', [StoryController::class, 'getShowStory'])->name('story.getShowStory');
});

Route::middleware(['auth', MemberMiddleware::class])->group(function () {

    Route::resource('/promotion-theme', PromotionThemeController::class);
    Route::get('/promotion-theme/{theme_id}/{index}/create', [PromotionThemeController::class, 'create']);
    Route::get('/promotion-theme/{id}/edit', [PromotionThemeController::class, 'edit']);
    Route::put('/promotion-theme/{id}', [PromotionThemeController::class, 'update'])->name('promotiontheme.update');
    Route::delete('/promotion-theme/{id}', [PromotionThemeController::class, 'destroy'])->name('promotiontheme.destroy');

    Route::get('/activity/create', [ActivityController::class, 'create']);
    Route::get('/activity/{id}/edit', [ActivityController::class, 'edit']);
    Route::put('/activity/{id}', [ActivityController::class, 'update'])->name('activity.update');
    Route::delete('/activity/{id}', [ActivityController::class, 'destroy'])->name('activity.destroy');

    Route::get('/activity-approval', [ActivityApprovalController::class, 'index'])->name('activityapproval.index');
    Route::post('/activity-approval/store', [ActivityApprovalController::class, 'store'])->name('activityapproval.store');
    Route::put('/activity-approval/{id}', [ActivityApprovalController::class, 'update'])->name('activityapproval.update');
    Route::post('/activity-approval/remove-stamp', [ActivityApprovalController::class, 'removeStamp'])->name('activityapproval.removeStamp');
    Route::post('/activity-approval/saveKaizenEditInLine', [ActivityApprovalController::class, 'saveKaizenEditInLine'])->name('activityapproval.saveKaizenEditInLine');

    Route::get('/circlelevel/create', [CircleLevelController::class, 'create']);
    Route::get('/circlelevel/{id}/edit', [CircleLevelController::class, 'store']);

    Route::get('/promotion-circle/create', [PromotionCircleController::class, 'create'])->name('promotioncircle.create');
    Route::get('/promotion-circle/{id}/edit', [PromotionCircleController::class, 'edit']);
    Route::put('/promotion-circle/{id}', [PromotionCircleController::class, 'update'])->name('promotioncircle.update');

    Route::get('/theme/create', [ThemeController::class, 'create']);
    Route::get('/theme/{id}/edit', [ThemeController::class, 'edit']);
    Route::delete('/theme/{id}', [ThemeController::class, 'destroy']);
    Route::get('/theme/report/{id}', [ThemeController::class, 'report'])->name('theme.report');



    Route::get('/test-export', [ExcelExportController::class, 'excelExport'])->name('excelExport')->middleware('auth');
    Route::post('/export-input', [ExcelExportController::class, 'dataInput'])->name('dataInput')->middleware('auth');

    Route::post('/upload-file/updateContentActivity', [UploadFileController::class, 'updateContentActivity'])->name('uploadfile.updateContentActivity');
    Route::post('/upload-file/updateRequestToBossFileActivity', [UploadFileController::class, 'updateRequestToBossFileActivity'])->name('uploadfile.updateRequestToBossFileActivity');
    Route::get('/upload-file/downloadFile/{id}', [UploadFileController::class, 'downloadFile'])->name('uploadfile.downloadFile');
});


