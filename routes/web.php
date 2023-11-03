<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GroupChatController;

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


Route::post('/send-message', [MessageController::class, 'sendMessage']);
Route::get('/messages', [MessageController::class, 'fetchMessage']);
Route::get('/messages/{FriendId}', [MessageController::class, 'getUserMessage']);
Route::get('/private', [MessageController::class, 'privateChat']);
Route::get('/get-users', [MessageController::class, 'getUsers']);
Route::get('/private-message/{user}', [MessageController::class, 'getUserMessage']);
Route::post('/private-message/{user}', [MessageController::class, 'sendPrivateMessage']);




Route::middleware(['auth'])->group(function () {
    Route::get('/groupChat', [GroupChatController::class, 'groupChat']);
    Route::get('/get-groups', [GroupChatController::class, 'getGroups']);
    Route::get('/conversation/{GroupId}', [GroupChatController::class, 'getMessages']);
    Route::post('/create-message', [GroupChatController::class, 'sendMessage']);
    Route::post('/create-local-image', [GroupChatController::class, 'CreateLocalImage']);
    Route::post('/delete-local-image', [GroupChatController::class, 'deleteLocalImage']);

    Route::post('/add-new-group', [GroupChatController::class, 'AddNewGroup']);
    Route::post('/delete-group', [GroupChatController::class, 'deleteGroup']);
    Route::get('/getUnreadNotifications', [GroupChatController::class, 'getUnreadNotifications']);
});

Auth::routes();
