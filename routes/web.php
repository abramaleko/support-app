<?php

use App\Http\Controllers\IssuesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('issues')->group(function(){

        Route::get('/all',[IssuesController::class,'allIssues'])->name('issues.all');

        Route::post('/new-issue',[IssuesController::class,'newIssue'])->name('issues.new');
    });


    Route::get('/settings',[SettingsController::class,'showSettings'])->name('settings');
    Route::post('setting/new-issue-category',[SettingsController::class,'newCategory'])->name('settings.issues-newCategory');
    Route::post('settings/new-staff',[SettingsController::class,'newStaff'])->name('settings.newStaff');
    Route::post('/settings/staff/update-permissions',[SettingsController::class,'updatePermissions'])->name('settings.staff.updatePermissions');

});

require __DIR__.'/auth.php';
;
