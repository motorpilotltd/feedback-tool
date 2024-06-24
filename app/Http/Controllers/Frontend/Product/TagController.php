<?php

namespace App\Http\Controllers\Frontend\Product;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display product ideas by tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Tag $tag): View
    {
        return view('frontend.product.tag', [
            'product' => $product,
            'tag' => $tag,
        ]);
    }
}
