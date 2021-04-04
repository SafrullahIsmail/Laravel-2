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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'PublicController@index')->name('index');
Route::get('post/{id}', 'PublicController@singlePost')->name('singlePost');
Route::get('about', 'PublicController@about')->name('about');
Route::get('contact', 'PublicController@contact')->name('contact');

Route::post('contact', 'PublicController@contactPost')->name('contactPost');

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::prefix('user')->group(function (){
    Route::get('dashboard', 'UserController@dashboard')->name('userDashboard');
    Route::get('comments', 'UserController@comments')->name('userComments');
        Route::post('comment/{id}/delete', 'UserController@deleteComment')->name('deleteComment');

    Route::get('profile', 'UserController@profile')->name('userProfile');
    Route::post('profile', 'UserController@profilePost')->name('userProfilePost');
});

Route::prefix('author')->group(function (){
    Route::get('dashboard', 'AuthorController@dashboard')->name('authorDashboard');
    Route::get('posts', 'AuthorController@posts')->name('authorPosts');
    Route::get('post/new', 'AuthorController@newPost')->name('newPost');
        Route::post('post/new', 'AuthorController@create')->name('createPost');
        Route::get('post/{id}/edit', 'AuthorController@postEdit')->name('postEdit');
        Route::post('post/{id}/edit', 'AuthorController@edit')->name('editPost');
        Route::post('post/{id}/delete', 'AuthorController@delete')->name('deletePost');
    Route::get('comments', 'AuthorController@comments')->name('authorComments');
});

Route::prefix('admin')->group(function (){
    Route::get('dashboard', 'AdminController@dashboard')->name('adminDashboard');
    Route::get('posts', 'AdminController@posts')->name('adminPosts');
        Route::get('post/{id}/edit', 'AuthorController@postEdit')->name('adminPostEdit');
        Route::post('post/{id}/edit', 'AuthorController@edit')->name('adminEditPost');
        Route::post('post/{id}/delete', 'AuthorController@delete')->name('adminDeletePost');
    Route::get('comments', 'AdminController@comments')->name('adminComments');
        Route::post('comment/{id}/delete', 'AdminController@deleteComment')->name('adminDeleteComment');
    Route::get('users', 'AdminController@users')->name('adminUsers');
});