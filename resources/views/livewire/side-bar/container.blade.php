<div class="sidebar-container">
    <div class="side-bar mx-auto w-80 md:mx-0 md:mr-5 px-4">
        <div
            class="side-bar-container bg-white md:sticky rounded-xl mt-8 px-4 py-2 top-28 shadow-md"
        >
            <x-product.shortcuts :product="$product" />
            <livewire:side-bar.category-links :productId="$product->id" />
            <x-product.links :links="$product->links" />
            <livewire:side-bar.tags-list :product='$product'/>
            <livewire:side-bar.people :product='$product'/>
        </div>
    </div>
</div>
