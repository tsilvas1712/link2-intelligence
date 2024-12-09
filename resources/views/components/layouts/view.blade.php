<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

</head>

<body class="min-h-screen font-sans antialiased">

    <div class=" max-w-6xl flex flex-col m-auto bg-gray-100 p-2 rounded gap-2">
        <header class="flex justify-between bg-white shadow rounded p-2">
            <div class="!justify-end">
                <x-button class="btn-primary " icon="o-home" label="Home" link="{{ route('dashboard') }}" />
                <x-button class="btn-primary " icon="o-building-storefront" label="Filiais"
                    link="{{ route('filiais.dashboard') }}" />
                <x-button class="btn-primary " icon="o-users" label="Vendedores"
                    link="{{ route('vendedores.dashboard') }}" />
                <x-button class="btn-primary " icon="o-cursor-arrow-ripple" label="Planos" />

            </div>
            <div>
                <x-button class="btn bg-gray-500 hover:bg-secondary " icon="o-cog" label="Painel"
                    link="{{ route('admin.dashboard') }}" />
                <x-button class="btn bg-red-500 " icon="o-arrow-right-end-on-rectangle" label="Sair"
                    link="{{ route('logout') }}" />
            </div>
        </header>
        {{-- MAIN --}}
        <x-main full-width>
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-main>

        {{--  TOAST area --}}
        <x-toast />

    </div>


</body>

</html>
