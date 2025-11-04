// script.js

document.addEventListener('DOMContentLoaded', function() {
  const carouselImages = document.querySelector('.carrossel-imagens');
  const slides = carouselImages.querySelectorAll('.slide');
  const prevBtn = document.querySelector('.carrossel-btn.prev');
  const nextBtn = document.querySelector('.carrossel-btn.next');
  const dots = document.querySelectorAll('.dot');
  let currentIndex = 0;
  let autoPlayInterval;
  const autoPlayDelay = 4000; // 4 segundos

  function updateDots() {
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
  }

  function goToSlide(index) {
    // Garante que o índice esteja dentro dos limites
    if (index < 0) index = slides.length - 1;
    if (index >= slides.length) index = 0;
    
    carouselImages.style.transform = `translateX(-${index * 100}%)`;
    currentIndex = index;
    updateDots();
  }

  function nextSlide() {
    goToSlide(currentIndex + 1);
  }

  function startAutoPlay() {
    autoPlayInterval = setInterval(nextSlide, autoPlayDelay);
  }

  function stopAutoPlay() {
    clearInterval(autoPlayInterval);
  }

  function restartAutoPlay() {
    stopAutoPlay();
    startAutoPlay();
  }

  // Event listeners para os botões
  prevBtn.addEventListener('click', function() {
    goToSlide(currentIndex - 1);
    restartAutoPlay(); // Reinicia o timer após interação manual
  });

  nextBtn.addEventListener('click', function() {
    goToSlide(currentIndex + 1);
    restartAutoPlay(); // Reinicia o timer após interação manual
  });

  // Event listeners para os dots
  dots.forEach((dot, index) => {
    dot.addEventListener('click', function() {
      goToSlide(index);
      restartAutoPlay(); // Reinicia o timer após interação manual
    });
  });

  // Pausar autoplay quando o mouse estiver sobre o carrossel
  const carousel = document.querySelector('.carrossel');
  carousel.addEventListener('mouseenter', stopAutoPlay);
  carousel.addEventListener('mouseleave', startAutoPlay);

  // Inicializar
  updateDots();
  startAutoPlay();
});


document.addEventListener('DOMContentLoaded', function() {
  const btn = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.menu__links');
  const nav = document.querySelector('.menu__nav'); // Adicionar referência ao nav
  const menuLinks = document.querySelectorAll('.menu__links a'); // Todos os links do menu
  
  btn.addEventListener('click', function() {
    menu.classList.toggle('menu-aberto');
    nav.classList.toggle('menu-ativo'); // Adicionar classe ao nav também
    btn.setAttribute('aria-expanded', menu.classList.contains('menu-aberto'));
  });

  // Fechar menu ao clicar em qualquer link (mobile)
  menuLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 900) { // Apenas em dispositivos mobile/tablet
        menu.classList.remove('menu-aberto');
        nav.classList.remove('menu-ativo');
        btn.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // Fechar menu ao clicar fora dele
  document.addEventListener('click', function(e) {
    if (!nav.contains(e.target) && menu.classList.contains('menu-aberto')) {
      menu.classList.remove('menu-aberto');
      nav.classList.remove('menu-ativo');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
});