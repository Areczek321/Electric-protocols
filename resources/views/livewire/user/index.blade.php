<div class="w-full space-y-4">
    @forelse ($clients as $client)
        <div
            class="w-full p-5 md:p-6 bg-white dark:bg-gray-800
                       border border-gray-200 dark:border-gray-700
                       rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 items-center">

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Imię
                    </p>
                    @if ($isEditing[$client->id] ?? false)
                        <input type="text" wire:model="formData.{{ $client->id }}.name"
                            class="text-sm font-semibold text-gray-900 dark:text-white w-full rounded border-0 px-2 py-1 outline-none  focus:ring-2 focus:ring-blue-500 bg-gray-200 dark:bg-gray-700">
                    @else
                        <p class="text-sm font-semibold text-gray-900 dark:text-white px-2 py-1">
                            {{ $client->name }}
                        </p>
                    @endif
                </div>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Nazwisko
                    </p>
                    @if ($isEditing[$client->id] ?? false)
                        <input type="text" wire:model="formData.{{ $client->id }}.surname"
                            class="text-sm font-semibold text-gray-900 dark:text-white w-full rounded border-0 px-2 py-1 outline-none  focus:ring-2 focus:ring-blue-500 bg-gray-200 dark:bg-gray-700">
                    @else
                        <p class="text-sm font-semibold text-gray-900 dark:text-white px-2 py-1">
                            {{ $client->surname }}
                        </p>
                    @endif
                </div>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Telefon
                    </p>
                    @if ($isEditing[$client->id] ?? false)
                        <input type="text" wire:model="formData.{{ $client->id }}.phone"
                            class="text-sm font-semibold text-gray-900 dark:text-white w-full rounded border-0 px-2 py-1 outline-none  focus:ring-2 focus:ring-blue-500 bg-gray-200 dark:bg-gray-700">
                    @else
                        <p class="text-sm font-semibold text-gray-900 dark:text-white px-2 py-1">
                            {{ $client->phone }}
                        </p>
                    @endif
                </div>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Adres
                    </p>
                    @if ($isEditing[$client->id] ?? false)
                        <input type="text" wire:model="formData.{{ $client->id }}.address"
                            class="text-sm font-semibold text-gray-900 dark:text-white w-full rounded border-0 px-2 py-1 outline-none  focus:ring-2 focus:ring-blue-500 bg-gray-200 dark:bg-gray-700">
                    @else
                        <p class="text-sm font-semibold text-gray-900 dark:text-white px-2 py-1">
                            {{ $client->address }}
                        </p>
                    @endif
                </div>

                <div class="flex justify-start xl:justify-end gap-2">
                    @if ($isEditing[$client->id] ?? false)
                        <button wire:click="update({{ $client->id }})"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-green-600 text-sm font-semibold text-white hover:bg-green-700 ">
                            Zapisz
                        </button>
                        <button wire:click="toggleEdit({{ $client->id }})"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-gray-500 text-sm font-semibold text-white hover:bg-gray-600 ">
                            Anuluj
                        </button>
                    @else
                        <button wire:click="removeClient({{ $client->id }})" wire:confirm="Czy chcesz usunąć klienta wraz z protokołami ?"
                            class="sm:inline-flex hidden items-center justify-center px-4 py-2 rounded-full bg-red-600 text-sm font-semibold text-white hover:bg-red-400 ">
                            Usuń
                        </button>
                        <button wire:click="toggleEdit({{ $client->id }})"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-gray-500 text-sm font-semibold text-white hover:bg-gray-600 ">
                            Edytuj
                        </button>
                        <a href="{{ route('protocol.create', ['client' => $client->id]) }}" wire:navigate
                            aria-label="Przejdź do formularza"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-black text-sm font-semibold text-white hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800  whitespace-nowrap">
                            <span class="mr-1.5 text-lg leading-none">+</span> Dodaj protokół
                        </a>
                    @endif
                </div>
            </div>

            @if ($client->protocols->isNotEmpty())
                <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="mb-3 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Wygenerowane protokoły
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($client->protocols as $protocol)
                            <a href="{{ route('protocol.show', ['protocol' => $protocol->id]) }}" wire:navigate
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium 
                                                   text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 
                                                   dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 
                                                   ">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Protokół:
                                {{ $protocol->client->surname . ' ' }}{{ date('Y', strtotime($protocol->date)) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div
            class="w-full p-8 text-center bg-white dark:bg-gray-800
                       border border-gray-200 dark:border-gray-700
                       rounded-xl shadow-sm">
            <p class="mb-5 text-sm text-gray-600 dark:text-gray-300">
                Nie masz jeszcze żadnych klientów.
            </p>

            <a href="{{ route('user.create') }}" wire:navigate
                class="inline-flex items-center justify-center px-5 py-2.5
                           text-sm font-semibold text-white bg-black rounded-lg
                           hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700
                           ">
                Stwórz klienta
            </a>
        </div>
    @endforelse

</div>
