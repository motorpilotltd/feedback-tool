<?php

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tag;
use App\Models\Product;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\IdeaController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\AdminController;

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

Route::get('/', [ProductController::class, 'index'])->name('product.index');

Route::prefix('product')->group(function () {
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('product.show');
    Route::get('{product:slug}/suggest-idea', [ProductController::class, 'suggestIdea'])->middleware(['auth'])->name('product.suggest.idea');
    Route::get('{product:slug}/progress', [ProductController::class, 'progress'])->name('product.progress');
    Route::get('{product}/{tag}', [ProductController::class, 'tag'])->name('product.tag');
});

Route::prefix('idea')->group(function () {
    Route::get('{idea:slug}', [IdeaController::class, 'show'])->name('idea.show');
    Route::get('{idea:slug}/edit', [IdeaController::class, 'edit'])->can('update', 'idea')->name('idea.edit');
});


Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

// Both product/super admin can access
$productPermission = config('const.PERMISSION_PRODUCTS_MANAGE');
Route::prefix('admin')->middleware(['auth', "can:{$productPermission}"])->group(function () {
    Route::get('/ideas', [AdminController::class, 'ideas'])->name('admin.ideas');
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/tags', [AdminController::class, 'tags'])->name('admin.tags');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
});

// Super admin can only access
$superadminRole = config('const.ROLE_SUPER_ADMIN');
Route::prefix('admin')->middleware(['auth', "role:{$superadminRole}"])->group(function () {
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
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
            'provider_platform' => 'azure'
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

Route::post('/user/loginas', [UserController::class, 'loginAs'])
    ->name('user.loginas');

// Search page
Route::get('/search', [SearchController::class, 'index'])->name('frontend.search.index');

Route::get('/attachments/{action}/{media:file_name}', [FileController::class, 'show'])
    ->middleware(['authFile'])
    ->name('file.attachments.show');

Route::get('/profile-photos/{filename}', [FileController::class, 'showProfilePhoto'])->name('file.profilephoto.show');;
