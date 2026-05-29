<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Casa dos Motores')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/css/store.css','resources/css/home.css', 'resources/js/app.js'])
</head>
@php
  $customer = auth('customer')->user();
  $isHome = request()->routeIs('site.home');
  $cart = \App\Models\Carts::query()
      ->withSum('items', 'quantity')
      ->when(
          $customer,
          fn ($query) => $query->where('customer_id', $customer->id),
          fn ($query) => $query->where('session_id', session()->getId())->whereNull('customer_id')
      )
      ->first();
  $cartCount = (int) ($cart?->items_sum_quantity ?? 0);
@endphp
<body>
  <header class="header">
    <nav class="nav">
      <a href="{{ route('site.home') }}" class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Casa dos Motores">
      </a>

      <form action="{{ route('site.products') }}" method="GET" class="header-search">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="O que você procura hoje?"
          aria-label="Buscar produtos">
        <button type="submit" aria-label="Buscar">
          <i class="fas fa-magnifying-glass"></i>
        </button>
      </form>

      <div class="nav-right">
        <div class="nav-menu">
          <a href="{{ route('site.home') }}" class="nav-link {{ request()->routeIs('site.home') ? 'active' : '' }}">Início</a>
          <a href="{{ route('site.products') }}" class="nav-link {{ request()->routeIs('site.products*') ? 'active' : '' }}">Produtos</a>
          <a href="{{ route('site.services') }}" class="nav-link {{ request()->routeIs('site.services') ? 'active' : '' }}">Serviços</a>
          <a href="{{ route('site.contact') }}" class="nav-link {{ request()->routeIs('site.contact') ? 'active' : '' }}">Contato</a>
        </div>

        <div class="nav-icons">
          @auth('customer')
            {{-- Dropdown de Perfil (Logado) --}}
            <div class="profile-dropdown">
              <button class="nav-icon nav-icon--account profile-toggle" title="Meu Perfil">
                <i class="fas fa-user"></i>
                <span class="status-indicator online"></span>
              </button>
              <div class="profile-menu">
                <div class="profile-header">
                  <p class="profile-name">{{ auth('customer')->user()->name }}</p>
                  <p class="profile-email">{{ auth('customer')->user()->email }}</p>
                </div>
                <div class="profile-divider"></div>
                <a href="{{ route('customer.orders') }}" class="profile-item">
                  <i class="fas fa-box"></i>
                  <span>Minhas Compras</span>
                </a>
                <a href="{{ route('customer.profile') }}" class="profile-item">
                  <i class="fas fa-user-circle"></i>
                  <span>Meu Perfil</span>
                </a>
                <a href="{{ route('customer.profile') }}?tab=addresses" class="profile-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>Endereços de Entrega</span>
                </a>
                <div class="profile-divider"></div>
                <form action="{{ route('customer.logout') }}" method="POST" class="logout-form">
                  @csrf
                  <button type="submit" class="profile-item profile-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                  </button>
                </form>
              </div>
            </div>
          @else
            {{-- Link de Login (Não logado) --}}
            <a href="{{ route('customer.login') }}" class="nav-login-btn">
              <i class="fas fa-user"></i>
              <span>Entrar</span>
            </a>
          @endauth
          
          <a href="{{ route('site.cart') }}" class="nav-icon" title="Carrinho">
            <i class="fas fa-shopping-cart"></i>
            @if ($cartCount > 0)
              <span class="cart-badge">{{ $cartCount }}</span>
            @endif
          </a>
          <button class="menu-toggle">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>
    </nav>

    <div class="mobile-drawer">
      <div class="nav-center">
        <a href="{{ route('site.home') }}" class="nav-link {{ request()->routeIs('site.home') ? 'active' : '' }}">Início</a>
        <a href="{{ route('site.products') }}" class="nav-link {{ request()->routeIs('site.products*') ? 'active' : '' }}">Produtos</a>
        <a href="{{ route('site.services') }}" class="nav-link {{ request()->routeIs('site.services') ? 'active' : '' }}">Serviços</a>
        <a href="{{ route('site.about') }}" class="nav-link {{ request()->routeIs('site.about') ? 'active' : '' }}">Sobre</a>
        <a href="{{ route('site.contact') }}" class="nav-link {{ request()->routeIs('site.contact') ? 'active' : '' }}">Contato</a>
      </div>
    </div>
  </header>

  <main class="w-full overflow-hidden">
    @yield('content')
