<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

 

Auth::routes();
Route::get('/', [App\Http\Controllers\TaskController::class, 'index'])->name('rooturl');

Route::middleware(['auth'])->group(function () {
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/subtasks', [TaskController::class, 'addSubtask'])->name('subtasks.store');
    Route::get('/subtasks/data/{task}', [TaskController::class, 'fetchSubTaskData'])->name('subtasks.data');

});


Route::post('/tasks/{task}/subtasks', [TaskController::class, 'storeSubtask'])->middleware('auth');