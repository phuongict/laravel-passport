<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('vue/cp1',function (){
   return view('vue.cp1');
});
Route::get('vue/cp2',function (){
    return view('vue.cp2');
});
Route::get('vue/cp3',function (){
    return view('vue.cp3');
});
Route::get('passport/test',function (){
    return view('passport.test');
});