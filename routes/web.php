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

use App\Http\Controllers\CircleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeepSortController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlanByYearController;
use App\Http\Controllers\ToppageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::guest() && !Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('toppageoffice');
});

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

Route::group(['middleware' => ['App\Http\Middleware\AdminMiddleware', 'auth']], function () {
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
    Route::get('/planbyyear/{year}/create', 'PlanByYearController@create');
    Route::get('/planbyyear/{id}/edit', 'PlanByYearController@edit');
    Route::put('/planbyyear/{id}', 'PlanByYearController@update');

    Route::resource('/planbymonth', 'PlanByMonthController');
    Route::get('/planbymonth/{id}/create', 'PlanByMonthController@create');
    Route::get('/planbymonth/{id}/edit', 'PlanByMonthController@edit');
    Route::put('/planbymonth/{id}', 'PlanByMonthController@update');
    Route::delete('/planbymonth/{id}', 'PlanByMonthController@destroy');

    Route::resource('/notification', 'NotificationController')->except(['update']);
    Route::get('/notification/index', 'NotificationController@index');
    Route::get('/notification/create', 'NotificationController@create')->name('notification.create');
    Route::get('/notification/view', 'NotificationController@show');
    Route::get('/notification/copy/{id}', 'NotificationController@copy')->name('notification.copy');
    Route::get('/notification/{id}/edit', 'NotificationController@edit');
    Route::put('/notification/{id}', 'NotificationController@update')->name('notification.update');
    Route::get('notification/delete/{id}', ['as' => 'notification.delete', 'uses' => 'NotificationController@destroy']);

    Route::resource('/library', 'LibraryController')->except(['show', 'update']);
    Route::get('/library/create', 'LibraryController@create')->name('library.create');
    Route::get('/library/{id}', 'LibraryController@show')->name('library.show');
    Route::get('/library/{id}/edit', 'LibraryController@edit');
    Route::put('/library/{id}', 'LibraryController@update')->name('library.update');
    Route::get('/download/{filePath}', ['as' => 'library.download', 'uses' => 'LibraryController@download']);
    Route::get('library/delete/{id}', ['as' => 'library.delete', 'uses' => 'LibraryController@destroy']);

    Route::resource('/educational-materials', 'EducationalMaterialsController')->except(['show', 'update']);
    Route::get('/educational-materials/create', 'EducationalMaterialsController@create')->name('educational-materials.create');
    Route::get('/educational-materials/{id}', 'EducationalMaterialsController@show')->name('educational-materials.show');
    Route::get('/educational-materials/{id}/edit', 'EducationalMaterialsController@edit');
    Route::put('/educational-materials/{id}', 'EducationalMaterialsController@update')->name('educational-materials.update');
    Route::get('/educational-materials-download/{filePath}', ['as' => 'educational-materials.download', 'uses' => 'EducationalMaterialsController@download']);
    Route::get('educational-materials/delete/{id}', ['as' => 'educational-materials.delete', 'uses' => 'EducationalMaterialsController@destroy']);

    Route::resource('/homepage', 'HomepageController')->except(['show', 'update']);
    Route::get('/homepage/create', 'HomepageController@create')->name('homepage.create');
    Route::get('/homepage/{id}', 'HomepageController@show')->name('homepage.show');
    Route::get('/homepage/{id}/edit', 'HomepageController@edit');
    Route::put('/homepage/{id}', 'HomepageController@update')->name('homepage.update');
    Route::get('homepage/delete/{id}', ['as' => 'homepage.delete', 'uses' => 'HomepageController@destroy']);

    Route::get('/promotion-circle-office', 'PromotionCircleOfficeController@index');
    Route::get('/promotion-circle-office/{id}', 'PromotionCircleOfficeController@show');
    Route::post('/promotion-circle-office/remove-stamp', 'PromotionCircleOfficeController@removeStamp')->name('promotion-circle-office.remove-stamp');

    Route::resource('/organization', 'OrganizationController');

    Route::resource('/circlelevel-office', 'CircleLevelOfficeController');
    //    Route::get('/circlelevel-office/{id}', 'CircleLevelOfficeController@index');

    Route::group(['prefix' => 'story'], function () {
        Route::get('list', ['as' => 'story.getList', 'uses' => 'StoryController@getList']);
        Route::get('show/{id}', ['as' => 'story.getShow', 'uses' => 'StoryController@getShow']);
        Route::get('add', ['as' => 'story.getAdd', 'uses' => 'StoryController@getAdd']);
        Route::post('add', ['as' => 'story.postAdd', 'uses' => 'StoryController@postAdd']);
        Route::post('delete/{id}', ['as' => 'story.postDelete', 'uses' => 'StoryController@postDelete']);
        Route::get('edit/{id}', ['as' => 'story.getEdit', 'uses' => 'StoryController@getEdit']);
        Route::post('edit/{id}', ['as' => 'story.postEdit', 'uses' => 'StoryController@postEdit']);
        Route::post('change', ['as' => 'story.postChangeDisplayOrder', 'uses' => 'StoryController@postChangeDisplayOrder']);
    });

    Route::group(['prefix' => 'qa'], function () {
        Route::get('list', ['as' => 'qa.getList', 'uses' => 'QaController@getList']);
        Route::get('show/{id}', ['as' => 'qa.getShow', 'uses' => 'QaController@getShow']);
        Route::get('add', ['as' => 'qa.getAdd', 'uses' => 'QaController@getAdd']);
        Route::post('add', ['as' => 'qa.postAdd', 'uses' => 'QaController@postAdd']);
        Route::post('delete/{id}', ['as' => 'qa.postDelete', 'uses' => 'QaController@postDelete']);
        Route::get('edit/{id}', ['as' => 'qa.getEdit', 'uses' => 'QaController@getEdit']);
        Route::post('edit/{id}', ['as' => 'qa.postEdit', 'uses' => 'QaController@postEdit']);
        Route::post('change', ['as' => 'qa.postChangeDisplayOrder', 'uses' => 'QaController@postChangeDisplayOrder']);
        Route::post('question-add', ['as' => 'qa.postQuestion', 'uses' => 'QaController@postQuestion']);
        Route::get('question-delete', ['as' => 'qa.deleteQuestion', 'uses' => 'QaController@deleteQuestion']);
        Route::get('question-disp', ['as' => 'qa.dispQuestion', 'uses' => 'QaController@dispQuestion']);
        Route::get('question-align', ['as' => 'qa.alignQuestion', 'uses' => 'QaController@alignQuestion']);
        Route::post('question-edit', ['as' => 'qa.editQuestion', 'uses' => 'QaController@editQuestion']);
        Route::post('answer-add', ['as' => 'qa.postAnswer', 'uses' => 'QaController@postAnswer']);
        Route::get('answer-delete', ['as' => 'qa.deleteAnswer', 'uses' => 'QaController@deleteAnswer']);
        Route::get('answer-disp', ['as' => 'qa.dispAnswer', 'uses' => 'QaController@dispAnswer']);
        Route::get('answer-align', ['as' => 'qa.alignAnswer', 'uses' => 'QaController@alignAnswer']);
        Route::post('answer-edit', ['as' => 'qa.editAnswer', 'uses' => 'QaController@editAnswer']);
    });

    Route::group(['prefix' => 'theme-office'], function () {
        Route::get('list', ['as' => 'theme-office.getList', 'uses' => 'ThemeOfficeController@getList']);
        Route::get('show/{id}', ['as' => 'theme-office.getShow', 'uses' => 'ThemeOfficeController@getShow']);
    });

    Route::group(['prefix' => 'activity-office'], function () {
        Route::get('list', ['as' => 'activity-office.getList', 'uses' => 'ActivityOfficeController@getList']);
        Route::get('show/{id}', ['as' => 'activity-office.getShow', 'uses' => 'ActivityOfficeController@getShow']);
    });
    Route::put('/activity-office/updateSecretariatEntry', 'ActivityOfficeController@updateSecretariatEntry')->name('activityoffice.updateSecretariatEntry');
    Route::get('/managementfile/downloadFile/{id}', ['as' => 'uploadfile.downloadFileActivityManagement', 'uses' => 'UploadFileController@downloadFile']);
});

