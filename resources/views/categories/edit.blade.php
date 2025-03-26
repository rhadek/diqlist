@extends('layouts.app')

@section('title', 'Upravit kategorii')

@section('header', 'Upravit kategorii')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Název kategorie -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Název kategorie <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte název kategorie...">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Barva kategorie -->
                <div>
                    <label for="color_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Barva kategorie</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" name="color_code" id="color_code" value="{{ old('color_code', $category->color_code) }}" class="h-10 w-10 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <input type="text" name="color_code_hex" id="color_code_hex" value="{{ old('color_code', $category->color_code) }}" readonly class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-24 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                    </div>
                    @error('color_code')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <!-- Předvolené barvy -->
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach(['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'] as $color)
                            <button type="button" onclick="document.getElementById('color_code').value='{{ $color }}'; document.getElementById('color_code_hex').value='{{ $color }}'; document.getElementById('color_preview').style.backgroundColor='{{ $color }}25'" class="h-8 w-8 rounded-full border-2 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" style="background-color: {{ $color }}"></button>
                        @endforeach
                    </div>

                    <!-- Náhled -->
                    <div class="mt-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Náhled:</p>
                        <div class="mt-1 p-3 rounded-md" id="color_preview" style="background-color: {{ old('color_code', $category->color_code) }}25">
                            <span class="inline-flex text-sm font-medium px-2.5 py-0.5 rounded-full" style="background-color: {{ old('color_code', $category->color_code) }}25; color: {{ old('color_code', $category->color_code) }};">
                                {{ $category->name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Popis -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Popis kategorie</label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Zadejte popis kategorie...">{{ old('description', $category->description) }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statistiky kategorie -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Statistiky kategorie</h4>
                    <dl class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="bg-white dark:bg-gray-800 px-4 py-3 rounded-md shadow-sm">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Celkem úkolů</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $category->tasks()->count() }}</dd>
                        </div>

                        <div class="bg-white dark:bg-gray-800 px-4 py-3 rounded-md shadow-sm">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Dokončené úkoly</dt>
                            <dd class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-500">{{ $category->tasks()->where('status', 'completed')->count() }}</dd>
                        </div>

                        <div class="bg-white dark:bg-gray-800 px-4 py-3 rounded-md shadow-sm">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Čekající úkoly</dt>
                            <dd class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-500">{{ $category->tasks()->where('status', 'pending')->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 flex justify-end space-x-2">
                <a href="{{ route('categories.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Zrušit
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Uložit změny
                </button>
            </div>
        </form>
    </div>

    <script>
        // Aktualizace náhledu při změně barvy
        document.getElementById('color_code').addEventListener('input', function(e) {
            document.getElementById('color_code_hex').value = e.target.value;
            document.getElementById('color_preview').style.backgroundColor = e.target.value + '25'; // 25 = 15% opacity

            const elements = document.getElementById('color_preview').getElementsByTagName('span');
            for (let i = 0; i < elements.length; i++) {
                elements[i].style.backgroundColor = e.target.value + '25';
                elements[i].style.color = e.target.value;
            }
        });
    </script>
@endsection
