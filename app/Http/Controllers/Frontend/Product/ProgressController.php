<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProgressController extends Controller
{
    /**
     * View the product's ideas progress (by status).
     */
    public function show(Product $product): View
    {
        return view('frontend.product.progress', [
            'product' => $product,
        ]);
    }
}
