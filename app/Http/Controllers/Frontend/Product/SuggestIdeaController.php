<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class SuggestIdeaController extends Controller
{
    /**
     * Suggest an idea for the current product.
     */
    public function show(Product $product): View
    {
        return view('frontend.product.suggest-idea', [
            'product' => $product,
        ]);
    }
}
