<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileViewController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AdminMasterDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Current User
    Route::get('/user', function (Request $request) {
        return $request->user()->load('profile');
    });
    
    // Profile API
    Route::prefix('profile')->group(function () {
        Route::get('{id}', [ProfileController::class, 'show']);
        Route::put('update', [ProfileController::class, 'update']);
        Route::post('photo', [ProfileController::class, 'uploadPhoto']);
        Route::delete('photo/{photo}', [ProfileController::class, 'deletePhoto']);
    });
    
    // Matches API
    Route::prefix('matches')->group(function () {
        Route::get('daily', [MatchController::class, 'dailyMatches']);
        Route::get('all', [MatchController::class, 'allMatches']);
        Route::get('mutual', [MatchController::class, 'mutualMatches']);
        Route::post('shortlist/{user}', [MatchController::class, 'addToShortlist']);
        Route::delete('shortlist/{user}', [MatchController::class, 'removeFromShortlist']);
        Route::post('ignore/{user}', [MatchController::class, 'ignoreProfile']);
        Route::delete('ignore/{user}', [MatchController::class, 'unignoreProfile']);
    });
    
    // Search API
    Route::prefix('search')->group(function () {
        Route::get('/', [SearchController::class, 'search']);
        Route::get('quick', [SearchController::class, 'quickSearch']);
    });
    
    // Profile Views API
    Route::prefix('views')->group(function () {
        Route::get('who-viewed', [ProfileViewController::class, 'whoViewedMe']);
        Route::get('viewed-by-me', [ProfileViewController::class, 'viewedByMe']);
        Route::post('contact/{user}', [ProfileViewController::class, 'viewContact']);
    });
    
    // Interests API
    Route::prefix('interests')->group(function () {
        Route::get('sent', [InterestController::class, 'sent']);
        Route::get('received', [InterestController::class, 'received']);
        Route::get('accepted', [InterestController::class, 'accepted']);
        Route::post('send/{user}', [InterestController::class, 'send']);
        Route::post('{interest}/accept', [InterestController::class, 'accept']);
        Route::post('{interest}/reject', [InterestController::class, 'reject']);
        Route::delete('{interest}/cancel', [InterestController::class, 'cancel']);
    });
    
    // Chat API
    Route::prefix('chat')->group(function () {
        Route::get('conversations', [ChatController::class, 'index']);
        Route::get('{user}/messages', [ChatController::class, 'getMessages']);
        Route::post('{user}/send', [ChatController::class, 'sendMessage']);
        Route::post('{user}/read', [ChatController::class, 'markAsRead']);
        Route::get('unread/count', [ChatController::class, 'getUnreadCount']);
        Route::delete('message/{message}', [ChatController::class, 'deleteMessage']);
        Route::get('token', [ChatController::class, 'getChatToken']);
    });
    
    // Subscription API
    Route::prefix('subscription')->group(function () {
        Route::get('plans', [SubscriptionController::class, 'plans']);
        Route::get('my', [SubscriptionController::class, 'mySubscription']);
        Route::post('subscribe/{plan}', [SubscriptionController::class, 'subscribe']);
    });
});

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Master Data (for dropdowns)
Route::prefix('master')->group(function () {
    Route::get('castes/{religion}', [AdminMasterDataController::class, 'getCastes']);
    Route::get('subcastes/{caste}', [AdminMasterDataController::class, 'getSubcastes']);
    Route::get('states/{country}', [AdminMasterDataController::class, 'getStates']);
    Route::get('cities/{state}', [AdminMasterDataController::class, 'getCities']);
});
