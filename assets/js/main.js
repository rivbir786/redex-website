// Redex Glass — hafif etkileşim + çerez onayı (bağımlılık yok)
(function () {
  'use strict';
  var MQ = '(max-width:1080px)';
  function isMobile(){ return window.matchMedia(MQ).matches; }

  /* ---------------- Menü (mega + mobil drawer) ---------------- */
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  var back = document.getElementById('navBackdrop');
  if (toggle && nav) {
    var closeNav = function(){ nav.classList.remove('open'); if(back) back.classList.remove('show'); toggle.setAttribute('aria-expanded','false'); document.body.style.overflow=''; };
    var openNav = function(){ nav.classList.add('open'); if(back) back.classList.add('show'); toggle.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; };
    toggle.addEventListener('click', function(){ nav.classList.contains('open') ? closeNav() : openNav(); });
    if (back) back.addEventListener('click', closeNav);
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeNav(); });

    // alt menü accordion (sadece mobil)
    nav.querySelectorAll('.has-sub > a').forEach(function (a) {
      a.addEventListener('click', function (e) {
        if (isMobile()) {
          e.preventDefault();
          var li = a.parentElement;
          nav.querySelectorAll('.has-sub.open').forEach(function(o){ if(o!==li) o.classList.remove('open'); });
          li.classList.toggle('open');
        }
      });
    });

    // mega paneli ekrana sığdır (desktop): taşarsa sola kaydır
    var clampMega = function(li){
      if (isMobile()) return;
      var m = li.querySelector('.mega'); if(!m) return;
      m.style.left = '';
      var r = m.getBoundingClientRect(), vw = document.documentElement.clientWidth, pad = 16;
      if (r.right > vw - pad) {
        var shift = (vw - pad) - r.right;
        if (r.left + shift < pad) shift = pad - r.left;
        m.style.left = Math.round(shift) + 'px';
      }
    };
    nav.querySelectorAll('.has-sub').forEach(function(li){
      li.addEventListener('mouseenter', function(){ clampMega(li); });
      li.addEventListener('focusin', function(){ clampMega(li); });
    });

    // dil seçimi (mobil: oka tıkla aç/kapa)
    var ls = nav.querySelector('.lang-switch'), lc = nav.querySelector('.lang-current');
    if (ls && lc) {
      lc.addEventListener('click', function(e){ if(isMobile()){ e.preventDefault(); ls.classList.toggle('open'); } });
    }

    window.addEventListener('resize', function(){
      if (!isMobile()) closeNav();
      nav.querySelectorAll('.mega').forEach(function(m){ m.style.left=''; });
    });
  }

  /* ---------------- Header gölgesi ---------------- */
  var header = document.getElementById('siteHeader');
  if (header) {
    var onScroll = function () { header.style.boxShadow = window.scrollY > 10 ? '0 4px 20px rgba(19,52,58,.07)' : 'none'; };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------------- Kaydırınca yumuşak görünürlük ---------------- */
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('in'); });
  }

  /* ---------------- Çerez / gizlilik onayı ---------------- */
  (function(){
    var KEY='redex_consent', VER=1, DAYS=365;
    var cc = window.REDEX_CC || {};
    var T_blocked = cc.blocked || 'This content is blocked.';
    var T_load = cc.load || 'Load video';

    function read(){ try{ var m=document.cookie.match(/(?:^|;\s*)redex_consent=([^;]+)/); if(m) return JSON.parse(decodeURIComponent(m[1])); }catch(e){} return null; }
    function write(c){ c.v=VER; c.ts=Date.now(); var d=new Date(Date.now()+DAYS*864e5);
      document.cookie = KEY+'='+encodeURIComponent(JSON.stringify(c))+';expires='+d.toUTCString()+';path=/;SameSite=Lax';
      try{ localStorage.setItem(KEY, JSON.stringify(c)); }catch(e){} }

    var banner = document.getElementById('ccBanner');
    var backEl = document.getElementById('ccModalBack');
    function showBanner(){ if(banner) banner.classList.remove('cc-hidden'); }
    function hideBanner(){ if(banner) banner.classList.add('cc-hidden'); }
    function openPrefs(){ var c=read()||{}; var s=document.getElementById('ccStat'), x=document.getElementById('ccExt');
      if(s) s.checked=!!c.statistics; if(x) x.checked=!!c.external;
      if(backEl){ backEl.classList.remove('cc-hidden'); document.body.style.overflow='hidden'; } }
    function closePrefs(){ if(backEl){ backEl.classList.add('cc-hidden'); document.body.style.overflow=''; } }

    function loadYT(box){
      if(box.querySelector('iframe')) return;
      var id=box.getAttribute('data-yt');
      var f=document.createElement('iframe');
      f.src='https://www.youtube-nocookie.com/embed/'+id+'?rel=0';
      f.setAttribute('allow','accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
      f.setAttribute('allowfullscreen','');
      f.setAttribute('loading','lazy');
      f.setAttribute('title', box.getAttribute('data-title') || 'Video');
      box.innerHTML=''; box.appendChild(f);
    }
    function blockYT(box){
      if(box.querySelector('iframe')) return;
      box.innerHTML='';
      var d=document.createElement('div'); d.className='video-blocked';
      var p=document.createElement('p'); p.textContent=T_blocked; d.appendChild(p);
      var b=document.createElement('button'); b.type='button'; b.className='btn btn-primary'; b.textContent=T_load;
      b.addEventListener('click', function(ev){ ev.stopPropagation(); var c=read()||{necessary:true}; c.external=true; write(c); loadYT(box); });
      d.appendChild(b); box.appendChild(d);
    }
    function refreshYT(){ var ext=(read()||{}).external;
      document.querySelectorAll('.video-box[data-yt]').forEach(function(box){ ext ? loadYT(box) : blockYT(box); }); }
    function finish(c){ write(c); hideBanner(); closePrefs(); refreshYT(); }

    function on(id, fn){ var el=document.getElementById(id); if(el) el.addEventListener('click', fn); }
    on('ccAll',   function(){ finish({necessary:true,statistics:true,external:true}); });
    on('ccAll2',  function(){ finish({necessary:true,statistics:true,external:true}); });
    on('ccEssential', function(){ finish({necessary:true,statistics:false,external:false}); });
    on('ccSave',  function(){
      var s=document.getElementById('ccStat'), x=document.getElementById('ccExt');
      finish({necessary:true, statistics: !!(s&&s.checked), external: !!(x&&x.checked)});
    });
    on('ccPrefs', openPrefs);
    on('ccClose', closePrefs);
    on('ccOpenPrefs', function(e){ if(e&&e.preventDefault) e.preventDefault(); openPrefs(); });
    if(backEl) backEl.addEventListener('click', function(e){ if(e.target===backEl) closePrefs(); });

    var saved=read();
    if(!saved || saved.v!==VER) showBanner();
    refreshYT();
  })();
})();
