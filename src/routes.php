<?php

Route::group(['namespace' => 'FullyStudios\LaravelTeams\Controllers'], function()
{
    Route::get('/teams', 'TeamController@index')->name('teams.index');
    Route::get('/teams/create', 'TeamController@create')->name('teams.create');
    Route::get('/teams/{product}', 'TeamController@show')->name('teams.show');
    Route::post('/teams', 'TeamController@store')->name('teams.store');
    Route::delete('/teams/{product}', 'TeamController@destroy')->name('teams.delete');

});