<?php

/*
|--------------------------------------------------------------------------
| Backpack\LogManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\LogManager package.
|
*/

Route::group([
    'namespace'  => 'App\Http\Controllers\Admin',
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
], function () {
    Route::get('log', 'LogCrudController@index')->name('log.index');
    Route::get('log/preview/{file_name}', 'LogCrudController@preview')->name('log.show');
    Route::get('log/download/{file_name}', 'LogCrudController@download')->name('log.download');
    Route::delete('log/delete/{file_name}', 'LogCrudController@delete')->name('log.destroy');
});
