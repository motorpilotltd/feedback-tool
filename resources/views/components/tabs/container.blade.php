@props(['active'])
<div x-data="{
        activeTab: (window.location.hash.length > 0) ? '#' + window.location.hash.substring(1) : '{{ $active }}',
        tabs: [],
        tabHeadings: [],
        getName(tab) {
            return eval(`(${tab.getAttribute('x-data')})`)['name'];
        },
        getTabLink(tab) {
            return eval(`(${tab.getAttribute('x-data')})`)['tabLink'];
        }
    }"
    x-init="() => {

        tabs = [...$refs.tabs.children];
        tabHeadings = tabs.map(tab => {
            const name =  getName(tab);
            const tabLink =  getTabLink(tab);
            return [
                name,
                '#'+  tabLink
            ];
        });
    }"
>
    <div class="mb-3">
        <ul class="flex flex-wrap border-b border-gray-200">
            <template  x-for="(tab, index) in tabHeadings" :key="index">
                <li class="mr-2 group">
                    <a x-bind:href="tab[1]" x-text="tab[0]"
                        @click="activeTab = tab[1];"
                        :class="tab[1] == activeTab ? 'border-blue-400 text-blue-500 font-bold' : 'hover:text-gray-700 hover:border-gray-300 text-gray-400 border-transparent';"
                        role="tab"
                        :aria-selected="tab[1] === activeTab"
                        class="inline-flex items-center p-4 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition"
                    >
                    </a>
                </li>
            </template>
        </ul>
    </div>
    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
