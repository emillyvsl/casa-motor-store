@extends('layouts.store')

@section('title', 'Criar Conta - Casa dos Motores')

@section('content')
<section class="max-w-md mx-auto bg-white rounded-2xl shadow-xl p-8 mt-12 border border-gray-100">
    <h1 class="text-3xl font-bold text-center text-secondary-dark mb-6">
        Criar sua Conta
    </h1>

    {{-- Exibir mensagens de erro --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário de registro --}}
    <form method="POST" action="{{ route('customer.register') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-orange focus:border-primary-orange px-4 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-orange focus:border-primary-orange px-4 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone (opcional)</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-orange focus:border-primary-orange px-4 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Senha</label>
            <input type="password" name="password" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-orange focus:border-primary-orange px-4 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Senha</label>
            <input type="password" name="password_confirmation" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-orange focus:border-primary-orange px-4 py-2">
        </div>

        <button type="submit"
                class="w-full bg-primary-orange hover:bg-orange-600 text-white font-bold py-2 rounded-lg transition shadow-md">
            Criar Conta
        </button>

        {{-- Login com Google --}}
        <div class="flex items-center justify-center mt-6">
            <a href="{{ route('customer.google.redirect') }}"
               class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md w-full transition">
                <i class="fab fa-google"></i> Registrar com Google
            </a>
        </div>

        {{-- Link para login --}}
        <p class="text-center text-sm text-gray-500 mt-6">
            Já tem uma conta?
            <a href="{{ route('customer.login') }}" class="text-primary-orange font-semibold hover:underline">Entrar</a>
        </p>
    </form>
</section>
@endsection
