<div id="system-settings">
    <x-form-section submit="saveGeneralSettings">
        <x-slot name="title">
            {{ __('General Settings') }}
        </x-slot>
        <x-slot name="description">
            {{ __('Update General Settings') }}
        </x-slot>
        <x-slot name="form">
            @foreach ($generalSettings as $key => $val)
                @php
                    $label = Str::headline($key);

                    $cornerHintsMap = [
                        'smtp_host' => 'MAIL_HOST',
                        'smtp_port' => 'MAIL_PORT',
                        'smtp_username' => 'MAIL_USERNAME',
                        'smtp_password' => 'MAIL_PASSWORD',
                    ];
                    $cornerHint = isset($cornerHintsMap[$key]) ? __('text.smtpdefaulthint', ['smtp' => $cornerHintsMap[$key]]) : '';
                @endphp
                <div class="col-span-6 sm:col-span-4">
                    @switch(gettype($val))
                        @case('boolean')
                            <x-toggle
                                :label="$label"
                                lg
                                wire:model="generalSettings.{{ $key }}"
                            />
                            @break
                        @case('integer')
                            <x-number
                                :label="$label"
                                wire:model="generalSettings.{{ $key }}"
                            />
                            @break
                        @default
                            @if (Str::position($key, 'secret') !== false || Str::position($key, 'password') !== false)
                                <x-password
                                    :label="$label"
                                    cornerHint="{!! $cornerHint !!}"
                                    :disabled="$key == 'language' ? true : false"
                                    wire:model="generalSettings.{{ $key }}"
                                />
                            @else
                                <x-input
                                    :label="$label"
                                    cornerHint="{!! $cornerHint !!}"
                                    :disabled="$key == 'language' ? true : false"
                                    wire:model="generalSettings.{{ $key }}"
                                />
                            @endif

                    @endswitch
                    @error($key)
                        <x-input.error>{{ $message }}</x-input.error>
                    @enderror
                </div>
            @endforeach
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="savedGeneralSettings">
                <x-alert type="success">
                    {{ __('Successfully saved settings.') }}
                </x-alert>
            </x-action-message>

            <x-button type="submit">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>

    <x-form-section submit="saveAzureADSettings">
        <x-slot name="title">
            {{ __('Azure AD Settings') }}
        </x-slot>
        <x-slot name="description">
            {{ __('Update Azure AD Settings') }}
        </x-slot>
        <x-slot name="form">
            @foreach ($azureadSettings as $key => $val)
                @php
                    $label = Str::headline($key);
                @endphp
                <div class="col-span-6 sm:col-span-4">
                    @switch(gettype($val))
                        @case('boolean')
                            <x-toggle
                                :label="$label"
                                lg
                                wire:model="azureadSettings.{{ $key }}"
                            />
                            @break
                        @case('integer')
                            <x-number
                                :label="$label"
                                wire:model="azureadSettings.{{ $key }}"
                            />
                            @break
                        @default
                            @if (Str::position($key, 'secret') !== false || Str::position($key, 'password') !== false)
                                <x-password
                                    :label="$label"
                                    wire:model="azureadSettings.{{ $key }}"
                                />
                            @else
                                <x-input
                                    :label="$label"
                                    wire:model="azureadSettings.{{ $key }}"
                                />
                            @endif

                    @endswitch
                </div>
            @endforeach
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="savedAzureadSettings">
                <x-alert type="success">
                    {{ __('Successfully saved settings.') }}
                </x-alert>
            </x-action-message>

            <x-button type="submit">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>

    <x-form-section submit="saveLinksSettings">
        <x-slot name="title">
            {{ __('Links Settings') }}
        </x-slot>
        <x-slot name="description">
            {{ __('Links to be displayed in the nav, accessible in the system level.') }}
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-input
                    label="Dropdown link title"
                    wire:model="linksSettings.title"
                />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <livewire:forms.links-field :initialLinks="$links" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="savedLinksSettings">
                <x-alert type="success">
                    {{ __('Successfully saved links settings.') }}
                </x-alert>
            </x-action-message>

            <x-button type="submit">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
