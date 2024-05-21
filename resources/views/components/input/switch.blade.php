{{-- <div class="flex justify-center">
    <div class="form-check form-switch">
        <input
            {!! $attributes->merge(['class' => 'form-check-input appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm']) !!}
            type="checkbox"
            role="switch"
        />
    </div>
</div> --}}

<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
    <input
    {!! $attributes->merge(['class' => 'toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer']) !!}
        type="checkbox"
        name="toggle"
        id="toggle"
    />
    <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
</div>