</main>


  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section fade-in">
        <h3>LINKS RÁPIDOS</h3>
        <ul class="footer-links">
          <li><a href="{{ route('site.home') }}">Início</a></li>
          <li><a href="{{ route('site.products') }}">Produtos</a></li>
          <li><a href="{{ route('site.services') }}">Serviços</a></li>
          <li><a href="{{ route('site.about') }}">Sobre</a></li>
          <li><a href="{{ route('site.contact') }}">Contato</a></li>
          <li><a href="{{ route('site.cart') }}">Carrinho</a></li>
        </ul>
      </div>

      <div class="footer-section fade-in">
        <h3>CONTATO</h3>
        <ul class="contact-info">
          <li><i>WhatsApp:</i><span><a href="https://wa.me/556899537519" target="_blank" rel="noopener noreferrer">+55 68 9953-7519</a></span></li>
          <li><i>Telefone:</i><span><a href="tel:+556899537519">+55 68 9953-7519</a></span></li>
          <li><i>Instagram:</i><span><a href="https://www.instagram.com/casadosmotoresac/" target="_blank" rel="noopener noreferrer">@casadosmotoresac</a></span></li>
          <li><i>Endereço:</i><span>Rua 6 de Agosto, Bairro 6 de Agosto, Rio Branco - AC</span></li>
          <li><i>Horário:</i><span>Seg a sex: 07h às 17h<br>Sáb: 07h às 11h30</span></li>
        </ul>
      </div>

      <div class="footer-section fade-in">
        <h3>SOBRE NÓS</h3>
        <p class="about-text">
          Loja e assistência técnica em Rio Branco com foco em motores, bombas, peças, acessórios e atendimento técnico para quem precisa de solução rápida e confiável.
        </p>
        <div class="social-links">
          <a href="https://www.instagram.com/casadosmotoresac/" class="social-link" title="Instagram" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://wa.me/556899537519" class="social-link" title="WhatsApp" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="{{ route('site.contact') }}" class="social-link" title="Contato">
            <i class="fas fa-phone"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="footer-bottom fade-in">
      <p>&copy; {{ now()->year }} Casa dos Motores. Todos os direitos reservados.</p>
    </div>
  </footer>

  <!-- BOTÃO VOLTAR AO TOPO -->
  <button class="back-to-top" id="backToTop">
    <i class="fas fa-chevron-up"></i>
  </button>

  <script>
    // Controle do menu mobile
    const menuToggle = document.querySelector('.menu-toggle');
    const navCenter = document.querySelector('.nav-center');

    menuToggle.addEventListener('click', () => {
      navCenter.classList.toggle('active');

      // Muda o ícone do menu
      if (navCenter.classList.contains('active')) {
        menuToggle.innerHTML = '<i class="fas fa-times"></i>';
      } else {
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
      }
    });

    // Fecha o menu ao clicar em um link (mobile)
    navCenter.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navCenter.classList.remove('active');
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
      });
    });

    // Botão voltar ao topo
    const backToTopBtn = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        backToTopBtn.classList.add('visible');
      } else {
        backToTopBtn.classList.remove('visible');
      }
    });

    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Adiciona animação de fade-in
    document.addEventListener('DOMContentLoaded', function() {
      const elements = document.querySelectorAll('.fade-in');
      elements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.2}s`;
      });
    });

    // Profile Dropdown Toggle
    const profileToggle = document.querySelector('.profile-toggle');
    const profileDropdown = document.querySelector('.profile-dropdown');
    const profileMenu = document.querySelector('.profile-menu');

    if (profileToggle) {
      profileToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('active');
      });

      // Fecha o menu ao clicar em um item
      document.querySelectorAll('.profile-item').forEach(item => {
        item.addEventListener('click', () => {
          profileDropdown.classList.remove('active');
        });
      });

      // Fecha o menu ao clicar fora
      document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target)) {
          profileDropdown.classList.remove('active');
        }
      });

      // Fecha o menu ao pressionar ESC
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          profileDropdown.classList.remove('active');
        }
      });
    }
  </script>
</body>
</html>
