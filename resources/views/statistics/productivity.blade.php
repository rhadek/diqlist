@extends('layouts.app')

@section('title', 'Statistiky produktivity')

@section('header', 'Statistiky produktivity')

@section('actions')
    <div class="flex space-x-2">
        <a href="{{ route('stats') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Zpět na přehled
        </a>

        <a href="{{ route('stats.categories') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Statistiky kategorií
        </a>
    </div>
@endsection

@section('content')
    <!-- Produktivita po měsících -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Produktivita po měsících
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="w-full h-80">
                <canvas id="monthlyProductivityChart"></canvas>
            </div>

            @if(isset($mostProductiveMonth))
                <div class="mt-6 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nejproduktivnější měsíc</h4>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-xl font-semibold text-gray-900 dark:text-white">{{ $mostProductiveMonth['label'] }}</span>
                        <span class="text-lg font-semibold text-green-600 dark:text-green-500">{{ $mostProductiveMonth['completion_rate'] }}% dokončeno</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="bg-white dark:bg-gray-800 p-2 rounded text-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Úkolů celkem</span>
                            <span class="block text-lg font-medium text-gray-900 dark:text-white">{{ $mostProductiveMonth['total'] }}</span>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-2 rounded text-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Bodový zisk</span>
                            <span class="block text-lg font-medium {{ $mostProductiveMonth['points'] >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                {{ $mostProductiveMonth['points'] > 0 ? '+' . $mostProductiveMonth['points'] : $mostProductiveMonth['points'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Produktivita podle dne v týdnu -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Produktivita podle dne v týdnu
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="w-full h-72">
                    <canvas id="weekdayProductivityChart"></canvas>
                </div>

                @if(isset($mostProductiveWeekday))
                    <div class="mt-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 p-3 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nejproduktivnější den</h4>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $mostProductiveWeekday['label'] }}</span>
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-500">{{ $mostProductiveWeekday['completion_rate'] }}% dokončeno</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Produktivita podle denní doby -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Produktivita podle denní doby
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="w-full h-72">
                    <canvas id="timeOfDayProductivityChart"></canvas>
                </div>

                @if(isset($mostProductiveTimeOfDay))
                    <div class="mt-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 p-3 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nejproduktivnější část dne</h4>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $mostProductiveTimeOfDay['label'] }}</span>
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-500">{{ $mostProductiveTimeOfDay['completion_rate'] }}% dokončení</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabulka produktivity po měsících -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Detailní přehled po měsících
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Měsíc</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Úkolů celkem</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dokončeno</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Úspěšnost</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Body</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($monthlyData as $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data['label'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data['total'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data['completed'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 w-24">
                                        <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $data['completion_rate'] }}%"></div>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ $data['completion_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $data['points'] >= 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                {{ $data['points'] > 0 ? '+' . $data['points'] : $data['points'] }}
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
            const monthlyData = @json($monthlyData);
            const weekdayData = @json($weekdayData);
            const timeOfDayData = @json($timeOfDayData);

            // Příprava dat pro graf měsíční produktivity
            const months = monthlyData.map(item => item.label);
            const completionRates = monthlyData.map(item => item.completion_rate);
            const tasksCompleted = monthlyData.map(item => item.completed);
            const tasksTotal = monthlyData.map(item => item.total);

            // Vytvoření grafu měsíční produktivity
            const ctxMonthly = document.getElementById('monthlyProductivityChart').getContext('2d');
            new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Celkem úkolů',
                            data: tasksTotal,
                            backgroundColor: 'rgba(107, 114, 128, 0.3)',
                            borderColor: 'rgba(107, 114, 128, 1)',
                            borderWidth: 1,
                            order: 2
                        },
                        {
                            label: 'Dokončené úkoly',
                            data: tasksCompleted,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            order: 1
                        },
                        {
                            label: 'Úspěšnost v %',
                            data: completionRates,
                            type: 'line',
                            fill: false,
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.5)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                            tension: 0.1,
                            yAxisID: 'y1',
                            order: 0
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
                            title: {
                                display: true,
                                text: 'Počet úkolů'
                            },
                            grid: {
                                borderDash: [2, 4],
                                color: 'rgba(160, 174, 192, 0.2)'
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Úspěšnost (%)'
                            },
                            grid: {
                                display: false
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

            // Příprava dat pro graf produktivity podle dne v týdnu
            const weekdays = weekdayData.map(item => item.label);
            const weekdayCompletionRates = weekdayData.map(item => item.completion_rate);

            // Vytvoření grafu produktivity podle dne v týdnu
            const ctxWeekday = document.getElementById('weekdayProductivityChart').getContext('2d');
            new Chart(ctxWeekday, {
                type: 'bar',
                data: {
                    labels: weekdays,
                    datasets: [{
                        label: 'Úspěšnost dokončení (%)',
                        data: weekdayCompletionRates,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(16, 185, 129, 0.7)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(16, 185, 129, 1)'
                        ],
                        borderWidth: 1
                    }]
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
                            max: 100,
                            title: {
                                display: true,
                                text: 'Úspěšnost (%)'
                            },
                            grid: {
                                borderDash: [2, 4],
                                color: 'rgba(160, 174, 192, 0.2)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Příprava dat pro graf produktivity podle denní doby
            const timeSlots = timeOfDayData.map(item => item.label);
            const timeSlotCompletionRates = timeOfDayData.map(item => item.completion_rate);

            // Vytvoření grafu produktivity podle denní doby
            const ctxTimeOfDay = document.getElementById('timeOfDayProductivityChart').getContext('2d');
            new Chart(ctxTimeOfDay, {
                type: 'doughnut',
                data: {
                    labels: timeSlots,
                    datasets: [{
                        data: timeSlotCompletionRates,
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(99, 102, 241, 0.7)',
                            'rgba(139, 92, 246, 0.7)'
                        ],
                        borderColor: [
                            'rgba(245, 158, 11, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(99, 102, 241, 1)',
                            'rgba(139, 92, 246, 1)'
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
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return `${label}: ${value}% dokončeno`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
