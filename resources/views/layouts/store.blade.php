<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Casa dos Motores</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/css/store.css','resources/css/home.css', 'resources/js/app.js'])



</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <nav class="nav">
      <!-- LOGO -->
      <a href="/" class="logo">
        <img src="/img/logo.png" alt="Casa dos Motores">
      </a>

      <!-- LINKS -->
      <div class="nav-center">
        <a href="/" class="nav-link active">Início</a>
        <a href="/produtos" class="nav-link">Produtos</a>
        <a href="/servicos" class="nav-link">Serviços</a>
        <a href="/sobre" class="nav-link">Sobre Nós</a>
        <a href="/contato" class="nav-link">Contato</a>
      </div>

      <!-- ÍCONES -->
      <div class="nav-icons">
        <i class="fas fa-search nav-icon"></i>
        <i class="fas fa-shopping-cart nav-icon">
          <span class="cart-badge">3</span>
        </i>
        <i class="fas fa-user nav-icon"></i>
        <button class="menu-toggle">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </nav>
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
          <li><a href="/">Início</a></li>
          <li><a href="/produtos">Produtos</a></li>
          <li><a href="/servicos">Serviços</a></li>
          <li><a href="/carrinho">Carrinho</a></li>
          <li><a href="/sobre">Sobre Nós</a></li>
          <li><a href="/privacidade">Política de Privacidade</a></li>
        </ul>
      </div>

      <div class="footer-section fade-in">
        <h3>CONTATO</h3>
        <ul class="contact-info">
          <li><i>Email:</i><span>contato@casadomotores.com</span></li>
          <li><i>Telefone:</i><span>(11) 1234-5678</span></li>
          <li><i>WhatsApp:</i><span>(11) 98765-4321</span></li>
          <li><i>Endereço:</i><span>Rua Exemplo, 123 - São Paulo, SP</span></li>
          <li><i>Horário:</i><span>Seg - Sex: 8h às 18h<br>Sáb: 8h às 12h</span></li>
        </ul>
      </div>

      <div class="footer-section fade-in">
        <h3>SOBRE NÓS</h3>
        <p class="about-text">
          Especialistas em motores e peças automotivas há mais de 15 anos.
          Oferecemos produtos de qualidade, serviços especializados e o melhor atendimento da região.
        </p>
        <div class="social-links">
          <a href="#" class="social-link" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="social-link" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="social-link" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="#" class="social-link" title="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="footer-bottom fade-in">
      <p>&copy; 2025 Casa dos Motores. Todos os direitos reservados. CNPJ: 12.345.678/0001-90</p>
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

    // Ativa link ativo baseado na página atual
    const currentPage = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });

    // Adiciona animação de fade-in
    document.addEventListener('DOMContentLoaded', function() {
      const elements = document.querySelectorAll('.fade-in');
      elements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.2}s`;
      });
    });
  </script>
</body>
</html>