<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display product ideas by tag.
     */
    public function show(Product $product, Tag $tag): View
    {
        return view('frontend.product.tag', [
            'product' => $product,
            'tag' => $tag,
        ]);
    }
}
