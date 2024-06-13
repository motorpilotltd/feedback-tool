<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display product ideas by tag.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Tag $tag)
    {
        return view('frontend.product.tag', [
            'product' => $product,
            'tag' => $tag
        ]);
    }
}
