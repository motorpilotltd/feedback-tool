<?php

namespace App\Traits;

use App\Models\Product;

trait WithCustomPolicy
{
    /**
     * Determine if product is in sandbox mode
     */
    public function isSandboxMode(Product $product)
    {
        if (isset($product->settings['enableSandboxMode']) && $product->settings['enableSandboxMode']) {
            return true;
        }

        return false;
    }
}
