<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('frontend.product.show', [
            'product' => $product,
        ]);
    }

    /**
     * Display product ideas by tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function tag(Product $product, Tag $tag)
    {
        return view('frontend.product.tag', [
            'product' => $product,
            'tag' => $tag,
        ]);
    }

    /**
     * Suggest idea for the current Product
     *
     * @return \Illuminate\Http\Response
     */
    public function suggestIdea(Product $product)
    {
        return view('frontend.product.suggest-idea', [
            'product' => $product,
        ]);
    }

    /**
     * View Product's ideas progress(by status)
     *
     * @return \Illuminate\Http\Response
     */
    public function progress(Product $product)
    {
        return view('frontend.product.progress', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
