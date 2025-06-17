<div>
    <x-header title="Grupos" separator>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" label="Criar novo grupo"
                link="{{ route('admin.groups.criar') }}" />
        </x-slot:actions>
    </x-header>

    <div>
        <x-table :headers="$headers" :rows="$this->grupos">
            @scope('cell_created_at', $grupo)
                {{ $grupo->created_at->format('d/m/Y') }}
            @endscope

            @scope('cell_updated_at', $grupo)
            {{ $grupo->updated_at->format('d/m/Y') }}
            @endscope

            @scope('cell_principal', $grupo)
            <div class="flex justify-center items-center">
                @if ($grupo->principal)
                    <x-icon name="s-check-badge" class="text-green-900 h-8"/>
                @else
                    <x-icon name="s-no-symbol" class="text-red-900 h-8"/>
                @endif
            </div>
            @endscope

            @scope('cell_category_id',$grupo)
            <div class="flex justify-center items-center">
                {{-- Display the category name or 'N/A' if not set --}}
                {{$grupo->category->name ?? 'N/A'}}
            </div>

            @endscope

            @scope('actions', $grupo)
                <div class="flex gap-2">
                    <x-button icon="o-pencil" link="{{ route('admin.groups.editar', $grupo->id) }}" spinner
                        class="btn-sm btn-primary" />
                    <x-button icon="o-trash" wire:click="delete({{ $grupo->id }})" spinner class="btn-sm btn-primary" />
                </div>
            @endscope
        </x-table>

    </div>
</div>
