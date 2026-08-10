<div style="display: grid; gap: 0.5rem;">
    @foreach ($this->localLoginEmails() as $email)
        <x-filament::button
            style="width: 100%;"
            wire:click="loginUser({{ Illuminate\Support\Js::from($email) }})"
            wire:loading.attr="disabled"
            wire:target="loginUser"
        >
            Login as {{ $email }}
        </x-filament::button>
    @endforeach
</div>
