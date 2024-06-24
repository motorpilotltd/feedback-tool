<?php

namespace App\Http\Controllers\Frontend\Product;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Product;

class SuggestIdeaController extends Controller
{
    /**
     * Suggest an idea for the current product.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product): View
    {
        return view('frontend.product.suggest-idea', [
            'product' => $product,
        ]);
    }
}
