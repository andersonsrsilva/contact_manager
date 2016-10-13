<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'Contacts@index');

Route::post('groups/store', ['uses' => 'Groups@store', 'as' => 'groups.store']);
Route::get('contacts/autocomplete', ['uses' => 'Contacts@autocomplete', 'as' => 'contacts.autocomplete']);

Route::resource('contacts', 'Contacts');
