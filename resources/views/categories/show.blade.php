@extends('layouts.app')

@section('title', $category->name)

@section('header', $category->name)

@section('actions')
    <div class="flex space-x-2">
        <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
            </svg>
            Upravit kategorii
        </a>

        <a href="{{ route('tasks.create') }}?category_id={{ $category->id }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Přidat úkol do kategorie
        </a>
    </div>
@endsection

@section('content')
    <!-- Informace o kategorii -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full mr-3" style="background-color: {{ $category->color_code }}"></div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h2>
                    @if($category->description)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category->description }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md text-center">
                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $category->tasks()->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Celkem úkolů</div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md text-center">
                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $category->tasks()->where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Čeká na splnění</div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md text-center">
                    <div class="text-2xl font-semibold text-green-600 dark:text-green-500">{{ $category->tasks()->where('status', 'completed')->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Splněno</div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md text-center">
                    <div class="text-2xl font-semibold text-red-600 dark:text-red-500">{{ $category->tasks()->where('status', 'failed')->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Nesplněno</div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Úspěšnost plnění úkolů</h3>
                <div class="mt-2">
                    @php
                        $totalTasks = $category->tasks()->whereIn('status', ['completed', 'failed'])->count();
                        $completedTasks = $category->tasks()->where('status', 'completed')->count();
                        $successRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp

                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $successRate }}%"></div>
                        </div>
                        <div class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $successRate }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Čekající úkoly -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Čekající úkoly ({{ $pendingTasks->count() }})
            </h3>
        </div>

        @if($pendingTasks->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($pendingTasks as $task)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Priorita indikátor -->
                                <div class="h-4 w-4 rounded-full
                                    {{ $task->priority === 1 ? 'bg-blue-500' :
                                       ($task->priority === 2 ? 'bg-yellow-500' :
                                       'bg-red-500') }}">
                                </div>

                                <!-- Název úkolu -->
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</h4>
                                    @if($task->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <!-- Termín -->
                                @if($task->due_date)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $task->due_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                        {{ $task->due_date->format('d.m.Y') }}
                                    </span>
                                @endif

                                <!-- Body -->
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                                    {{ $task->points_value }} bodů
                                </span>

                                <!-- Akce -->
                                <div class="flex space-x-1">
                                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center p-1 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 py-5 sm:p-6 text-center text-gray-500 dark:text-gray-400">
                <p>V této kategorii nejsou žádné čekající úkoly.</p>
            </div>
        @endif
    </div>

    <!-- Dokončené úkoly -->
    @if($completedTasks->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Dokončené úkoly ({{ $completedTasks->count() }})
                </h3>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($completedTasks->take(5) as $task)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Ikona splněno -->
                                <div class="h-5 w-5 rounded-full bg-green-500 flex items-center justify-center">
                                    <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>

                                <!-- Název úkolu -->
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium line-through text-gray-500 dark:text-gray-400">{{ $task->title }}</h4>
                                    <p class="text-xs text-green-600 dark:text-green-400">
                                        Dokončeno {{ $task->updated_at->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>

                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100">
                                +{{ $task->points_value }} bodů
                            </span>
                        </div>
                    </div>
                @endforeach

                @if($completedTasks->count() > 5)
                    <div class="px-4 py-3 sm:px-6 text-center">
                        <a href="{{ route('tasks.index', ['category_id' => $category->id, 'status' => 'completed']) }}" class="text-sm text-blue-600 dark:text-blue-500 hover:underline">
                            Zobrazit všechny dokončené úkoly ({{ $completedTasks->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
