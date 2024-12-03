<div class="flex flex-col justify-center items-center w-full h-screen">
    <div class="w-1/3 rounded bg-white p-4 flex flex-col gap-8 shadow">
        <img src="{{ asset('assets/logo.svg') }}" alt="Logo" class="w-52 mx-auto" />
        <x-form wire:submit="login">
            <x-input label="E-mail" wire:model="email" type='email' />
            <x-input label="Senha" wire:model="password" type="password" />


            <x-slot:actions>

                <x-button label="Acessar" class="btn-primary w-full" type="submit" spinner="login" />
            </x-slot:actions>
        </x-form>
    </div>
</div>
