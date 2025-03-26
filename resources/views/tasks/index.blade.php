@extends('layouts.app')

@section('title', 'Moje úkoly')

@section('header', 'Moje úkoly')

@section('actions')
    <a href="{{ route('tasks.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Nový úkol
    </a>
@endsection

@section('content')
    <!-- Filtr a vyhledávání -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6 overflow-hidden">
        <div class="p-4 sm:p-6">
            <form action="{{ route('tasks.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Vyhledávání -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vyhledávání</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Název nebo popis úkolu...">
                        </div>
                    </div>

                    <!-- Filtr podle stavu -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stav</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Všechny stavy</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Čeká na splnění</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Splněno</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Nesplněno</option>
                        </select>
                    </div>

                    <!-- Filtr podle priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priorita</label>
                        <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Všechny priority</option>
                            <option value="3" {{ request('priority') == '3' ? 'selected' : '' }}>Vysoká</option>
                            <option value="2" {{ request('priority') == '2' ? 'selected' : '' }}>Střední</option>
                            <option value="1" {{ request('priority') == '1' ? 'selected' : '' }}>Nízká</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filtr podle kategorie -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategorie</label>
                        <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Všechny kategorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                            <option value="null" {{ request('category_id') == 'null' ? 'selected' : '' }}>Bez kategorie</option>
                        </select>
                    </div>

                    <!-- Řazení -->
                    <div>
                        <label for="order_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Řadit podle</label>
                        <select id="order_by" name="order_by" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="due_date" {{ $orderBy == 'due_date' ? 'selected' : '' }}>Termín</option>
                            <option value="priority" {{ $orderBy == 'priority' ? 'selected' : '' }}>Priorita</option>
                            <option value="created_at" {{ $orderBy == 'created_at' ? 'selected' : '' }}>Datum vytvoření</option>
                            <option value="points_value" {{ $orderBy == 'points_value' ? 'selected' : '' }}>Bodová hodnota</option>
                        </select>
                    </div>

                    <!-- Směr řazení -->
                    <div>
                        <label for="direction" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Směr řazení</label>
                        <select id="direction" name="direction" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="asc" {{ $direction == 'asc' ? 'selected' : '' }}>Vzestupně</option>
                            <option value="desc" {{ $direction == 'desc' ? 'selected' : '' }}>Sestupně</option>
                        </select>
                    </div>

                    <!-- Tlačítka -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Filtrovat
                        </button>
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Zrušit filtry
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Seznam úkolů -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Seznam úkolů ({{ $tasks->total() }})
            </h3>
        </div>

        @if($tasks->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($tasks as $task)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Status indikátor -->
                                @if($task->status === 'completed')
                                    <div class="h-5 w-5 rounded-full bg-green-500 mr-3 flex items-center justify-center">
                                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @elseif($task->status === 'failed')
                                    <div class="h-5 w-5 rounded-full bg-red-500 mr-3 flex items-center justify-center">
                                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-5 w-5 rounded-full border border-gray-400 dark:border-gray-500 mr-3"></div>
                                @endif

                                <!-- Název úkolu -->
                                <p class="text-sm font-medium text-gray-900 dark:text-white {{ $task->status === 'completed' ? 'line-through text-gray-500 dark:text-gray-400' : '' }}">
                                    {{ $task->title }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2">
                                <!-- Kategorie -->
                                @if($task->category)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $task->category->color_code }}25; color: {{ $task->category->color_code }};">
                                        {{ $task->category->name }}
                                    </span>
                                @endif

                                <!-- Priorita -->
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $task->priority === 1 ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' :
                                       ($task->priority === 2 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' :
                                       'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100') }}">
                                    {{ $task->priority === 1 ? 'Nízká' : ($task->priority === 2 ? 'Střední' : 'Vysoká') }}
                                </span>

                                <!-- Body -->
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                                    {{ $task->points_value }} bodů
                                </span>

                                <!-- Termín -->
                                @if($task->due_date)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $task->due_date->isPast() && $task->status === 'pending' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                        {{ $task->due_date->format('d.m.Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Popis -->
                        @if($task->description)
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ Str::limit($task->description, 100) }}
                            </div>
                        @endif

                        <!-- Akce -->
                        <div class="mt-3 flex justify-end space-x-2">
                            @if($task->status === 'pending')
                                <!-- Přiřadit na dnešek -->
                                @if(!$task->isAssignedToday())
                                    <form action="{{ route('tasks.assign-today', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            Na dnes
                                        </button>
                                    </form>
                                @endif

                                <!-- Splnit -->
                                <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Splnit
                                    </button>
                                </form>

                                <!-- Nesplnit -->
                                <form action="{{ route('tasks.fail', $task) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Nesplnit
                                    </button>
                                </form>
                            @endif

                            <!-- Upravit -->
                            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Upravit
                            </a>

                            <!-- Smazat -->
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu chcete smazat tento úkol?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Smazat
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Stránkování -->
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-8 sm:px-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Žádné úkoly</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Začněte vytvořením nového úkolu.</p>
                <div class="mt-6">
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Nový úkol
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
