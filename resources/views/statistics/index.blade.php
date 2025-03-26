<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Statistiky') }}
        </h2>
    </x-slot>
    <div class="flex space-x-2">
        <a href="{{ route('stats.productivity') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Produktivita
        </a>

        <a href="{{ route('stats.categories') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Kategorie
        </a>

        <a href="{{ route('points.stats') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
            </svg>
            Bodový vývoj
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Celkové statistiky -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Celkové statistiky</h3>

                <dl class="mt-5 grid grid-cols-1 gap-5">
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Celkem úkolů</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:text-right">{{ $totalTasks }}</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Úkolů dokončeno</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:text-right">{{ $completedTasks }}</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Úspěšnost plnění</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:text-right">{{ $successRate }}%</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bodová bilance</dt>
                        <dd class="mt-1 text-sm font-medium {{ $totalPoints >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }} sm:mt-0 sm:text-right">{{ $totalPoints }}</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktuální streak</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:text-right">{{ $currentStreak }} dnů</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Průměrné hodnoty -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Průměrné hodnoty</h3>

                <dl class="mt-5 grid grid-cols-1 gap-5">
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Úkolů denně</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:text-right">{{ $tasksPerDay }}</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dokončeno denně</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:text-right">{{ $completedPerDay }}</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Získáno bodů</dt>
                        <dd class="mt-1 text-sm text-green-600 dark:text-green-500 sm:mt-0 sm:text-right">+{{ $positivePoints }}</dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ztraceno bodů</dt>
                        <dd class="mt-1 text-sm text-red-600 dark:text-red-500 sm:mt-0 sm:text-right">{{ $negativePoints }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Nejúspěšnější data -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Nejúspěšnější data</h3>

                <dl class="mt-5 grid grid-cols-1 gap-5">
                    @if(isset($bestCategory))
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:px-6 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nejúspěšnější kategorie</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <div class="h-4 w-4 rounded-full mr-2" style="background-color: {{ $bestCategory->color_code }}"></div>
                                    <span>{{ $bestCategory->name }}</span>
                                </div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $bestCategory->success_rate }}% úspěšnost ({{ $bestCategory->completed_tasks_count }}/{{ $bestCategory->tasks_count }})
                                </div>
                            </dd>
                        </div>
                    @endif

                    @if(isset($worstCategory))
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:px-6 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nejslabší kategorie</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <div class="h-4 w-4 rounded-full mr-2" style="background-color: {{ $worstCategory->color_code }}"></div>
                                    <span>{{ $worstCategory->name }}</span>
                                </div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $worstCategory->success_rate }}% úspěšnost ({{ $worstCategory->completed_tasks_count }}/{{ $worstCategory->tasks_count }})
                                </div>
                            </dd>
                        </div>
                    @endif

                    @if(isset($bestDay))
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:px-6 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nejúspěšnější den v týdnu</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $bestDay->day_name }}
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $bestDay->success_rate }}% úspěšnost ({{ $bestDay->completed }}/{{ $bestDay->total }})
                                </div>
                            </dd>
                        </div>
                    @endif

                    @if(isset($worstDay))
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:px-6 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nejslabší den v týdnu</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $worstDay->day_name }}
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $worstDay->success_rate }}% úspěšnost ({{ $worstDay->completed }}/{{ $worstDay->total }})
                                </div>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Týdenní aktivita -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Týdenní aktivita</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Týden</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Úkolů</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Splněno</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Úspěšnost</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($weekActivityData as $week)
                                <tr>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $week['label'] }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $week['total'] }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $week['completed'] }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 w-24">
                                                <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $week['success_rate'] }}%"></div>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ $week['success_rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Denní aktivita -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Denní aktivita</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    @foreach($dailyActivityData as $day)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white mr-2">{{ $day['day_name'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $day['label'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mr-1">Body:</span>
                                    <span class="text-xs font-medium {{ $day['points'] >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                        {{ $day['points'] > 0 ? '+' . $day['points'] : $day['points'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-md overflow-hidden">
                                @if($day['total'] > 0)
                                    <div class="h-full bg-blue-600 dark:bg-blue-500 text-xs text-white px-2 flex items-center" style="width: {{ $day['completion_rate'] }}%">
                                        {{ $day['completion_rate'] }}%
                                    </div>
                                @else
                                    <div class="h-full bg-gray-300 dark:bg-gray-600 text-xs text-gray-700 dark:text-gray-300 px-2 flex items-center">
                                        Žádné úkoly
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-between mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $day['completed'] }}/{{ $day['total'] }} úkolů</span>
                                <span>{{ $day['completion_rate'] }}% dokončeno</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
