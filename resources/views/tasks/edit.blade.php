@extends('layouts.app')

@section('title', 'Upravit úkol')

@section('header', 'Upravit úkol')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Název úkolu -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Název úkolu <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte název úkolu...">
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Popis -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Popis</label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte popis úkolu...">{{ old('description', $task->description) }}</textarea>
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
                                <option value="1" {{ old('priority', $task->priority) == 1 ? 'selected' : '' }}>Nízká</option>
                                <option value="2" {{ old('priority', $task->priority) == 2 ? 'selected' : '' }}>Střední</option>
                                <option value="3" {{ old('priority', $task->priority) == 3 ? 'selected' : '' }}>Vysoká</option>
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                            <input type="number" name="points_value" id="points_value" value="{{ old('points_value', $task->points_value) }}" min="1" max="100" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        </div>
                        @error('points_value')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Termín dokončení -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Termín dokončení</label>
                    <div class="mt-1">
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                    </div>
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opakování -->
                <div x-data="{ recurring: {{ old('recurring', $task->recurring) ? 'true' : 'false' }} }">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="recurring" name="recurring" type="checkbox" x-model="recurring" value="1" {{ old('recurring', $task->recurring) ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
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
                                    <option value="daily" {{ old('recurring_type', $task->recurring_type) == 'daily' ? 'selected' : '' }}>Denně</option>
                                    <option value="weekly" {{ old('recurring_type', $task->recurring_type) == 'weekly' ? 'selected' : '' }}>Týdně</option>
                                    <option value="monthly" {{ old('recurring_type', $task->recurring_type) == 'monthly' ? 'selected' : '' }}>Měsíčně</option>
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
                                <input type="number" name="recurring_interval" id="recurring_interval" value="{{ old('recurring_interval', $task->recurring_interval ?? 1) }}" min="1" x-bind:required="recurring" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Počet dnů, týdnů nebo měsíců mezi opakováními</p>
                            @error('recurring_interval')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Přiřadit na dnešní den -->
                @if(!$task->isAssignedToday())
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="assign_today" name="assign_today" type="checkbox" value="1" {{ old('assign_today') ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="assign_today" class="font-medium text-gray-700 dark:text-gray-300">Přiřadit na dnešní den</label>
                            <p class="text-gray-500 dark:text-gray-400">Úkol bude přidán do dnešního seznamu úkolů.</p>
                        </div>
                    </div>
                @endif

                <!-- Informace o stavu -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Informace o úkolu</h4>
                    <dl class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stav</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($task->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Čeká na splnění</span>
                                @elseif($task->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Splněno</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Nesplněno</span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Vytvořeno</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->created_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Poslední aktualizace</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->updated_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        @if($task->status !== 'pending')
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $task->status === 'completed' ? 'Dokončeno' : 'Nesplněno' }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->updated_at->format('d.m.Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 flex justify-end space-x-2">
                <a href="{{ route('tasks.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Zrušit
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Uložit změny
                </button>
            </div>
        </form>
    </div>
@endsection
