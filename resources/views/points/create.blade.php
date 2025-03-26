@extends('layouts.app')

@section('title', 'Ruční úprava bodů')

@section('header', 'Ruční úprava bodů')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <form action="{{ route('points.store') }}" method="POST">
            @csrf

            <div class="px-4 py-5 sm:p-6 space-y-6">
                <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Upozornění</h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <p>
                                    Tato funkce je určena pro ruční úpravy bodů. Běžné body by měly být získávány/odebírány automaticky při plnění/neplnění úkolů.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Počet bodů -->
                <div>
                    <label for="points_change" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Počet bodů <span class="text-red-500">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="points_change" id="points_change" value="{{ old('points_change') }}" required class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte počet bodů...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">±</span>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Použijte kladné číslo pro přidání bodů nebo záporné číslo pro odebrání bodů.
                    </p>
                    @error('points_change')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Popis -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Popis <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte důvod úpravy bodů...">{{ old('description') }}</textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Popište důvod úpravy bodů. Tento popis bude viditelný v historii transakcí.
                    </p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Náhled -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md" x-data="{ points: '{{ old('points_change', 0) }}' }">
                    <h3 class="font-medium text-gray-900 dark:text-white">Náhled transakce</h3>

                    <div class="mt-3 flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Aktuální stav bodů:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->points_balance }}</span>
                    </div>

                    <div class="mt-1 flex items-center justify-between text-sm" x-show="points != 0">
                        <span class="text-gray-500 dark:text-gray-400">Změna:</span>
                        <span class="font-medium" :class="points > 0 ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500'" x-text="points > 0 ? '+' + points : points"></span>
                    </div>

                    <div class="mt-1 flex items-center justify-between text-sm border-t border-gray-200 dark:border-gray-600 pt-2" x-show="points != 0">
                        <span class="text-gray-500 dark:text-gray-400">Nový stav bodů:</span>
                        <span class="font-medium text-gray-900 dark:text-white" x-text="{{ Auth::user()->points_balance }} + Number(points)"></span>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 flex justify-end space-x-2">
                <a href="{{ route('points.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Zrušit
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Uložit úpravu
                </button>
            </div>
        </form>
    </div>

    <script>
        // Aktualizace náhledu při změně počtu bodů
        document.getElementById('points_change').addEventListener('input', function(e) {
            const points = e.target.value;
            document.querySelector('[x-data]').__x.$data.points = points;
        });
    </script>
@endsection
