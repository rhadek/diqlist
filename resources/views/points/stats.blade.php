<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Statistiky bodů') }}
        </h2>
    </x-slot>


    <a href="{{ route('points.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        Zpět na historii bodů
    </a>


    <!-- Přehled a filtry -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Celková bilance -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Celková bilance</h3>
                    <div class="mt-2 text-3xl font-bold {{ $totalPoints >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                        {{ $totalPoints > 0 ? '+' . $totalPoints : $totalPoints }}
                    </div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        za vybrané období
                    </div>
                </div>

                <!-- Získané body -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Získané body</h3>
                    <div class="mt-2 text-3xl font-bold text-green-600 dark:text-green-500">
                        +{{ $positivePoints }}
                    </div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        za vybrané období
                    </div>
                </div>

                <!-- Ztracené body -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ztracené body</h3>
                    <div class="mt-2 text-3xl font-bold text-red-600 dark:text-red-500">
                        {{ $negativePoints }}
                    </div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        za vybrané období
                    </div>
                </div>
            </div>

            <!-- Filtry pro období -->
            <div class="mt-6">
                <form action="{{ route('points.stats') }}" method="GET" class="flex flex-wrap gap-4">
                    <!-- Filtr podle období -->
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Období</label>
                        <select id="period" name="period" onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Poslední týden</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Poslední měsíc</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Poslední rok</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Graf vývoje bodů -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Vývoj bodů v čase
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="w-full h-80">
                <canvas id="pointsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Další statistiky -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Nejlepší a nejhorší dny -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Nejlepší a nejhorší {{ $groupBy === 'day' ? 'dny' : 'měsíce' }}
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <!-- Nejlepší den/měsíc -->
                @if(isset($mostSuccessful))
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nejlepší {{ $groupBy === 'day' ? 'den' : 'měsíc' }}</h4>
                        <div class="mt-2 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-semibold text-gray-900 dark:text-white">{{ $mostSuccessful['date'] }}</span>
                                <span class="text-xl font-semibold text-green-600 dark:text-green-500">+{{ $mostSuccessful['net'] }} bodů</span>
                            </div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <div class="bg-white dark:bg-gray-800 p-2 rounded text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Získáno</span>
                                    <span class="block text-lg font-medium text-green-600 dark:text-green-500">+{{ $mostSuccessful['positive'] }}</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-2 rounded text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Ztraceno</span>
                                    <span class="block text-lg font-medium text-red-600 dark:text-red-500">{{ $mostSuccessful['negative'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Nejhorší den/měsíc -->
                @if(isset($leastSuccessful))
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nejhorší {{ $groupBy === 'day' ? 'den' : 'měsíc' }}</h4>
                        <div class="mt-2 bg-red-50 dark:bg-red-900 dark:bg-opacity-20 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-semibold text-gray-900 dark:text-white">{{ $leastSuccessful['date'] }}</span>
                                <span class="text-xl font-semibold {{ $leastSuccessful['net'] >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">{{ $leastSuccessful['net'] > 0 ? '+' . $leastSuccessful['net'] : $leastSuccessful['net'] }} bodů</span>
                            </div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <div class="bg-white dark:bg-gray-800 p-2 rounded text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Získáno</span>
                                    <span class="block text-lg font-medium text-green-600 dark:text-green-500">+{{ $leastSuccessful['positive'] }}</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-2 rounded text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Ztraceno</span>
                                    <span class="block text-lg font-medium text-red-600 dark:text-red-500">{{ $leastSuccessful['negative'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Poměr získaných a ztracených bodů -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Poměr získaných a ztracených bodů
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="w-full h-60">
                    <canvas id="pointsRatioChart"></canvas>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg text-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Efektivita</div>
                        @php
                            $efficiency = $positivePoints > 0 && $negativePoints != 0
                                ? round(($positivePoints / abs($negativePoints)) * 100)
                                : 0;
                        @endphp
                        <div class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ $efficiency }}%</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">získané/ztracené body</div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg text-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Denní průměr</div>
                        @php
                            $dailyAverage = count($chartData) > 0
                                ? round($totalPoints / count($chartData), 1)
                                : 0;
                        @endphp
                        <div class="mt-1 text-xl font-semibold {{ $dailyAverage >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">{{ $dailyAverage > 0 ? '+' . $dailyAverage : $dailyAverage }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">bodů za den</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabulka dat -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mt-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Detailní přehled
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Datum</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Získané body</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ztracené body</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Celkem</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($chartData as $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-500">+{{ $data['positive'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-500">{{ $data['negative'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $data['net'] >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                {{ $data['net'] > 0 ? '+' . $data['net'] : $data['net'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript pro grafy -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data z controlleru
            const chartData = @json($chartData);

            // Příprava dat pro graf vývoje bodů
            const dates = chartData.map(item => item.date);
            const netPoints = chartData.map(item => item.net);
            const positivePoints = chartData.map(item => item.positive);
            const negativePoints = chartData.map(item => -item.negative);

            // Vytvoření grafu vývoje bodů
            const ctx = document.getElementById('pointsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Získané body',
                            data: positivePoints,
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Ztracené body',
                            data: negativePoints,
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Celková bilance',
                            data: netPoints,
                            type: 'line',
                            fill: false,
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: 'rgba(160, 174, 192, 0.2)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Vytvoření grafu poměru bodů
            const totalGained = {{ $positivePoints }};
            const totalLost = {{ $negativePoints }};

            const ctxRatio = document.getElementById('pointsRatioChart').getContext('2d');
            new Chart(ctxRatio, {
                type: 'doughnut',
                data: {
                    labels: ['Získané body', 'Ztracené body'],
                    datasets: [{
                        data: [totalGained, Math.abs(totalLost)],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(239, 68, 68, 1)',
                        ],
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
