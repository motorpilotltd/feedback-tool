<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\IdeaController as AdminIdeaController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\IdeaController;
use App\Http\Controllers\Frontend\Product\IndexController as ProductIndexController;
use App\Http\Controllers\Frontend\Product\ProgressController as ProductProgressController;
use App\Http\Controllers\Frontend\Product\SuggestIdeaController as ProductSuggestIdeaController;
use App\Http\Controllers\Frontend\Product\TagController as ProductTagController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginAsController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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

Route::get('/', [ProductIndexController::class, 'index'])->name('product.index');

Route::prefix('product')->group(function () {
    Route::get('/{product:slug}', [ProductIndexController::class, 'show'])->name('product.show');
    Route::get('{product:slug}/suggest-idea', [ProductSuggestIdeaController::class, 'show'])->middleware(['auth'])->name('product.suggest.idea');
    Route::get('{product:slug}/progress', [ProductProgressController::class, 'show'])->name('product.progress');
    Route::get('{product}/{tag}', [ProductTagController::class, 'show'])->name('product.tag');
});

Route::prefix('idea')->group(function () {
    Route::get('{idea:slug}', [IdeaController::class, 'show'])->name('idea.show');
    Route::get('{idea:slug}/edit', [IdeaController::class, 'edit'])->can('update', 'idea')->name('idea.edit');
});

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

// Both product/super admin can access
$productPermission = config('const.PERMISSION_PRODUCTS_MANAGE');
Route::prefix('admin')->middleware(['auth', "can:{$productPermission}"])->group(function () {
    Route::get('/ideas', [AdminIdeaController::class, 'index'])->name('admin.ideas');
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
    Route::get('/tags', [AdminTagController::class, 'index'])->name('admin.tags');
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
});

// Super admin can only access
$superadminRole = config('const.ROLE_SUPER_ADMIN');
Route::prefix('admin')->middleware(['auth', "role:{$superadminRole}"])->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
});

Route::get('/auth/microsoft/login', function () {
    return Socialite::driver('azure')->redirect();
})->name('auth.microsoft');

Route::get('/auth/microsoft/callback', function () {
    try {
        $azureUser = Socialite::driver('azure')->user();
        $user = User::updateOrCreate([
            'provider_user_id' => $azureUser->id,
        ], [
            'name' => $azureUser->name,
            'email' => $azureUser->email,
            'provider_token' => $azureUser->token ?? '',
            'provider_platform' => 'azure',
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    } catch (Exception $e) {
        return redirect('login')->with('error', __('error.microsoft:login', ['message' => $e->getMessage() ?: 'None']));
    }
})->name('auth.microsoft.callback');

Route::get('/user/viewprofile/{user}', [UserController::class, 'show'])
    ->middleware(['auth'])
    ->name('user.viewprofile');

Route::get('/user/myprofile', [UserController::class, 'show'])
    ->middleware(['auth'])
    ->name('user.myprofile');

Route::post('/user/loginas', [UserLoginAsController::class, 'index'])
    ->name('user.loginas');

// Search page
Route::get('/search', [SearchController::class, 'index'])->name('frontend.search.index');

Route::get('/attachments/{action}/{media:file_name}', [MediaController::class, 'show'])
    ->middleware(['authFile'])
    ->name('file.attachments.show');

Route::get('/profile-photos/{filename}', [ProfilePhotoController::class, 'show'])->name('file.profilephoto.show');
