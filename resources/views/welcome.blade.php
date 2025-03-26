<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DiqList') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigace -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-blue-600 dark:text-blue-500 font-bold text-2xl">DiqList</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-500 font-medium">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-500 font-medium">Přihlášení</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Registrace</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero sekce -->
        <div class="relative flex items-center justify-center min-h-[500px] bg-gradient-to-r from-blue-600 to-indigo-700 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 text-center text-white py-16">
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">
                    <span class="block">Zbavte se prokrastinace</span>
                    <span class="block">Získejte kontrolu nad svými úkoly</span>
                </h1>
                <p class="mt-6 text-xl max-w-lg mx-auto">
                    Nástroj, který vám pomůže systematicky plnit úkoly, budovat pozitivní návyky a překonat prokrastinaci.
                </p>
                <div class="mt-10 flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Začít zdarma
                    </a>
                    <a href="#features" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-800 bg-opacity-30 hover:bg-opacity-40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Více informací
                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Dekorativní elementy -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-20 left-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
                <div class="absolute bottom-20 right-1/4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
            </div>
        </div>

        <!-- Hlavní funkce -->
        <div id="features" class="py-16 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                        Hlavní funkce
                    </h2>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 mx-auto">
                        Nástroj navržený tak, aby vám pomohl překonat prokrastinaci a zvýšit vaši produktivitu.
                    </p>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Funkce 1 -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md text-blue-600 dark:text-blue-400">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Propracovaný systém úkolů</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Vytvářejte, kategorizujte a plánujte úkoly. Nastavte prioritu, termín dokončení a získejte přehled o svém pokroku.
                            </p>
                        </div>

                        <!-- Funkce 2 -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md text-blue-600 dark:text-blue-400">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Denní plánování</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Efektivně plánujte svůj den. Přiřazujte úkoly k jednotlivým dnům a sledujte svůj denní pokrok v přehledném kalendáři.
                            </p>
                        </div>

                        <!-- Funkce 3 -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md text-blue-600 dark:text-blue-400">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Bodový systém motivace</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Získávejte body za splněné úkoly a ztrácejte je za prokrastinaci. Motivační systém, který vás udrží na cestě k produktivitě.
                            </p>
                        </div>

                        <!-- Funkce 4 -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md text-blue-600 dark:text-blue-400">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Detailní statistiky</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Sledujte své pokroky, analyzujte své návyky a identifikujte oblasti, kde se můžete zlepšit pomocí přehledných statistik.
                            </p>
                        </div>

                        <!-- Funkce 5 -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md text-blue-600 dark:text-blue-400">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Streaky pro konzistenci</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Budujte návyky pomocí sledování "streaky" - počtu dnů v řadě, kdy jste byli aktivní. Zůstaňte motivovaní udržet si svou sérii.
                            </p>
                        </div>

                        <!-- Funkce 6 -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md text-blue-600 dark:text-blue-400">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Opakující se úkoly</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Nastavte opakující se úkoly pro pravidelné povinnosti a návyky. Automatické vytváření dalších instancí po dokončení.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA sekce -->
        <div class="bg-blue-600 dark:bg-blue-700">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 lg:py-16">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                    <div>
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                            Připraveni zbavit se prokrastinace?
                        </h2>
                        <p class="mt-4 text-lg text-blue-100">
                            Začněte už dnes a získejte kontrolu nad svými úkoly. Jednoduchý, ale efektivní systém, který vám pomůže dosáhnout vašich cílů.
                        </p>
                        <div class="mt-8">
                            <div class="inline-flex rounded-md shadow">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                    Registrovat se zdarma
                                </a>
                            </div>
                            @auth
                                <div class="inline-flex rounded-md shadow ml-4">
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-800 bg-opacity-30 hover:bg-opacity-40">
                                        Přejít na dashboard
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                    <div class="mt-8 lg:mt-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                            <div class="px-6 py-8 sm:p-10">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proč používat DiqList?</h3>
                                    <ul class="mt-6 space-y-4">
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="ml-3 text-base text-gray-700 dark:text-gray-300">Zvýšení produktivity a efektivity</p>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="ml-3 text-base text-gray-700 dark:text-gray-300">Budování pozitivních návyků a disciplíny</p>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="ml-3 text-base text-gray-700 dark:text-gray-300">Snížení stresu díky lepší organizaci</p>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="ml-3 text-base text-gray-700 dark:text-gray-300">Motivační systém podporující dosahování cílů</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patička -->
        <footer class="bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="border-t border-gray-200 dark:border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                    <div class="flex space-x-6 md:order-2">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                    </div>
                    <p class="mt-8 text-base text-gray-500 dark:text-gray-400 md:mt-0 md:order-1">
                        &copy; {{ date('Y') }} DiqList. Všechna práva vyhrazena.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