Route::group(['middleware' => 'auth'], function () {

    Route::resource('/category', 'CategoryController')->except(['update']);
    Route::get('/category/list', 'CategoryController@list');
    Route::get('/category/add', 'CategoryController@add');
    Route::get('/category/{id}/edit', 'CategoryController@edit');
    Route::put('/category/{id}', 'CategoryController@update')->name('category.update');
    Route::delete('/category/{id}', 'CategoryController@destroy');

    Route::resource('/thread', 'ThreadController')->except(['update']);
    Route::get('/thread/{id}', 'ThreadController@show');
    Route::get('/thread/add/{id}', 'ThreadController@create');
    Route::get('/thread/{id}/edit', 'ThreadController@edit');
    Route::put('/thread/{id}', 'ThreadController@update')->name('thread.update');
    Route::delete('/thread/{id}', 'ThreadController@destroy');

    Route::resource('/topic', 'TopicController');
    Route::get('/topic/{id}', 'TopicController@show');
    Route::get('/topic/add/{id}', 'TopicController@create');
    Route::get('/topic/{id}/edit', 'TopicController@edit');
    Route::get('topic/delete/{topic_id}/{thread_id}', ['as' => 'topic.delete', 'uses' => 'TopicController@destroy']);

    Route::resource('/calendar', 'CalendarController')->except(['destroy', 'update']);
    Route::get('/calendar', 'CalendarController@index')->name('calendar.index');
    Route::put('/calendar', 'CalendarController@update')->name('calendar.update');
    Route::put('/calendar', 'CalendarController@destroy')->name('calendar.destroy');

    Route::resource('/theme', 'ThemeController');
    Route::get('/theme/{id}', 'ThemeController@show');

    Route::resource('/promotion-circle', 'PromotionCircleController');
    Route::get('/promotion-circle', 'PromotionCircleController@index')->name('promotioncircle.index');
    Route::get('/promotion-circle/history', 'PromotionCircleController@show')->name('promotioncircle.history');

    Route::resource('/circlelevel', 'CircleLevelController');

    Route::resource('/activity', 'ActivityController')->except(['destroy', 'update']);

    Route::resource('/library-circle', 'LibraryCircleController');
    Route::get('/download-circle/{filePath}', ['as' => 'library-circle.download', 'uses' => 'LibraryCircleController@download']);

    Route::resource('/educational-materials-circle', 'EducationalMaterialsCircleController');
    Route::get('/educational-materials-download-circle/{filePath}', ['as' => 'educational-materials-circle.download', 'uses' => 'EducationalMaterialsCircleController@download']);

    Route::group(['prefix' => 'qc-navi'], function () {
        Route::get('story-classification', ['as' => 'navi.getStoryClassification', 'uses' => 'NaviController@getStoryClassification']);
        Route::get('story-list/{id}', ['as' => 'navi.getStoryList', 'uses' => 'NaviController@getStoryList']);
        Route::get('story-navi', ['as' => 'navi.getStoryNavi', 'uses' => 'NaviController@getStoryNavi']);
        Route::get('story-navi-download', ['as' => 'navi.downloadNavi', 'uses' => 'NaviController@downloadNavi']);
        Route::get('navi-history', ['as' => 'navi.history', 'uses' => 'NaviController@getHistory']);
        Route::get('navi-detail/{history_id}', ['as' => 'navi.detail', 'uses' => 'NaviController@getDetail']);
        Route::get('navi-finish/{history_id}/{classification}', ['as' => 'navi.finish', 'uses' => 'NaviController@finishNavi']);
        Route::get('navi-delete-detail/{id}', ['as' => 'navi.deleteDetail', 'uses' => 'NaviController@deleteDetail']);
        Route::post('navi-add-details', ['as' => 'navi.addDetails', 'uses' => 'NaviController@addDetails']);
    });

    Route::get('qa/show-qa/{id}', ['as' => 'qa.getShowQa', 'uses' => 'QaController@getShowQa']);
    Route::get('story/show-story/{id}', ['as' => 'story.getShowStory', 'uses' => 'StoryController@getShowStory']);
});

