<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;

class SuggestIdeaController extends Controller
{
    /**
     * Suggest an idea for the current product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('frontend.product.suggest-idea', [
            'product' => $product
        ]);
    }
}
