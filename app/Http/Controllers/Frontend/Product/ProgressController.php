<?php

namespace App\Http\Controllers\Frontend\Product;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProgressController extends Controller
{
    /**
     * View the product's ideas progress (by status).
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product): View
    {
        return view('frontend.product.progress', [
            'product' => $product,
        ]);
    }
}
