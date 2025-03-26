<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nový ukol') }}
        </h2>
    </x-slot>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Název úkolu -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Název úkolu <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte název úkolu...">
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Popis -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Popis</label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte popis úkolu...">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Priorita -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priorita <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select name="priority" id="priority" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Nízká</option>
                                <option value="2" {{ old('priority', 2) == 2 ? 'selected' : '' }}>Střední</option>
                                <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>Vysoká</option>
                            </select>
                        </div>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategorie -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategorie</label>
                        <div class="mt-1">
                            <select name="category_id" id="category_id" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Vyberte kategorii --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bodové ohodnocení -->
                    <div>
                        <label for="points_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bodové ohodnocení</label>
                        <div class="mt-1">
                            <input type="number" name="points_value" id="points_value" value="{{ old('points_value') }}" min="1" max="100" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Automaticky podle priority">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pokud nezadáte, bude automaticky nastaveno podle priority</p>
                        @error('points_value')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Termín dokončení -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Termín dokončení</label>
                    <div class="mt-1">
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                    </div>
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opakování -->
                <div x-data="{ recurring: {{ old('recurring') ? 'true' : 'false' }} }">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="recurring" name="recurring" type="checkbox" x-model="recurring" value="1" {{ old('recurring') ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="recurring" class="font-medium text-gray-700 dark:text-gray-300">Opakující se úkol</label>
                            <p class="text-gray-500 dark:text-gray-400">Po dokončení se automaticky vytvoří další instance úkolu.</p>
                        </div>
                    </div>

                    <!-- Nastavení opakování - zobrazí se pouze pokud je úkol opakující se -->
                    <div x-show="recurring" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Typ opakování -->
                        <div>
                            <label for="recurring_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Typ opakování</label>
                            <div class="mt-1">
                                <select name="recurring_type" id="recurring_type" x-bind:required="recurring" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    <option value="daily" {{ old('recurring_type') == 'daily' ? 'selected' : '' }}>Denně</option>
                                    <option value="weekly" {{ old('recurring_type') == 'weekly' ? 'selected' : '' }}>Týdně</option>
                                    <option value="monthly" {{ old('recurring_type') == 'monthly' ? 'selected' : '' }}>Měsíčně</option>
                                </select>
                            </div>
                            @error('recurring_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Interval opakování -->
                        <div>
                            <label for="recurring_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Interval</label>
                            <div class="mt-1">
                                <input type="number" name="recurring_interval" id="recurring_interval" value="{{ old('recurring_interval', 1) }}" min="1" x-bind:required="recurring" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Počet dnů, týdnů nebo měsíců mezi opakováními</p>
                            @error('recurring_interval')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Přiřadit na dnešní den -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="assign_today" name="assign_today" type="checkbox" value="1" {{ old('assign_today', true) ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="assign_today" class="font-medium text-gray-700 dark:text-gray-300">Přiřadit na dnešní den</label>
                        <p class="text-gray-500 dark:text-gray-400">Úkol bude automaticky přidán do dnešního seznamu úkolů.</p>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 flex justify-end space-x-2">
                <a href="{{ route('tasks.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Zrušit
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Vytvořit úkol
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
