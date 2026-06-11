<?php

use App\Livewire\Product\IdeasProgress;
use App\Models\Idea;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Support\Carbon;

use function Pest\Livewire\livewire;

it('lists the 10 most recent ideas per status, newest first', function () {
    $product = Product::factory()->create();
    $category = $product->categories()->first();
    Status::factory()->create(['slug' => 'planned', 'name' => 'Planned']);

    $base = Carbon::parse('2026-01-01');
    foreach (range(1, 12) as $i) {
        Idea::factory()->create([
            'title' => "Idea {$i}",
            'category_id' => $category->id,
            'status' => 'planned',
            'created_at' => $base->copy()->addDays($i),
        ]);
    }

    $statuses = livewire(IdeasProgress::class, ['product' => $product])->viewData('statuses');
    $planned = $statuses->firstWhere('slug', 'planned');

    expect($planned->ideas)->toHaveCount(10)
        ->and($planned->ideas->first()->title)->toBe('Idea 12')
        ->and($planned->ideas->last()->title)->toBe('Idea 3');
});