Route::group(['middleware' => ['auth', 'App\Http\Middleware\MemberMiddleware']], function () {

    Route::resource('/promotion-theme', 'PromotionThemeController');
    Route::get('/promotion-theme/{theme_id}/{index}/create', 'PromotionThemeController@create');
    Route::get('/promotion-theme/{id}/edit', 'PromotionThemeController@edit');
    Route::put('/promotion-theme/{id}', 'PromotionThemeController@update')->name('promotiontheme.update');
    Route::delete('/promotion-theme/{id}', 'PromotionThemeController@destroy')->name('promotiontheme.destroy');

    Route::get('/activity/create', 'ActivityController@create');
    Route::get('/activity/{id}/edit', 'ActivityController@edit');
    Route::put('/activity/{id}', 'ActivityController@update')->name('activity.update');
    Route::delete('/activity/{id}', 'ActivityController@destroy')->name('activity.destroy');

    Route::get('/activity-approval', 'ActivityApprovalController@index')->name('activityapproval.index');
    Route::post('/activity-approval/store', 'ActivityApprovalController@store')->name('activityapproval.store');
    Route::put('/activity-approval/{id}', 'ActivityApprovalController@update')->name('activityapproval.update');
    Route::post('/activity-approval/remove-stamp', 'ActivityApprovalController@removeStamp')->name('activityapproval.removeStamp');
    Route::post('/activity-approval/saveKaizenEditInLine', 'ActivityApprovalController@saveKaizenEditInLine')->name('activityapproval.saveKaizenEditInLine');

    Route::get('/circlelevel/create', 'CircleLevelController@create');
    Route::get('/circlelevel/{id}/edit', 'CircleLevelController@edit');

    Route::get('/promotion-circle/create', 'PromotionCircleController@create')->name('promotioncircle.create');
    Route::get('/promotion-circle/{id}/edit', 'PromotionCircleController@edit');
    Route::put('/promotion-circle/{id}', 'PromotionCircleController@update')->name('promotioncircle.update');

    Route::get('/theme/create', 'ThemeController@create');
    Route::get('/theme/{id}/edit', 'ThemeController@edit');
    Route::delete('/theme/{id}', 'ThemeController@destroy');
    Route::get('/theme/report/{id}', 'ThemeController@report')->name('theme.report');



    Route::get('/test-export', 'ExcelExportController@excelExport')->name('excelExport')->middleware('auth');
    Route::post('/export-input', 'ExcelExportController@dataInput')->name('dataInput')->middleware('auth');

    Route::post('/upload-file/updateContentActivity', 'UploadFileController@updateContentActivity')->name('uploadfile.updateContentActivity');
    Route::post('/upload-file/updateRequestToBossFileActivity', 'UploadFileController@updateRequestToBossFileActivity')->name('uploadfile.updateRequestToBossFileActivity');
    Route::get('/upload-file/downloadFile/{id}', 'UploadFileController@downloadFile')->name('uploadfile.downloadFile');
});


