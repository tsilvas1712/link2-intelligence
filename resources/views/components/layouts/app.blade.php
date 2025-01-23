<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>

        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-primary lg:text-white">

            {{-- BRAND --}}
            <div class="w-full p-2 bg-white h-18">
                <img src="{{ asset('assets/logo.svg') }}" alt="Logo" class="w-full h-16 mx-auto my-4" />
            </div>


            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff"
                                no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                @endif

                <x-menu-item title="Home" icon="o-home" link="{{ route('admin.dashboard') }}" exact />
                <x-menu-item title="Filiais" icon="o-building-storefront" link="{{ route('admin.filiais') }}" exact />
                <x-menu-item title="Vendedores" icon="o-calculator" link="{{ route('admin.vendedores') }}" exact />
                <x-menu-item title="Valores de Planos" icon="o-currency-dollar" link="{{ route('admin.planos') }}"
                    exact />
                <x-menu-item title="Grupos" icon="o-rectangle-group" link="{{ route('admin.groups') }}" exact />
                <x-menu-item title="Usuários" icon="o-users" link="{{ route('admin.usuarios') }}" exact />
                <x-menu-separator />
                <x-menu-item title="Painel de Vendas" icon="o-presentation-chart-line" link="{{ route('dashboard') }}"
                    exact />

            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            <div class="w-full h-full p-4 bg-white rounded shadow">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
</body>

</html>
