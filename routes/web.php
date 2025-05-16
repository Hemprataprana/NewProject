<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
 
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/all', [TaskController::class, 'showAll'])->name('tasks.all');
Route::get('/tasks/edit/{id}', [TaskController::class, 'edit'])->name('tasks.edit');
Route::post('/tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::post('/tasks/complete/{id}', [TaskController::class, 'complete'])->name('tasks.complete');