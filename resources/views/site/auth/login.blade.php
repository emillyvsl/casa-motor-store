@extends('layouts.store')

@section('title', 'Login - Casa dos Motores')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Acesse sua conta
        </h2>

        {{-- Exibir erros --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-300 text-red-700 text-sm rounded-lg p-3">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULÁRIO DE LOGIN --}}
        <form method="POST" action="{{ route('customer.login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input id="email" type="email" name="email" required autofocus
                    value="{{ old('email') }}"
                    class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <span class="ml-2 text-gray-600">Lembrar de mim</span>
                    </label>
                </div>

                <a href="#" class="text-orange-600 hover:underline">Esqueceu a senha?</a>
            </div>

            <button type="submit"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg transition">
                Entrar
            </button>
        </form>

        {{-- Divisor --}}
        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-3 text-gray-500 text-sm">ou</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        {{-- LOGIN COM GOOGLE --}}
        <a href="{{ route('customer.google.redirect') }}"
            class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition w-full">
            <i class="fab fa-google text-lg"></i>
            Entrar com Google
        </a>

        {{-- Registro --}}
        <p class="text-center text-gray-600 text-sm mt-6">
            Ainda não tem uma conta?
            <a href="{{ route('customer.register') }}" class="text-orange-600 font-semibold hover:underline">Cadastre-se</a>
        </p>
    </div>
</div>
@endsection
