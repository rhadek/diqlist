<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Smazat opakující se ukol') }}
        </h2>
    </x-slot>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Smazat opakující se úkol
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Chystáte se smazat opakující se úkol "{{ $task->title }}". Tento úkol má {{ $task->childTasks()->count() }} souvisejících instancí.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4 mt-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Upozornění</h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>
                                Vyberte jednu z následujících možností:
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Možnosti smazání -->
            <div class="mt-4 space-y-4">
                <form action="{{ route('tasks.delete-recurring', $task) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="delete_parent_only" name="delete_children" type="radio" value="0" checked class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="delete_parent_only" class="font-medium text-gray-700 dark:text-gray-300">Smazat pouze rodičovský úkol</label>
                                <p class="text-gray-500 dark:text-gray-400">Smaže pouze tento úkol. Existující instance zůstanou zachovány, ale nebudou se vytvářet nové.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="delete_all" name="delete_children" type="radio" value="1" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="delete_all" class="font-medium text-gray-700 dark:text-gray-300">Smazat všechny související instance</label>
                                <p class="text-gray-500 dark:text-gray-400">Smaže tento úkol i všechny jeho instance. Tuto akci nelze vrátit.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Smazat úkol
                        </button>
                        <a href="{{ route('tasks.index') }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Zrušit
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
