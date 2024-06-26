<?php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\Tag;
// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Str;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {

    if (Str::position(url()->previous(), route('admin.users')) !== false) {
        $trail->push('Admin Dashboard - Users', url()->previous());
    } else {
        $trail->push('Home', route('product.index'));
    }
});

// Home > [Product Name]
Breadcrumbs::for('product', function (BreadcrumbTrail $trail, Product $product, array $queryParams = []) {
    $prevQueryParams = getPreviousQueryString() ?? [];

    if (getPreviousRouteName() == 'frontend.search.index') {
        $trail->parent('search', $prevQueryParams);
    } else {
        if (
            ! isset($product->settings['hideProductFromBreadcrumbs']) ||
            (isset($product->settings['hideProductFromBreadcrumbs']) && ! $product->settings['hideProductFromBreadcrumbs'])
        ) {
            $trail->parent('home');
        }
    }

    $params = collect([
        'product' => $product,
    ])->merge($queryParams);
    $trail->push($product->name, route('product.show', $params->all()));
});

// Home > Profile
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Edit My Profile', route('profile.show'));
});

// Home > Profile
Breadcrumbs::for('search', function (BreadcrumbTrail $trail, array $queryParams = []) {
    $trail->parent('home');
    $trail->push('Search', route('frontend.search.index', $queryParams));
});

// Home > View Profile
Breadcrumbs::for('viewprofile', function (BreadcrumbTrail $trail) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    if (getPreviousRouteName() == 'frontend.search.index') {
        $trail->parent('search', $prevQueryParams);
    } else {
        $trail->parent('home');
    }

    $trail->push('Profile', route('profile.show'));
});

// Home > [Category Title]
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, Category $category, array $queryParams = []) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    if (getPreviousRouteName() == 'frontend.search.index') {
        $trail->parent('search', $prevQueryParams);
    } else {
        $trail->parent('product', $category->product);
    }
    $params = collect([
        'category' => $category,
    ])->merge($queryParams);
    $trail->push($category->name, route('category.show', $params->all()));
});

// Home > Product > Category > [Idea title]
Breadcrumbs::for('idea', function (BreadcrumbTrail $trail, Idea $idea) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    if (getPreviousRouteName() == 'category.show') {
        $trail->parent('category', $idea->category, $prevQueryParams);
    } elseif (getPreviousRouteName() == 'frontend.search.index') {
        $trail->parent('search', $prevQueryParams);
    } else {
        $trail->parent('product', $idea->product, $prevQueryParams);
    }

    if (getPreviousRouteName() == 'product.tag') {
        $trail->push(__('text.tagresults'), url()->previous());
    }

    $trail->push(Str::limit($idea->title, 35, '...'), $idea->idea_link);
});

// Home > Product > [Tag Name]
Breadcrumbs::for('tag', function (BreadcrumbTrail $trail, Tag $tag) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    $trail->parent('product', $tag->tagGroup->product, $prevQueryParams);
    $trail->push(__('text.tagname', ['tag' => $tag->name]), route('product.tag', [$tag->tagGroup->product, $tag]));
});

// Home > Product > [Suggest an Idea]
Breadcrumbs::for('suggestIdea', function (BreadcrumbTrail $trail, Product $product) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    $trail->parent('product', $product, $prevQueryParams);
    $trail->push(__('Suggesting an idea...'), route('product.suggest.idea', [$product]));
});

// Home > Product > [Progress]
Breadcrumbs::for('progress', function (BreadcrumbTrail $trail, Product $product) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    $trail->parent('product', $product, $prevQueryParams);
    $trail->push(__('Progress'), route('product.progress', [$product]));
});

// Home > | Product > | Category > | Tag > [Idea title]
Breadcrumbs::for('editIdea', function (BreadcrumbTrail $trail, Idea $idea) {
    $prevQueryParams = getPreviousQueryString() ?? [];
    if (getPreviousRouteName() == 'category.show') {
        $trail->parent('category', $idea->category, $prevQueryParams);
    } else {
        $trail->parent('product', $idea->product, $prevQueryParams);
    }

    if (getPreviousRouteName() == 'product.tag') {
        $trail->push(__('text.tagresults'), url()->previous());
    }

    if (getPreviousRouteName() == 'idea.show') {
        $trail->push(Str::limit($idea->title, 35, '...'), $idea->idea_link);
    }

    $trail->push(__('Editing...'), $idea->idea_link);
});
