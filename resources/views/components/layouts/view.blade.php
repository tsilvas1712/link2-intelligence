<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>


</head>

<body class="min-h-screen font-sans antialiased">
    <div class="m-auto flex flex-col gap-2 rounded  p-2 lg:max-w-7xl">
        @php
            $certificado = \App\Models\Certificado::query()->first();

            $validate_at = Carbon\Carbon::parse($certificado->validateAt)->format('Y-m-d');
            $now = Carbon\Carbon::now()->format('Y-m-d');
            $expirate_in = Carbon\Carbon::parse($now)->diffInDays($validate_at);

        @endphp
        @if ($expirate_in <= 2)
            <x-alert class="bg-red-500" description="Sua chave de API Está Vencendo" dismissible
                icon="o-exclamation-triangle" title="Datasys" />
        @endif
        @if ($expirate_in <= 5 && $expirate_in > 2)
            <x-alert class="bg-yellow-400" description="Sua chave de API irá Expirar em Breve" dismissible
                icon="o-exclamation-triangle" title="Datasys" />
        @endif

        <header class="flex justify-between rounded bg-white p-2 shadow">
            <div class="!justify-end">
                <x-button class="btn-primary" icon="o-home" label="Home" link="{{ route('dashboard') }}" />
                <x-button class="btn-primary hidden" disabled icon="o-building-storefront" label="Filiais"
                    link="{{ route('filiais.dashboard') }}" />
                <x-button class="btn-primary hidden" disabled icon="o-users" label="Vendedores"
                    link="{{ route('vendedores.dashboard') }}" />
                <x-button class="btn-primary hidden" disabled icon="o-cursor-arrow-ripple" label="Planos" />

            </div>
            <div>
                @if (auth()->user()->cargo === 'admin')
                    <x-button class="btn bg-gray-500 hover:bg-secondary" icon="o-cog" label="Painel"
                        link="{{ route('admin.dashboard') }}" />
                @endif
                <x-button class="btn bg-red-500" icon="o-arrow-right-end-on-rectangle" label="Sair"
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
