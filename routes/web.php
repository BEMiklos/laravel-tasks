<?php

use Illuminate\Support\Facades\Route;

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

use App\Models\Task;
use Illuminate\Http\Request;

/**
    * Show Task Dashboard
    */
Route::get('/', function () {
    error_log("INFO: get /");
    return view('tasks', [
        'tasks' => Task::orderBy('created_at', 'asc')->get(),
        'purchases' => Purchase::orderBy('created_at', 'asc')->get()
    ]);
});

/**
    * Add New Task
    */
Route::post('/task', function (Request $request) {
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'price' => 'required|numeric',
    ]);

    $task = new Task();
    $task->name = $validatedData['name'];
    $task->price = $validatedData['price'];
    $task->save();

    return redirect('/');
});

/**
    * Delete Task
    */
Route::delete('/task/{id}', function ($id) {
    error_log('INFO: delete /task/'.$id);
    Task::findOrFail($id)->delete();

    return redirect('/');
});
