<?php

use App\Http\Controllers\IssuesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffIssuesController;
use App\Http\Controllers\UserIssueController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified'])->group(function () {
    //profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    //issues routes
    Route::prefix('issues')->group(function () {
        Route::get('/all', [IssuesController::class, 'allIssues'])->name('issues.all');
        Route::post('/new-issue', [IssuesController::class, 'newIssue'])->name('issues.new');
    });

    Route::middleware('isStaff')->prefix('/staff')->group(function(){
        //staff issue messages
        Route::get('/messages',[StaffIssuesController::class,'staffChats'])->name('staff.messages');
        Route::get('/message/{issue_id}',[StaffIssuesController::class,'viewChat'])->name('staff.view-message');
        Route::post('/assign-issue',[StaffIssuesController::class,'getAssignedIssue'])->name('staff.get-assigned-issue');
        Route::post('/send-message',[StaffIssuesController::class,'sendMessage'])->name('staff.send-message');

        Route::post('/close-issue',[StaffIssuesController::class,'closeIssue'])->name('close-issue');
    });

    //normal user meesages
    Route::prefix('messages')->group(function(){
        Route::get('/',[UserIssueController::class,'getMessages'])->name('messages');
        Route::get('/{issue_id}',[UserIssueController::class,'viewChat'])->name('user.view-message');
        Route::post('/send-message',[UserIssueController::class,'sendMessage'])->name('user.send-message');

    });

    //admin settings routes
    Route::prefix('/settings')->group(function () {
        Route::get('/', [SettingsController::class, 'showSettings'])->name('settings');
        Route::post('/new-issue-category', [SettingsController::class, 'newCategory'])->name('settings.issues-newCategory');
        Route::post('/new-staff', [SettingsController::class, 'newStaff'])->name('settings.newStaff');
        Route::post('/staff/update-permissions', [SettingsController::class, 'updatePermissions'])->name('settings.staff.updatePermissions');
    });

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

require __DIR__ . '/auth.php';;
