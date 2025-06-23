<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-base-200/50 font-sans antialiased dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav class="lg:hidden" sticky>
        <x-slot:brand>

        </x-slot:brand>
        <x-slot:actions>
            <label class="me-3 lg:hidden" for="main-drawer">
                <x-icon class="cursor-pointer" name="o-bars-3" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar class="bg-base-100 lg:bg-primary lg:text-white" collapsible drawer="main-drawer">

            {{-- BRAND --}}
            <div class="h-18 w-full bg-white p-2">
                <img alt="Logo" class="mx-auto my-4 h-16 w-full" src="{{ asset('assets/logo.svg') }}" />
            </div>


            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" class="!-my-2 -mx-2 rounded" no-hover no-separator sub-value="email"
                        value="name">
                        <x-slot:actions>
                            <x-button class="btn-circle btn-ghost btn-xs" icon="o-power" link="/logout" no-wire-navigate
                                tooltip-left="logoff" />
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                @endif

                <x-menu-item exact icon="o-home" link="{{ route('admin.dashboard') }}" title="Home" />

                <x-menu-sub title="Datasys" icon="o-cog-6-tooth" icon-classes="text-warning">
                    <x-menu-item exact icon="o-device-tablet" link="{{ route('admin.datasys.dashboard') }}" title="Painel Datasys" />
                    <x-menu-item exact icon="o-key" link="{{ route('admin.datasys.api') }}" title="Certificado Datasys" />
                </x-menu-sub>

                <x-menu-item exact icon="o-rectangle-group" link="{{route('admin.categorias')}}" title="Categorias" />
                <x-menu-item exact icon="o-building-storefront" link="{{ route('admin.filiais') }}" title="Filiais" />
                <x-menu-item exact icon="o-calculator" link="{{ route('admin.vendedores') }}" title="Vendedores" />
                <x-menu-item exact icon="o-currency-dollar" link="{{ route('admin.planos') }}"
                    title="Valores de Planos" />
                <x-menu-item exact icon="o-rectangle-group" link="{{ route('admin.groups') }}" title="Grupos" />
                <x-menu-item exact icon="o-users" link="{{ route('admin.usuarios') }}" title="Usuários" />
                <x-menu-separator />
                <x-menu-item exact icon="o-presentation-chart-line" link="{{ route('dashboard') }}"
                    title="Painel de Vendas" />

            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            <div class="h-full w-full rounded bg-white p-4 shadow">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
</body>

</html>
