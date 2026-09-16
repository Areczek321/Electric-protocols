<div class="w-full mx-auto space-y-6  dark:bg-gray-800 p-4">

    <div class="flex items-center justify-between flex-wrap">
        <div class="flex flex-row">
            <div class="flex flex-col">
                <h1 class="text-2xl  text-gray-900 dark:text-white">
                    Protokół:
                    <strong>{{ $protocol->client->surname . ' ' }}{{ date('y', strtotime($protocol->date)) }}</strong>

                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Utworzono: {{ $protocol->created_at->format('d.m.Y H:i') }}
                    @if ($protocol->updated_at != $protocol->created_at)
                        (Zaktualizowano: {{ $protocol->updated_at->format('d.m.Y H:i') }})
                    @endif
                </p>
            </div>


            <button type="button" wire:click="removeProtocol()" wire:confirm="Czy chcesz usunąć PROTOKÓŁ ?"
                title="Usuń pomieszczenie"
                class="sm:flex hidden cursor-pointer ml-10 items-center justify-center w-10 h-10 rounded-md bg-red-300 text-red-600 hover:bg-red-200 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50  shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </button>

        </div>
        <div class="sm:flex-row flex-col justify-end items-center ">
            @php
                $hasData =
                    $protocol->switchboards->contains(
                        fn($switchboard) => $switchboard->differentialBreakers->isNotEmpty() ||
                            $switchboard->circuitBreakers->isNotEmpty() ||
                            $switchboard->rooms->isNotEmpty(),
                    ) || $protocol->wireResistances->isNotEmpty();
            @endphp

            @if ($hasData)
                <a href="{{ route('protocols.print', $protocol->id) }}" target="_blank"
                    class="mr-10 rounded bg-blue-600 px-4 py-2 text-white">
                    Drukuj
                </a>
                {{-- <button wire:click="goPrint"  
                    class="mr-10 cursor-pointer inline-flex items-center justify-center px-4 py-2 text-center mx-auto font-medium
                                    text-white bg-black rounded-lg shadow-sm hover:bg-gray-800
                                    focus:outline-none focus:ring-2 focus:ring-gray-300
                                    dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                                     whitespace-nowrap"
                >
                    Drukuj
                </button> --}}
            @endif


            <a href="{{ route('dashboard') }}" wire:navigate
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium
                   text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200
                   dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600
                   dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700
                   ">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Powrót
            </a>
        </div>

    </div>

    <x-protocol.client-data :protocol="$protocol" />


    <div class="w-full p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 gap-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Lista rozdzielnic
            </h2>
        </div>

        <div class="overflow-x-auto mb-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-medium">Nazwa</th>
                        <th scope="col" class="px-4 py-3 font-medium">Ilość bezpieczników</th>
                        <th scope="col" class="px-4 py-3 font-medium">Zarządzaj rozdzielnicą</th>
                        <th scope="col" class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($protocol->switchboards as $switchboard)
                        <tr wire:key="switchboard-{{ $switchboard->id }}"
                            class="border-b last:border-0 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/25 ">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                <input type="text" value="{{ $switchboard->name }}"
                                    wire:change="updateSwitchboard({{ $switchboard->id }}, $event.target.value)"
                                    class="block w-[120px] mx-auto px-3.5 py-2 text-sm text-center text-gray-900 bg-gray-50
                                    border border-gray-300 dark:border-gray-600 rounded-lg 
                                    focus:ring-blue-500 focus:border-blue-500 
                                    dark:bg-gray-900 dark:placeholder-gray-400 dark:text-white
                                    dark:focus:ring-blue-500 dark:focus:border-blue-500 
                                    @error('surname') border-red-500 dark:border-red-500 @enderror">
                            </td>
                            <td class="px-4 py-3">
                                {{ $switchboard->circuitBreakers->count() }}
                            </td>

                            <td>
                                <a href="{{ route('protocol.createSwitchboard', ['switchboard' => $switchboard->id]) }}"
                                    wire:navigate
                                    class="inline-flex items-center justify-center px-4 py-2 text-center mx-auto font-medium
                                    text-white bg-black rounded-lg shadow-sm hover:bg-gray-800
                                    focus:outline-none focus:ring-2 focus:ring-gray-300
                                    dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                                     whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edytuj
                                </a>
                            </td>
                            <td>
                                <button wire:click="deleteSwitchboard({{ $switchboard->id }})"
                                    wire:confirm="Chcesz usunąć rozdzielnicę {{ $switchboard->name }}?"
                                    class="inline-flex items-center justify-center px-4 py-2 text-center mx-auto font-medium
                                    text-white bg-black rounded-lg shadow-sm hover:bg-gray-800
                                    focus:outline-none focus:ring-2 focus:ring-gray-300
                                    dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800
                                     whitespace-nowrap">
                                    Usuń
                                    </buttno>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <span class="block italic">Brak przypisanych rozdzielnic do tego protokołu.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-center mt-2">
            <button type="button" wire:click="createSwitchboard"
                class="inline-flex items-center justify-center px-5 py-2.5
                       text-sm font-semibold text-white bg-black rounded-lg shadow-sm
                       hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700 w-full
                       ">
                Dodaj rozdzielnicę
            </button>
        </div>
    </div>
    <div>
        @if ($protocol->switchboards->isEmpty())
            Dodaj tablicę rodzielczą
        @else
            <x-protocol.differential-circuit-breaker :protocol="$protocol" :switchboard="$switchboard" :differential-breakers="$differentialBreakers" />
            <livewire:protocol.insulation-testings-form :protocol="$protocol" />

            <div class="w-full  bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                @forelse ($protocol->switchboards as $switchboard)
                    @if ($switchboard->circuitBreakers->isEmpty())
                        <div class="mb-8 text-center sm:text-left">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Dodaj bezpieczniki do rozdzielnicy
                            </h3>
                            <a href="{{ route('protocol.createSwitchboard', ['switchboard' => $switchboard->id]) }}"
                                class="group inline-flex items-center justify-between w-full sm:w-auto px-6 py-3 mt-2 mb-6
                                   text-sm font-semibold text-white bg-gray-900 rounded-xl shadow-sm 
                                   hover:bg-gray-800 hover:shadow-md 
                                   focus:outline-none focus:ring-4 focus:ring-gray-300 
                                   dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-800 
                                   transition-all duration-200 cursor-pointer">

                                <span>{{ $switchboard->name }}</span>

                                {{-- Animowana strzałka pokazująca się/przesuwająca przy najechaniu --}}
                                <svg class="w-4 h-4 ml-3 transition-transform duration-200 group-hover:translate-x-1"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @else
                        @if ($switchboard->rooms->isEmpty())
                            <div class="mb-8 text-center sm:text-left">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Dodaj pomieszczenia do rozdzielnicy
                                </h3>
                                <a href="{{ route('protocol.formRoom', ['switchboard' => $switchboard->id]) }}"
                                    class="group inline-flex items-center justify-between w-full sm:w-auto px-6 py-3 mt-2 mb-6
                                   text-sm font-semibold text-white bg-gray-900 rounded-xl shadow-sm 
                                   hover:bg-gray-800 hover:shadow-md 
                                   focus:outline-none focus:ring-4 focus:ring-gray-300 
                                   dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-800 
                                   transition-all duration-200 cursor-pointer">

                                    <span>{{ $switchboard->name }}</span>

                                    <svg class="w-4 h-4 ml-3 transition-transform duration-200 group-hover:translate-x-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @else
                            <div class="mt-10">
                                <x-protocol.measure-room :protocol="$protocol" />
                            </div>
                        @endif
                    @endif
                @empty
                @endforelse
            </div>

        @endif
        @if (!$protocol->switchboards->isEmpty() && (!$protocol->wireResistances->isEmpty() || !$protocol->rooms->isEmpty()))
            <livewire:protocol.wire-resistance-form :protocol="$protocol" :is-embedded="true" />
        @else
            <div class="my-10 w-full">
                <a href="{{ route('protocol.wire', ['protocol' => $protocol->id]) }}" wire:navigate
                    class="group flex w-full items-center justify-center gap-2 px-5 py-3.5 
                   text-sm font-semibold text-white bg-gray-900 rounded-xl shadow-sm 
                   hover:bg-gray-800 hover:shadow-md
                   focus:outline-none focus:ring-4 focus:ring-gray-300 
                   dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-800 
                   transition-all duration-200 cursor-pointer">

                    {{-- Ikona Plusa --}}
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4">
                        </path>
                    </svg>

                    Dodaj badanie rezystancji
                </a>
            </div>
        @endif
    </div>



</div>
