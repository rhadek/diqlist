<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Welcome & Overview -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                                Vítejte zpět, {{ Auth::user()->name }}!
                            </h2>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">
                                {{ now()->translatedFormat('l, j. F Y') }}
                            </p>
                        </div>

                        <div class="mt-4 md:mt-0 flex items-center space-x-3">
                            <div class="flex items-center bg-blue-50 dark:bg-blue-900 px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 text-blue-500 dark:text-blue-400 mr-2"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
                                </svg>
                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Bodový zůstatek:
                                    <span class="font-bold">{{ Auth::user()->points_balance }}</span></span>
                            </div>
                            <div class="flex items-center bg-indigo-50 dark:bg-indigo-900 px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400 mr-2"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">Streak: <span
                                        class="font-bold">{{ $streak }}
                                        {{ Str::plural('den', $streak) }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Statistiky pro dnešek -->
                <div class="md:col-span-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Přehled pro dnešek
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-blue-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="text-blue-500 dark:text-blue-400">
                                            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                <path fill-rule="evenodd"
                                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Úkolů celkem</p>
                                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                                {{ $completedToday + $pendingToday }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="text-green-500 dark:text-green-400">
                                            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Splněno</p>
                                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                                {{ $completedToday }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="text-yellow-500 dark:text-yellow-400">
                                            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Čeká na splnění</p>
                                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                                {{ $pendingToday }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-purple-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="text-purple-500 dark:text-purple-400">
                                            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Úspěšnost</p>
                                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                                {{ $todayCompletionPercentage }}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress bar -->
                            <div class="mt-6">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Průběh plnění
                                        dne</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $completedToday }}/{{ $completedToday + $pendingToday }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full"
                                        style="width: {{ $todayCompletionPercentage }}%"></div>
                                </div>
                            </div>

                            <!-- Získané body dnes -->
                            <!-- Získané body dnes -->
                            <div class="mt-4 flex items-center space-x-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Body dnes:</span>
                                <span
                                    class="text-sm font-medium {{ $todayPoints >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                    {{ $todayPoints > 0 ? '+' . $todayPoints : $todayPoints }}
                                </span>
                            </div>

                            <!-- Dnešní úkoly - seznam -->
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-800 dark:text-white mb-3">Dnešní úkoly</h4>

                                @if (count($todayTasks) > 0)
                                    <div class="space-y-2">
                                        @foreach ($todayTasks as $assignment)
                                            <div
                                                class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md p-3 flex items-center justify-between">
                                                <div class="flex items-center">
                                                    @if ($assignment->status === 'completed')
                                                        <div
                                                            class="h-5 w-5 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">{{ $assignment->task->title }}</span>
                                                    @elseif($assignment->status === 'failed')
                                                        <div
                                                            class="h-5 w-5 bg-red-500 rounded-full flex items-center justify-center">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">{{ $assignment->task->title }}</span>
                                                    @else
                                                        <div
                                                            class="h-5 w-5 border-2 border-gray-300 dark:border-gray-500 rounded-full">
                                                        </div>
                                                        <span
                                                            class="ml-2 text-sm text-gray-800 dark:text-gray-200">{{ $assignment->task->title }}</span>
                                                    @endif
                                                </div>

                                                @if ($assignment->status === 'pending')
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('daily.complete', $assignment) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                <svg class="h-4 w-4" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('daily.fail', $assignment) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                <svg class="h-4 w-4" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <p class="text-gray-500 dark:text-gray-400">Nemáte žádné úkoly na dnešní den.
                                        </p>
                                        <a href="{{ route('daily.index') }}"
                                            class="mt-2 inline-flex items-center text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Přejít do denního plánu
                                        </a>
                                    </div>
                                @endif

                                @if (count($todayTasks) > 0)
                                    <div class="mt-3 text-right">
                                        <a href="{{ route('daily.index') }}"
                                            class="text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                            Zobrazit celý denní plán →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Postranní panel -->
                <div class="md:col-span-4">
                    <!-- Bodová bilance -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Bodová bilance
                            </h3>

                            <div class="text-center">
                                <div
                                    class="inline-flex items-center justify-center h-24 w-24 rounded-full {{ Auth::user()->points_balance >= 0 ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
                                    <span
                                        class="text-3xl font-bold {{ Auth::user()->points_balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ Auth::user()->points_balance }}
                                    </span>
                                </div>

                                <div class="mt-3">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Celkový bodový
                                        zůstatek</span>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Týdenní zisk</span>
                                    <div
                                        class="mt-1 text-lg font-semibold {{ $weekPoints >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $weekPoints > 0 ? '+' . $weekPoints : $weekPoints }}
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Aktuální streak</span>
                                    <div class="mt-1 text-lg font-semibold text-blue-600 dark:text-blue-400">
                                        {{ $streak }} {{ Str::plural('den', $streak) }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <a href="{{ route('points.index') }}"
                                    class="text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                    Zobrazit historii bodů →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Nadcházející úkoly -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Nadcházející úkoly
                            </h3>

                            @if (count($upcomingTasks) > 0)
                                <div class="space-y-3">
                                    @foreach ($upcomingTasks as $task)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-md p-3">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                        {{ $task->title }}</p>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        Termín:
                                                        {{ $task->due_date ? $task->due_date->format('d.m.Y') : 'Není nastaven' }}
                                                    </p>
                                                </div>

                                                <!-- Priorita -->
                                                <div class="flex items-start">
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full
                                                        {{ $task->priority === 1
                                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100'
                                                            : ($task->priority === 2
                                                                ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'
                                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100') }}">
                                                        {{ $task->priority === 1 ? 'Nízká' : ($task->priority === 2 ? 'Střední' : 'Vysoká') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Pokud není úkol přiřazen na dnešek, zobrazit možnost přiřadit -->
                                            @if (!$task->isAssignedToday())
                                                <div class="mt-2">
                                                    <form action="{{ route('tasks.assign-today', $task) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full inline-flex justify-center items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-100 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            <svg class="-ml-0.5 mr-1 h-4 w-4"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Přidat na dnešek
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="{{ route('tasks.index') }}" class="text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                            Zobrazit všechny úkoly →
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <p class="text-gray-500 dark:text-gray-400">Nemáte žádné nadcházející úkoly.</p>
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
                </div>
            </div>
        </div>

        <!-- Týdenní přehled a statistiky -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Týdenní přehled -->
                    <div class="md:col-span-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    Týdenní přehled
                                </h3>

                                <div class="grid grid-cols-7 gap-2">
                                    @php
                                        $today = now();
                                        $startOfWeek = $today->copy()->startOfWeek();

                                        for ($i = 0; $i < 7; $i++) {
                                            $currentDay = $startOfWeek->copy()->addDays($i);
                                            $isToday = $currentDay->isToday();
                                            $dayName = $currentDay->translatedFormat('D');
                                            $dayNumber = $currentDay->format('j');
                                            $dayData = $weeklyData[$i] ?? ['completion' => 0, 'total' => 0, 'completed' => 0];
                                    @endphp

                                    <div class="border {{ $isToday ? 'border-blue-500 dark:border-blue-400' : 'border-gray-200 dark:border-gray-700' }} rounded-md p-2 {{ $isToday ? 'bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20' : '' }}">
                                        <div class="text-center">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $dayName }}</p>
                                            <p class="text-lg font-bold {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-800 dark:text-white' }}">{{ $dayNumber }}</p>
                                        </div>

                                        @if($dayData['total'] > 0)
                                            <div class="mt-2">
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                                    <div class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full" style="width: {{ $dayData['completion'] }}%"></div>
                                                </div>
                                                <div class="mt-1 text-xs text-center text-gray-500 dark:text-gray-400">
                                                    {{ $dayData['completed'] }}/{{ $dayData['total'] }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-2 text-xs text-center text-gray-400 dark:text-gray-500">
                                                -
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        }
                                    @endphp
                                </div>

                                <div class="mt-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Týdenní úspěšnost</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $completedThisWeek }}/{{ $totalTasksThisWeek }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $weekCompletionPercentage }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="{{ route('daily.calendar') }}" class="text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                        Zobrazit kalendář →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kategorie -->
                    <div class="md:col-span-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    Kategorie
                                </h3>

                                @if(count($categories) > 0)
                                    <div class="space-y-3">
                                        @foreach($categories as $category)
                                            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-md">
                                                <div class="flex items-center">
                                                    <div class="h-4 w-4 rounded-full" style="background-color: {{ $category->color_code }}"></div>
                                                    <span class="ml-2 text-sm font-medium text-gray-800 dark:text-white">{{ $category->name }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $category->tasks_count }} úkolů
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="{{ route('categories.index') }}" class="text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                            Spravovat kategorie →
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <p class="text-gray-500 dark:text-gray-400">Zatím nemáte žádné kategorie.</p>
                                        <a href="{{ route('categories.create') }}" class="mt-2 inline-flex items-center text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Vytvořit kategorii
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
