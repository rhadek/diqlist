<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DailyAssignmentController;
use App\Http\Controllers\PointTransactionController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

// Autentizované routy
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/confirm-delete', [TaskController::class, 'confirmDelete'])->name('tasks.confirm-delete');
    Route::delete('/tasks/{task}/delete-recurring', [TaskController::class, 'deleteRecurring'])->name('tasks.delete-recurring');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/fail', [TaskController::class, 'fail'])->name('tasks.fail');
    Route::post('/tasks/{task}/assign-today', [TaskController::class, 'assignToday'])->name('tasks.assign-today');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('/categories/{category}/confirm-delete', [CategoryController::class, 'confirmDelete'])->name('categories.confirm-delete');
    Route::delete('/categories/{category}/delete-with-tasks', [CategoryController::class, 'deleteCategoryWithTasks'])->name('categories.delete-with-tasks');

    // Daily Assignments
    Route::get('/daily', [DailyAssignmentController::class, 'index'])->name('daily.index');
    Route::post('/daily/assign', [DailyAssignmentController::class, 'assign'])->name('daily.assign');
    Route::delete('/daily/{assignment}/unassign', [DailyAssignmentController::class, 'unassign'])->name('daily.unassign');
    Route::post('/daily/{assignment}/complete', [DailyAssignmentController::class, 'complete'])->name('daily.complete');
    Route::post('/daily/{assignment}/fail', [DailyAssignmentController::class, 'fail'])->name('daily.fail');
    Route::get('/daily/calendar', [DailyAssignmentController::class, 'calendar'])->name('daily.calendar');

    // Point Transactions
    Route::get('/points', [PointTransactionController::class, 'index'])->name('points.index');
    Route::get('/points/create', [PointTransactionController::class, 'create'])->name('points.create');
    Route::post('/points', [PointTransactionController::class, 'store'])->name('points.store');
    Route::get('/points/stats', [PointTransactionController::class, 'stats'])->name('points.stats');

    // Statistics
    Route::get('/stats', [StatisticsController::class, 'index'])->name('stats');
    Route::get('/stats/productivity', [StatisticsController::class, 'productivity'])->name('stats.productivity');
    Route::get('/stats/categories', [StatisticsController::class, 'categories'])->name('stats.categories');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
