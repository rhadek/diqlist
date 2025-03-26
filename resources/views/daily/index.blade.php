@extends('layouts.app')

@section('title', 'Denní plán')

@section('header')
    Denní plán: {{ $formattedDate }}
@endsection

@section('actions')
    <div class="flex space-x-2">
        <a href="{{ route('daily.index', ['date' => $previousDay->toDateString()]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Předchozí den
        </a>

        <a href="{{ route('daily.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
            Dnes
        </a>

        <a href="{{ route('daily.index', ['date' => $nextDay->toDateString()]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Další den
            <svg class="-mr-1 ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>

        <a href="{{ route('daily.calendar') }}" class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z" clip-rule="evenodd" />
            </svg>
            Kalendář
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Levý sloupec - Přehled dne -->
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Přehled dne</h3>

                    <div class="mt-5">
                        <!-- Bodový zisk/ztráta -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bodové skóre:</span>
                            <span class="text-xl font-semibold {{ $pointsToday >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                {{ $pointsToday > 0 ? '+' . $pointsToday : $pointsToday }}
                            </span>
                        </div>

                        <!-- Statistiky úkolů -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Splněné úkoly:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $completedAssignments->count() }} / {{ $pendingAssignments->count() + $completedAssignments->count() + $failedAssignments->count() }}</span>
                        </div>

                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4">
                            @php
                                $total = $pendingAssignments->count() + $completedAssignments->count() + $failedAssignments->count();
                                $completionPercentage = $total > 0 ? round(($completedAssignments->count() / $total) * 100) : 0;
                            @endphp
                            <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                        </div>

                        <!-- Další statistiky -->
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $completedAssignments->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Splněné</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingAssignments->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Čeká</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $failedAssignments->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Nesplněné</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <div class="text-2xl font-semibold {{ $pointsToday >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">{{ $pointsToday }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Body</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Přidat úkol na dnešní den -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Přidat úkol na tento den</h3>

                    @if($availableTasks->count() > 0)
                        <form action="{{ route('daily.assign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date->toDateString() }}">

                            <div class="mb-4">
                                <label for="task_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vyberte úkol</label>
                                <select name="task_id" id="task_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @foreach($availableTasks as $task)
                                        <option value="{{ $task->id }}">
                                            {{ $task->title }} -
                                            @if($task->priority === 3)
                                                Vysoká priorita
                                            @elseif($task->priority === 2)
                                                Střední priorita
                                            @else
                                                Nízká priorita
                                            @endif
                                            ({{ $task->points_value }} bodů)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-start mb-4">
                                <div class="flex items-center h-5">
                                    <input id="is_bonus" name="is_bonus" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 focus:ring-blue-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_bonus" class="font-medium text-gray-700 dark:text-gray-300">Bonus</label>
                                    <p class="text-gray-500 dark:text-gray-400">Označit tento úkol jako bonusový (pro získání extra bodů)</p>
                                </div>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Přidat úkol na tento den
                            </button>
                        </form>
                    @else
                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                            <p>Nemáte k dispozici žádné další úkoly.</p>
                            <a href="{{ route('tasks.create') }}" class="mt-2 inline-flex items-center text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Vytvořit nový úkol
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pravý sloupec - Seznam úkolů -->
        <div class="md:col-span-2">
            <!-- Čekající úkoly -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Úkoly čekající na splnění ({{ $pendingAssignments->count() }})
                    </h3>
                </div>

                @if($pendingAssignments->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($pendingAssignments as $assignment)
                            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <!-- Priorita indikátor -->
                                        <div class="h-4 w-4 rounded-full
                                            {{ $assignment->task->priority === 1 ? 'bg-blue-500' :
                                               ($assignment->task->priority === 2 ? 'bg-yellow-500' :
                                               'bg-red-500') }}">
                                        </div>

                                        <!-- Název úkolu -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $assignment->task->title }}
                                                @if($assignment->is_bonus)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Bonus
                                                    </span>
                                                @endif
                                            </h4>
                                            @if($assignment->task->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($assignment->task->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <!-- Bodová hodnota -->
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                                            {{ $assignment->task->points_value }} bodů
                                        </span>

                                        <!-- Kategorie -->
                                        @if($assignment->task->category)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $assignment->task->category->color_code }}25; color: {{ $assignment->task->category->color_code }};">
                                                {{ $assignment->task->category->name }}
                                            </span>
                                        @endif

                                        <!-- Akce -->
                                        <div class="flex space-x-1">
                                            <!-- Splnit -->
                                            <form action="{{ route('daily.complete', $assignment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Nesplnit -->
                                            <form action="{{ route('daily.fail', $assignment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Odebrat -->
                                            <form action="{{ route('daily.unassign', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu chcete odebrat tento úkol z denního plánu?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center p-1 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-4 py-6 sm:px-6 text-center text-gray-500 dark:text-gray-400">
                        <p>Nemáte žádné čekající úkoly na tento den.</p>
                        <p class="mt-1">Přidejte nový úkol nebo si naplánujte úkoly na další den.</p>
                    </div>
                @endif
            </div>

            <!-- Dokončené úkoly -->
            @if($completedAssignments->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mt-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Splněné úkoly ({{ $completedAssignments->count() }})
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($completedAssignments as $assignment)
                            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <!-- Ikona splněno -->
                                        <div class="h-5 w-5 rounded-full bg-green-500 flex items-center justify-center">
                                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>

                                        <!-- Název úkolu -->
                                        <div>
                                            <h4 class="text-sm font-medium line-through text-gray-500 dark:text-gray-400">
                                                {{ $assignment->task->title }}
                                                @if($assignment->is_bonus)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Bonus
                                                    </span>
                                                @endif
                                            </h4>
                                            <p class="text-xs text-green-600 dark:text-green-400">
                                                Dokončeno {{ $assignment->completed_at ? $assignment->completed_at->format('H:i') : '' }}
                                                • +{{ $assignment->task->points_value }} bodů
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <!-- Kategorie -->
                                        @if($assignment->task->category)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full opacity-60" style="background-color: {{ $assignment->task->category->color_code }}25; color: {{ $assignment->task->category->color_code }};">
                                                {{ $assignment->task->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Nesplněné úkoly -->
            @if($failedAssignments->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mt-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Nesplněné úkoly ({{ $failedAssignments->count() }})
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($failedAssignments as $assignment)
                            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <!-- Ikona nesplněno -->
                                        <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>

                                        <!-- Název úkolu -->
                                        <div>
                                            <h4 class="text-sm font-medium line-through text-gray-500 dark:text-gray-400">
                                                {{ $assignment->task->title }}
                                                @if($assignment->is_bonus)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Bonus
                                                    </span>
                                                @endif
                                            </h4>
                                            <p class="text-xs text-red-600 dark:text-red-400">
                                                Nesplněno {{ $assignment->updated_at->format('H:i') }}
                                                • -{{ $assignment->task->points_value * 2 }} bodů
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <!-- Kategorie -->
                                        @if($assignment->task->category)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full opacity-60" style="background-color: {{ $assignment->task->category->color_code }}25; color: {{ $assignment->task->category->color_code }};">
                                                {{ $assignment->task->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
