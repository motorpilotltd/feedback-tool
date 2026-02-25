<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProductNotArchived
{
    public function handle(Request $request, Closure $next): Response
    {
        $product = $this->resolveProduct($request);

        if (! $product || ! $product->isArchived()) {
            return $next($request);
        }

        $user = $request->user();

        if ($user) {
            if ($user->hasRole(config('const.ROLE_SUPER_ADMIN'))) {
                return $next($request);
            }

            if ($user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$product->id)) {
                return $next($request);
            }
        }

        abort(404);
    }

    protected function resolveProduct(Request $request): ?Product
    {
        if ($product = $request->route('product')) {
            return $product instanceof Product ? $product : null;
        }

        if ($category = $request->route('category')) {
            return $category instanceof Category ? $category->product : null;
        }

        if ($idea = $request->route('idea')) {
            return $idea instanceof Idea ? $idea->product : null;
        }

        return null;
    }
}
