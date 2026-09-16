<div class="flex items-center justify-center min-h-[80vh]">
    {{-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci --}}

    <div class="w-full md:max-w-4xl p-0 md:p-8 lg:p-10 bg-white shadow-none md:shadow-lg
                rounded-none md:rounded-2xl dark:bg-gray-800 border-0 md:border
                border-gray-100 dark:border-gray-700">
        <h2 class="mb-6 text-xl md:text-2xl font-bold text-center text-gray-800 dark:text-white">
            Wprowadź dane
        </h2>

        <form wire:submit="store" class="space-y-5">
            {{-- W Livewire 3 @csrf nie jest już wymagane, ale można je zostawić --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Imię
                    </label>
                    <input type="text" id="name" wire:model="name" class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                               border rounded-lg focus:ring-blue-500
                               focus:border-blue-500 dark:bg-gray-700 
                               dark:placeholder-gray-400 dark:text-white
                               dark:focus:ring-blue-500 dark:focus:border-blue-500
                                
                               @error('name') border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                    
                    @error('name')
                        <span class="mt-1.5 block text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="surname" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nazwisko
                    </label>
                    <input type="text" id="surname" wire:model="surname" class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                               border rounded-lg focus:ring-blue-500
                               focus:border-blue-500 dark:bg-gray-700 
                               dark:placeholder-gray-400 dark:text-white
                               dark:focus:ring-blue-500 dark:focus:border-blue-500
                                
                               @error('surname') border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                    
                    @error('surname')
                        <span class="mt-1.5 block text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="address" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Adres
                </label>
                <input type="text" id="address" wire:model="address" class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border rounded-lg focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500
                            
                           @error('address') border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                
                @error('address')
                    <span class="mt-1.5 block text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="phone" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Numer telefonu
                    </label>
                    <input type="tel" id="phone" wire:model="phone" maxlength="9" x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                               border rounded-lg focus:ring-blue-500
                               focus:border-blue-500 dark:bg-gray-700 
                               dark:placeholder-gray-400 dark:text-white
                               dark:focus:ring-blue-500 dark:focus:border-blue-500
                                
                               @error('phone') border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                    
                    @error('phone')
                        <span class="mt-1.5 block text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full cursor-pointer px-5 py-2.5 text-sm font-medium text-center text-white
                               bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4
                               focus:outline-none focus:ring-blue-300 dark:bg-blue-500
                               dark:hover:bg-blue-600 dark:focus:ring-blue-800
                               ">
                        Zapisz
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>