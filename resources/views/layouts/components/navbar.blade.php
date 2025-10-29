<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3 flex items-center justify-between">
    <div class="flex items-center">
      <!-- Botão do menu mobile -->
      <button @click="$dispatch('toggle-sidebar')" type="button"
        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Logo -->
      <a href="{{ route('dashboard') }}" class="flex items-center ms-2 md:me-24">
        <img src="{{ asset('img/logo.png') }}" class="h-8 me-3" alt="Casa dos Motores" />
        <span class="text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
          Casa dos Motores
        </span>
      </a>
    </div>

    <!-- Avatar do usuário -->
    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open"
        class="flex items-center justify-center w-8 h-8 bg-orange-600 text-white rounded-full focus:ring-4 focus:ring-orange-300">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </button>

      <div x-show="open" x-cloak x-transition.origin.top.right
           @click.away="open = false"
           class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-600">
          <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
          <p class="text-xs text-gray-500 truncate dark:text-gray-300">{{ Auth::user()->email }}</p>
        </div>
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">
          <li><a href="{{ route('profile.edit') }}"
                 class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Meu Perfil</a></li>
          <li><a href="{{ route('site.home') }}"
                 class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Ir para Loja</a></li>
        </ul>
        <div class="py-1 border-t border-gray-100 dark:border-gray-600">
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-600 dark:hover:text-white">
              Sair
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>
