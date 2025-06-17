<div>

    <div class="flex flex-col gap-4">

   @foreach($telas as $tela)
        <div class="bg-white shadow-md rounded-lg p-2">
            <h2 class="text-xl font-semibold mb-4">{{ $tela->name }}</h2>
            <p class="text-gray-700 mb-4">{{ $tela->description }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($tela->grupos as $grupo)
                <div class="bg-gray-100 p-4 mb-4 rounded-lg">
                    <h3 class="text-lg font-semibold">{{ $grupo->nome }}</h3>
                    <p class="text-gray-600">{{ $grupo->description }}</p>

                  <span class="text-2xl font-black">R$  {{ number_format($this->getValores($grupo->id), 2, ',', '.') }}</span>

                </div>

            @endforeach
            </div>

        </div>

   @endforeach
    </div>
</div>
