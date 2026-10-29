// Redex Glass — hafif etkileşim scripti (bağımlılık yok)
(function () {
  'use strict';

  // Mobil menü
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    // Mobilde alt menüleri aç/kapat
    nav.querySelectorAll('.has-sub > a').forEach(function (a) {
      a.addEventListener('click', function (e) {
        if (window.matchMedia('(max-width:760px)').matches) {
          e.preventDefault();
          a.parentElement.classList.toggle('open');
        }
      });
    });
  }

  // Header gölgesi (kaydırınca)
  var header = document.getElementById('siteHeader');
  if (header) {
    var onScroll = function () {
      header.style.boxShadow = window.scrollY > 10 ? '0 4px 20px rgba(19,52,58,.07)' : 'none';
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // YouTube facade — tıklayınca gömülü oynatıcıyı yükle (performans + gizlilik)
  document.querySelectorAll('.video-box[data-yt]').forEach(function (box) {
    var load = function () {
      var id = box.getAttribute('data-yt');
      var ifr = document.createElement('iframe');
      ifr.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
      ifr.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
      ifr.setAttribute('allowfullscreen', '');
      ifr.setAttribute('title', 'Redex Glass');
      box.innerHTML = '';
      box.appendChild(ifr);
    };
    box.addEventListener('click', load);
    box.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); load(); } });
  });

  // Kaydırınca yumuşak görünürlük
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('in'); });
  }
})();
