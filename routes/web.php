<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileViewController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPhotoController;
use App\Http\Controllers\Admin\AdminMasterDataController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminBannerController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('update', [ProfileController::class, 'update'])->name('update');
        Route::put('basic-info', [ProfileController::class, 'updateBasicInfo'])->name('basic-info');
        Route::put('partner-preferences', [ProfileController::class, 'updatePartnerPreferences'])->name('partner-preferences');
        Route::put('privacy', [ProfileController::class, 'updatePrivacy'])->name('privacy');
        Route::put('password', [ProfileController::class, 'changePassword'])->name('password');
        Route::post('photo', [ProfileController::class, 'uploadPhoto'])->name('photo.upload');
        Route::delete('photo/{photo}', [ProfileController::class, 'deletePhoto'])->name('photo.delete');
        Route::post('photo/{photo}/primary', [ProfileController::class, 'setPrimaryPhoto'])->name('photo.primary');
        Route::post('deactivate', [ProfileController::class, 'deactivate'])->name('deactivate');
        Route::get('biodata/download', [ProfileController::class, 'downloadBiodata'])->name('biodata.download');
        Route::get('biodata/download/pdf', [ProfileController::class, 'downloadBiodataPdf'])->name('biodata.download.pdf');
    });
    
    // View Other Profiles
    Route::get('profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    
    // Matches
    Route::prefix('matches')->name('matches.')->group(function () {
        Route::get('daily', [MatchController::class, 'dailyMatches'])->name('daily');
        Route::get('all', [MatchController::class, 'allMatches'])->name('all');
        Route::get('mutual', [MatchController::class, 'mutualMatches'])->name('mutual');
        Route::get('shortlist', [MatchController::class, 'shortlist'])->name('shortlist');
        Route::post('shortlist/{user}', [MatchController::class, 'addToShortlist'])->name('shortlist.add');
        Route::delete('shortlist/{user}', [MatchController::class, 'removeFromShortlist'])->name('shortlist.remove');
        Route::get('ignored', [MatchController::class, 'ignoredProfiles'])->name('ignored');
        Route::post('ignore/{user}', [MatchController::class, 'ignoreProfile'])->name('ignore');
        Route::delete('ignore/{user}', [MatchController::class, 'unignoreProfile'])->name('unignore');
    });
    
    // Search
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [SearchController::class, 'index'])->name('index');
        Route::get('results', [SearchController::class, 'search'])->name('results');
        Route::get('quick', [SearchController::class, 'quickSearch'])->name('quick');
    });
    
    // Profile Views
    Route::prefix('views')->name('views.')->group(function () {
        Route::get('who-viewed', [ProfileViewController::class, 'whoViewedMe'])->name('who-viewed');
        Route::get('viewed-by-me', [ProfileViewController::class, 'viewedByMe'])->name('viewed-by-me');
        Route::get('contact-viewers', [ProfileViewController::class, 'contactViewers'])->name('contact-viewers');
        Route::post('contact/{user}', [ProfileViewController::class, 'viewContact'])->name('contact');
    });
    
    // Interests
    Route::prefix('interests')->name('interests.')->group(function () {
        Route::get('sent', [InterestController::class, 'sent'])->name('sent');
        Route::get('received', [InterestController::class, 'received'])->name('received');
        Route::get('accepted', [InterestController::class, 'accepted'])->name('accepted');
        Route::post('send/{user}', [InterestController::class, 'send'])->name('send');
        Route::post('{interest}/accept', [InterestController::class, 'accept'])->name('accept');
        Route::post('{interest}/reject', [InterestController::class, 'reject'])->name('reject');
        Route::delete('{interest}/cancel', [InterestController::class, 'cancel'])->name('cancel');
    });
    
    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('{user}', [ChatController::class, 'conversation'])->name('conversation');
        Route::get('{user}/messages', [ChatController::class, 'getMessages'])->name('messages');
        Route::post('{user}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::post('{user}/read', [ChatController::class, 'markAsRead'])->name('read');
        Route::get('unread/count', [ChatController::class, 'getUnreadCount'])->name('unread');
        Route::delete('message/{message}', [ChatController::class, 'deleteMessage'])->name('message.delete');
        Route::delete('conversation/{user}', [ChatController::class, 'deleteConversation'])->name('conversation.delete');
        Route::get('token', [ChatController::class, 'getChatToken'])->name('token');
    });
    
    // Subscription
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('plans', [SubscriptionController::class, 'plans'])->name('plans');
        Route::get('my', [SubscriptionController::class, 'mySubscription'])->name('my');
        Route::post('subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::post('cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('{user}', [AdminUserController::class, 'show'])->name('show');
        Route::post('{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('{user}/verify', [AdminUserController::class, 'verify'])->name('verify');
    });
    
    // Photo Moderation
    Route::prefix('photos')->name('photos.')->group(function () {
        Route::get('pending', [AdminPhotoController::class, 'pending'])->name('pending');
        Route::post('{photo}/approve', [AdminPhotoController::class, 'approve'])->name('approve');
        Route::post('{photo}/reject', [AdminPhotoController::class, 'reject'])->name('reject');
    });
    
    // Master Data Management
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('religions', [AdminMasterDataController::class, 'religions'])->name('religions');
        Route::post('religions', [AdminMasterDataController::class, 'storeReligion'])->name('religions.store');
        Route::put('religions/{religion}', [AdminMasterDataController::class, 'updateReligion'])->name('religions.update');
        
        Route::get('castes', [AdminMasterDataController::class, 'castes'])->name('castes');
        Route::post('castes', [AdminMasterDataController::class, 'storeCaste'])->name('castes.store');
        
        Route::get('mother-tongues', [AdminMasterDataController::class, 'motherTongues'])->name('mother-tongues');
        Route::post('mother-tongues', [AdminMasterDataController::class, 'storeMotherTongue'])->name('mother-tongues.store');
        
        Route::get('locations', [AdminMasterDataController::class, 'locations'])->name('locations');
        
        Route::get('educations', [AdminMasterDataController::class, 'educations'])->name('educations');
        Route::post('educations', [AdminMasterDataController::class, 'storeEducation'])->name('educations.store');
        
        Route::get('occupations', [AdminMasterDataController::class, 'occupations'])->name('occupations');
        Route::post('occupations', [AdminMasterDataController::class, 'storeOccupation'])->name('occupations.store');
    });
    
    // Plans Management
    Route::resource('plans', AdminPlanController::class);
    
    // Banners Management
    Route::resource('banners', AdminBannerController::class);
});

/*
|--------------------------------------------------------------------------
| AJAX Routes for Dependent Dropdowns
|--------------------------------------------------------------------------
*/

Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::get('castes/{religion}', [AdminMasterDataController::class, 'getCastes'])->name('castes');
    Route::get('subcastes/{caste}', [AdminMasterDataController::class, 'getSubcastes'])->name('subcastes');
    Route::get('states/{country}', [AdminMasterDataController::class, 'getStates'])->name('states');
    Route::get('cities/{state}', [AdminMasterDataController::class, 'getCities'])->name('cities');
});
