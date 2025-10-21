// script.js

document.addEventListener('DOMContentLoaded', function() {
  const carouselImages = document.querySelector('.carrossel-imagens');
  const slides = carouselImages.querySelectorAll('.slide');
  const prevBtn = document.querySelector('.carrossel-btn.prev');
  const nextBtn = document.querySelector('.carrossel-btn.next');
  let currentIndex = 0;

  function goToSlide(index) {
    // Garante que o índice esteja dentro dos limites
    if (index < 0) index = slides.length - 1;
    if (index >= slides.length) index = 0;
    
    carouselImages.style.transform = `translateX(-${index * 100}%)`;
    currentIndex = index;
  }

  prevBtn.addEventListener('click', function() {
    goToSlide(currentIndex - 1);
  });

  nextBtn.addEventListener('click', function() {
    goToSlide(currentIndex + 1);
  });
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