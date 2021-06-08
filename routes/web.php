<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', 'TaskController', [
        'only' => [
            'index', 'store', 'update'
        ]
    ]);
});
