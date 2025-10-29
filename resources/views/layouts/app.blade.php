<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Casa dos Motores') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="font-sans antialiased bg-gray-50">
    <div x-data="{ asideOpen: window.innerWidth >= 1024 }"
         @toggle-sidebar.window="asideOpen = !asideOpen"
         @resize.window="asideOpen = window.innerWidth >= 1024"
         class="flex flex-col h-screen overflow-hidden">

      {{-- Navbar --}}
      @include('layouts.components.navbar')

      <div class="flex flex-1 pt-16">
        {{-- Sidebar --}}
        @include('layouts.components.sidebar')

        {{-- Conteúdo principal --}}
        <main class="flex-1 transition-all duration-300 ease-in-out p-6"
              :class="{ 'ml-64': asideOpen, 'ml-0': !asideOpen }">
          {{ $slot }}
        </main>
      </div>
    </div>
  </body>
</html>
