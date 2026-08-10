<div class="flex flex-col gap-y-2">
    @foreach ($this->localLoginEmails() as $email)
        <x-filament::button
            class="mb-2 w-full"
            wire:click="loginUser({{ Illuminate\Support\Js::from($email) }})"
            wire:loading.attr="disabled"
            wire:target="loginUser"
        >
            Login as {{ $email }}
        </x-filament::button>
    @endforeach
</div>
