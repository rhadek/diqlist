@extends('layouts.app')

@section('title', 'Kalendář')

@section('header')
    Kalendář: {{ $formattedMonth }}
@endsection

@section('actions')
    <div class="flex space-x-2">
        <a href="{{ route('daily.calendar', ['month' => $previousMonth->month, 'year' => $previousMonth->year]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Předchozí měsíc
        </a>

        <a href="{{ route('daily.calendar', ['month' => Carbon\Carbon::now()->month, 'year' => Carbon\Carbon::now()->year]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
            Aktuální měsíc
        </a>

        <a href="{{ route('daily.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Další měsíc
            <svg class="-mr-1 ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>

        <a href="{{ route('daily.index') }}" class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
            </svg>
            Denní přehled
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <!-- Kalendářová mřížka -->
            <div class="grid grid-cols-7 gap-1">
                <!-- Dny v týdnu -->
                @foreach(['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne'] as $dayName)
                    <div class="text-center py-2 font-semibold text-gray-700 dark:text-gray-300">
                        {{ $dayName }}
                    </div>
                @endforeach

                <!-- Dny v měsíci -->
                @php
                    $firstDayOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);
                    $daysInMonth = $firstDayOfMonth->daysInMonth;

                    // Určení počtu prázdných polí před prvním dnem měsíce (0 = pondělí, 6 = neděle)
                    $emptyDaysBefore = ($firstDayOfMonth->dayOfWeek === 0) ? 6 : $firstDayOfMonth->dayOfWeek - 1;
                @endphp

                <!-- Prázdné buňky před prvním dnem měsíce -->
                @for($i = 0; $i < $emptyDaysBefore; $i++)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-md p-2 h-28 opacity-50"></div>
                @endfor

                <!-- Dny měsíce -->
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = Carbon\Carbon::createFromDate($year, $month, $day);
                        $isToday = $currentDate->isToday();
                        $isPast = $currentDate->isPast();
                        $isFuture = $currentDate->isFuture();

                        // Získání dat pro tento den
                        $dayData = $dailyStats[$day] ?? null;
                    @endphp

                    <div class="relative rounded-md p-2 h-28
                        {{ $isToday ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20' : 'bg-white dark:bg-gray-800' }}
                        {{ $isPast && !$isToday ? 'bg-gray-50 dark:bg-gray-700' : '' }}
                        shadow-sm">

                        <!-- Číslo dne -->
                        <div class="flex justify-between items-start">
                            <span class="text-lg font-medium
                                {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">
                                {{ $day }}
                            </span>

                            <!-- Počet úkolů -->
                            @if(isset($dayData) && $dayData['total'] > 0)
                                <span class="text-xs px-1.5 py-0.5 rounded-full
                                    {{ $dayData['completion'] == 100 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                                      ($dayData['completion'] > 0 ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' :
                                      'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                    {{ $dayData['completed'] }}/{{ $dayData['total'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Přehled úkolů -->
                        <div class="mt-2 overflow-hidden max-h-16">
                            @if(isset($dayData) && $dayData['assignments']->count() > 0)
                                @foreach($dayData['assignments']->take(3) as $assignment)
                                    <div class="text-xs mb-1 truncate
                                        {{ $assignment->status === 'completed' ? 'line-through text-green-600 dark:text-green-400' :
                                          ($assignment->status === 'failed' ? 'line-through text-red-600 dark:text-red-400' :
                                          'text-gray-700 dark:text-gray-300') }}">
                                        • {{ $assignment->task->title }}
                                    </div>
                                @endforeach

                                @if($dayData['assignments']->count() > 3)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        +{{ $dayData['assignments']->count() - 3 }} dalších
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Progress bar -->
                        @if(isset($dayData) && $dayData['total'] > 0)
                            <div class="absolute bottom-2 left-2 right-2">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full" style="width: {{ $dayData['completion'] }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Odkaz na den -->
                        <a href="{{ route('daily.index', ['date' => $currentDate->toDateString()]) }}" class="absolute inset-0 z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md" aria-label="Zobrazit den {{ $currentDate->format('d.m.Y') }}"></a>
                    </div>
                @endfor

                <!-- Prázdné buňky po posledním dni měsíce -->
                @php
                    $lastDayOfMonth = Carbon\Carbon::createFromDate($year, $month, $daysInMonth);
                    $emptyDaysAfter = ($lastDayOfMonth->dayOfWeek === 0) ? 0 : 7 - $lastDayOfMonth->dayOfWeek;
                @endphp

                @for($i = 0; $i < $emptyDaysAfter; $i++)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-md p-2 h-28 opacity-50"></div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Legenda -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mt-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-3">Legenda</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex items-center">
                    <div class="h-5 w-5 rounded-md bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-2 border-blue-500"></div>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Dnešní den</span>
                </div>

                <div class="flex items-center">
                    <div class="h-5 w-5 rounded-md bg-white dark:bg-gray-800 shadow-sm"></div>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Budoucí den</span>
                </div>

                <div class="flex items-center">
                    <div class="h-5 w-5 rounded-md bg-gray-50 dark:bg-gray-700 shadow-sm"></div>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Minulý den</span>
                </div>

                <div class="flex items-center">
                    <div class="inline-block h-1.5 w-8 bg-blue-600 dark:bg-blue-500 rounded-full"></div>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Průběh plnění</span>
                </div>
            </div>
        </div>
    </div>
@endsection
