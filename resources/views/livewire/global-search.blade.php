@php
    $titleLimit = $isFullPage ? 150 : 70;
    $descLimit = $isFullPage ? 250 : 100;
    $fullPageClasses = $isFullPage ? 'bg-white shadow-md rounded-md py-4 px-8' : '';
@endphp
<div
    id='global-search'
    x-data=""
    @global-search-focuskeyword.window="
        $nextTick(() => {
            setTimeout(() => {
                document.getElementById('global-search-keyword').focus()
            }, 300);
        });"
    "
>
    <form wire:submit.prevent='goToSearchFullPage'>
        <x-input
            id='global-search-keyword'
            wire:model.live.debounce.500ms="keywords"
            autocomplete="off"
            icon="magnifying-glass"
            placeholder="Search anything..."
        >
            @if($keywords)
                <x-slot name="append">
                    <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                        <x-icon
                            name="x-mark"
                            class="w-4 h-4 m-2 text-red-base cursor-pointer hover:text-red-700"
                            solid
                            wire:click='clearKeywords'
                        />
                    </div>
                </x-slot>
            @endif
        </x-input>
    </form>
    @if($keywords && !$isFullPage)
        <div class="w-full text-right text-gray-400 mt-2">{!! __('text.enterforfullsearchresult') !!}</div>
    @endif
    <div id='global-search-result'>
        {{-- Product results --}}
        @if ($products->isNotEmpty())
            <div class="product-results space-y-2 mt-4 {{ $fullPageClasses }}">
                <h6 class="text-xl font-bold text-gray-400">Products</h6>
                @foreach ($products as $product)
                    <a href="{{ route('product.show', $product) }}" class="flex flex-col group hover:bg-gray-200 px-2.5 py-1.5 rounded-sm">
                        <b class="group-hover:text-blue-hover">{!! highlightMatchedSearch($product->name, $keywords) !!}</b>
                        <span class="leading-snug text-xs text-gray-400">{{  Str::limit($product->description, $descLimit, '...') }}</span>
                    </a>
                @endforeach
                @if ($isFullPage)
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Category results --}}
        @if ($categories->isNotEmpty())
            <div class="category-results space-y-2 mt-4 {{ $fullPageClasses }}">
                <h6 class="text-xl font-bold text-gray-400">Categories</h6>
                @foreach ($categories as $category)
                    <a href="{{ route('category.show', $category) }}" class="flex flex-col group hover:bg-gray-200 px-2 py-1.5 rounded-sm">
                        <span>{{ $category->product->name }} > <b class="group-hover:text-blue-hover">{!! highlightMatchedSearch($category->name, $keywords) !!}</b></span>
                        <span class="leading-snug text-xs text-gray-400">{{  Str::limit($category->description, $descLimit, '...') }}</span>
                    </a>
                @endforeach
                @if ($isFullPage)
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Product admin results --}}
        @if ($productAdmins->isNotEmpty())
            <div class="productadmin-results space-y-2 mt-4 {{ $fullPageClasses }}">
                <h6 class="text-xl font-bold text-gray-400">Product Admins</h6>
                @foreach ($productAdmins as $user)

                    <a href="{{ route('user.viewprofile', ['user' => $user->id]) }}" class="flex flex-col group hover:bg-gray-200 px-2 py-1.5 rounded-sm">
                        @php
                            $user->name = highlightMatchedSearch($user->name, $keywords);
                            $user->email = highlightMatchedSearch($user->email, $keywords);
                        @endphp
                        <x-user-avatar-name-email
                            class="group-hover:text-blue-hover"
                            :user="$user"
                        />
                        <div class="flex flex-row space-x-2">
                            @foreach ($user->permissionProduct as $permission)
                                <x-badge outline sky label="{{ $permission }}" />
                            @endforeach
                        </div>
                    </a>
                @endforeach
                @if ($isFullPage)
                    <div class="mt-4">
                        {{ $productAdmins->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Idea results --}}
        @if ($ideas->isNotEmpty())
            <div class="ieas-results space-y-2 mt-4 {{ $fullPageClasses }}">
                <h6 class="text-xl font-bold text-gray-400">Ideas</h6>
                @foreach ($ideas as $idea)
                    <a href="{{ route('idea.show', $idea) }}" class="flex flex-col group hover:bg-gray-200 px-2 py-1.5 rounded-sm">
                        <span>{{ $idea->product->name }} > <b class="group-hover:text-blue-hover">{!! highlightMatchedSearch($idea->title, $keywords) !!}</b></span>
                        <span class="leading-snug text-xs text-gray-400">{{  Str::limit($idea->content, $descLimit, '...') }}</span>
                    </a>
                @endforeach
                @if ($isFullPage)
                    <div class="mt-4">
                        {{ $ideas->links() }}
                    </div>
                @endif
            </div>
        @endif
        @if ($keywords && $products->isEmpty() && $categories->isEmpty() && $productAdmins->isEmpty() && $ideas->isEmpty())
            <div class="leading-snug text-xs text-gray-400 mt-4 text-center">{{ __('text.couldnotfind') }}</div>
        @endif
    </div>
</div>
