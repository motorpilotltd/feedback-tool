@unless ($breadcrumbs->isEmpty())
    <nav class="container mx-auto mb-4 shadow-md rounded-xl bg-white">
        <ol class="p-4 flex flex-wrap text-sm text-gray-800 ">
            @foreach ($breadcrumbs as $breadcrumb)
                @php
                    $breadcrumbtitle = Str::limit($breadcrumb->title, 25, '...');
                @endphp
                @if ($breadcrumb->url && !$loop->last)
                    <li>
                        <a href="{{ $breadcrumb->url }}" class="text-blue-base hover:text-blue-hover font-semibold hover:underline focus:text-blue-hover focus:underline">
                            {{ $breadcrumbtitle }}
                        </a>
                    </li>
                @else
                    <li>
                        {{ $breadcrumbtitle }}
                    </li>
                @endif

                @unless($loop->last)
                    <li class="text-gray-500 px-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                    </li>
                @endif

            @endforeach
        </ol>
    </nav>
@endunless
