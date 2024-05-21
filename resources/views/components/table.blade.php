<div class="flex flex-col">
    <div class="flex-grow overflow-auto -my-2 shadow border-b border-gray-200 sm:rounded-lg">
        <table {{ $attributes->merge(['class' => 'relative min-w-full divide-y divide-gray-200']) }}>
            <thead class="bg-gray-50">
                <tr>
                    {{ $head }}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $body }}
            </tbody>
        </table>
    </div>
</div>
