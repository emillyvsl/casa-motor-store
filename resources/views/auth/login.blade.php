<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        
        {{-- Logo central --}}
        <div class="mb-8 text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Casa dos Motores" class="mx-auto h-20 w-auto drop-shadow-md">
            </a>
            <h2 class="mt-4 text-2xl font-bold text-gray-800 tracking-tight">
                Painel Administrativo
            </h2>
            <p class="text-sm text-gray-500">Acesse sua conta para gerenciar a loja</p>
        </div>

        {{-- Card de login --}}
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('E-mail')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Senha')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-primary-orange shadow-sm focus:ring-primary-orange"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">Lembrar-me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-primary-orange hover:underline"
                           href="{{ route('password.request') }}">
                            Esqueceu a senha?
                        </a>
                    @endif
                </div>

                <!-- Botão de Login -->
                <div class="mt-6">
                    <x-primary-button class="w-full justify-center bg-primary-orange hover:bg-orange-600">
                        Entrar
                    </x-primary-button>
                </div>
            </form>
        </div>

        {{-- Rodapé opcional --}}
        <p class="mt-8 text-xs text-gray-400 text-center">
            © {{ date('Y') }} Casa dos Motores — Acesso restrito à administração
        </p>
    </div>
</x-guest-layout>
