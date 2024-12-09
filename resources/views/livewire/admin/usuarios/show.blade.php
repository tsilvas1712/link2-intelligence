<div>
    <x-header title="{{ $user['name'] ?? 'Novo Usuário' }}" subtitle="Ajustes de usuários do Sistema" separator />
    <div>
        @if ($errors->hasAny('invalidRegister'))
            <x-alert icon="o-exclamation-triangle" class="alert-danger mb-4">
                @error('invalidRegister')
                    <span>{{ $message }}</span>
                @enderror

            </x-alert>
        @endif

        @php
            $cargo = [
                [
                    'id' => 'admin',
                    'name' => 'Administrador',
                ],
                [
                    'id' => 'usuario',
                    'name' => 'Usuário',
                ],
            ];
        @endphp
        <x-form wire:submit.prevent="save">
            <div class="flex flex-col gap-4">
                <x-input label="Nome" placeholder="Digite o nome do usuário" wire:model="user.name" />
                <x-input label="E-mail" placeholder="Digite o e-mail do usuário" wire:model="user.email" />
                <x-input label="Senha" placeholder="Digite a senha do usuário" type="password"
                    wire:model="user.password" />

                @if ($user['name'] === null)
                    <x-input label="Confirmação de Senha" placeholder="Digite a senha do usuário" type="password"
                        wire:model="user.password_confirmation" />
                @endif

                <x-select label="Escolha o Cargo" icon="o-user" :options="$cargo" wire:model="cargoSelected" />

            </div>
            <div class="flex justify-end gap-4">
                <x-button label="Salvar" class="btn-primary" type="submit" />
                <x-button label="Cancelar" class="btn-secondary" link="{{ route('admin.usuarios') }}" />
            </div>
        </x-form>
    </div>

</div>
