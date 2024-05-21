<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use App\Services\Idea\IdeaSpamService;
use App\Services\Idea\IdeaVoteService;
use App\Settings\AzureADSettings;
use App\Settings\GeneralSettings;
use Database\Seeders\RoleAndPermissionSeeder;

use function Pest\Faker\fake;
use Illuminate\Support\Str;


uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

// Group testing by folder
uses()->group('frontend')->in('Feature/Frontend');
/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return test()->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function login($user = null)
{
    $user = $user ?? User::factory()->create();
    return test()->actingAs($user);
}

function createIdeaWithUser($user = null, $product = null)
{
    $user = $user ?? User::factory()->create();
    $product = $product ?? Product::factory()->create();
    $category = $product->categories->first();

    $status = Status::factory()->create([
        'name' => 'Awaiting Consideration',
        'slug' => Str::slug('Awaiting Consideration', '')
    ]);
    return Idea::factory()->create([
        'title' => 'Lorem ipsum dolor sit amet',
        'category_id' => $category,
        'author_id' => $user->id,
        'status' => $status->slug
    ]);
}


function setupSettings()
{
    setupAzureSettings();
    setupGeneralSettings();
}

function setupAzureSettings()
{
    test()->azureSettings =  resolve(AzureADSettings::class);
}

function setupGeneralSettings()
{
    test()->generalSettings =  resolve(GeneralSettings::class);
}

function setupData()
{
    test()->product1 = Product::factory()->create(['name' => fake()->unique()->name]);
    test()->product2 = Product::factory()->create(['name' => fake()->unique()->name]);
    // Categories
    test()->category1 = Category::factory()->create(['product_id' => test()->product1]);
    test()->category2 = Category::factory()->create(['product_id' => test()->product1]);
    test()->category3 = Category::factory()->create(['product_id' => test()->product1]);

    test()->category4 = Category::factory()->create(['product_id' =>  Product::factory()->create(['name' => "A product name"])]);

    test()->status1 = Status::factory()->create([
        'name' =>'Status Alpha',
        'slug' => 'statusalpha',
        'color' => 'red',
    ]);
    test()->status2 = Status::factory()->create([
        'name' => 'Status Beta',
        'slug' => 'statusbeta',
        'color' => 'green',
    ]);

     // Ideas
    test()->idea1 = Idea::factory()->create([
        'title' => 'Lorem ipsum dolor sit amet',
        'category_id' => test()->category1,
        'status' => test()->status1->slug
    ]);

    test()->idea2 = Idea::factory()->create([
        'title' => 'Nullam luctus mi ac',
        'category_id' => test()->category2,
    ]);
    test()->idea3 = Idea::factory()->create([
        'title' => 'DONT MATCH SEARCH',
        'category_id' => test()->category2,
    ]);
    test()->idea4 = Idea::factory()->create([
        'title' => 'DONT BELONG TO FIRST PRODUCT',
        'category_id' => test()->category4,
    ]);

     // Run a specific seeder...
    test()->seed(RoleAndPermissionSeeder::class);

    test()->userBasic = User::factory()->create();

    // Super Admin User
    test()->userSuperAdmin = User::factory()->create();
    test()->userSuperAdmin->assignRole(config('const.ROLE_SUPER_ADMIN'));

    // Product Admin User
    test()->userProductAdmin1 = User::factory()->create();
    test()->userProductAdmin1->assignRole(config('const.ROLE_PRODUCT_ADMIN'));
    test()->userProductAdmin1->syncPermissions([config('const.PERMISSION_PRODUCTS_MANAGE') . '.' . test()->product1->id]);

    test()->userProductAdmin2 = User::factory()->create();
    test()->userProductAdmin2->assignRole(config('const.ROLE_PRODUCT_ADMIN'));
    test()->userProductAdmin2->syncPermissions([config('const.PERMISSION_PRODUCTS_MANAGE') . '.' . test()->product2->id]);

    test()->searchString = 'Lorem';

    // Load Service Classes
    test()->ideaVoteService = new IdeaVoteService();
    test()->ideaSpamService = new IdeaSpamService();
}
