document.addEventListener('DOMContentLoaded', function () {

  // ── CUSTOM CURSOR ──
  const cursor = document.getElementById('sao-cursor');
  const ring   = document.getElementById('sao-cursor-ring');
  let mx = 0, my = 0, rx = 0, ry = 0;

  document.addEventListener('mousemove', e => {
    mx = e.clientX; my = e.clientY;
    cursor.style.left = mx + 'px';
    cursor.style.top  = my + 'px';
  });

  function animRing() {
    rx += (mx - rx) * 0.1;
    ry += (my - ry) * 0.1;
    ring.style.left = rx + 'px';
    ring.style.top  = ry + 'px';
    requestAnimationFrame(animRing);
  }
  animRing();

  document.querySelectorAll('a, button, input, .task-item, .reward-item').forEach(el => {
    el.addEventListener('mouseenter', () => {
      cursor.style.width  = '18px';
      cursor.style.height = '18px';
      cursor.style.background = '#ff3355';
      ring.style.width  = '44px';
      ring.style.height = '44px';
      ring.style.borderColor = 'rgba(255,51,85,0.6)';
    });
    el.addEventListener('mouseleave', () => {
      cursor.style.width  = '10px';
      cursor.style.height = '10px';
      cursor.style.background = 'var(--sao-blue)';
      ring.style.width  = '30px';
      ring.style.height = '30px';
      ring.style.borderColor = 'rgba(0,200,255,0.6)';
    });
  });

  // ── SYSTEM CLOCK ──
  function updateClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');
    const el = document.getElementById('system-time');
    if (el) el.textContent = `${h}:${m}:${s}`;
  }
  updateClock();
  setInterval(updateClock, 1000);

  // ── MUSIC ──
  const musicBtn = document.getElementById('musicBtn');
  const audio    = document.getElementById('bgMusic');

  if (audio && musicBtn) {
    audio.volume = 0.3;
    if (localStorage.getItem('sao_music') === 'true') {
      audio.play().catch(() => {});
      musicBtn.textContent = '⏸ PAUSE BGM';
    }
    musicBtn.addEventListener('click', () => {
      if (audio.paused) {
        audio.play();
        musicBtn.textContent = '⏸ PAUSE BGM';
        localStorage.setItem('sao_music', 'true');
      } else {
        audio.pause();
        musicBtn.textContent = '♪ PLAY BGM';
        localStorage.setItem('sao_music', 'false');
      }
    });
  }

  // ── NOTIFICATIONS ──
  window.showNotif = function(msg, type = 'xp') {
    const container = document.getElementById('notif-container');
    if (!container) return;
    const notif = document.createElement('div');
    notif.className = `notif notif-${type}`;
    const icons = { xp: '⚡', gold: '◈', dmg: '💀', buy: '✓' };
    notif.innerHTML = `<span>${icons[type] || '⚡'}</span> ${msg}`;
    container.appendChild(notif);
    setTimeout(() => notif.remove(), 3100);
  };

  // ── AUTO DISMISS FLASH MESSAGES ──
  document.querySelectorAll('.flash-msg').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .3s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 300);
    }, 3000);
  });

  // ── PROGRESS BAR ANIMATE ON LOAD ──
  document.querySelectorAll('.progress-fill').forEach(bar => {
    const target = bar.getAttribute('data-width') || bar.style.width;
    bar.style.width = '0';
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        bar.style.width = target;
      });
    });
  });

  // ── SCROLL REVEAL ──
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.05 });

  document.querySelectorAll('.quest-panel, .shop-panel, .player-hud').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity .5s ease, transform .5s ease';
    observer.observe(el);
  });

  // ── DELETE CONFIRM ──
  document.querySelectorAll('.del-btn[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm('Delete this quest?')) e.preventDefault();
    });
  });

  // ── TASK ITEM COUNT ──
  document.querySelectorAll('.quest-panel').forEach(panel => {
    const items = panel.querySelectorAll('.task-item').length;
    const countEl = panel.querySelector('.panel-count');
    if (countEl) countEl.textContent = `${items} QUEST${items !== 1 ? 'S' : ''}`;
  });

  // ── MODAL PARTICLES ──
  document.querySelectorAll('.modal-particles').forEach(container => {
    for (let i = 0; i < 12; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      p.style.left = Math.random() * 100 + '%';
      p.style.bottom = '0';
      p.style.animationDelay = Math.random() * 2 + 's';
      p.style.animationDuration = (1.5 + Math.random()) + 's';
      container.appendChild(p);
    }
  });
});
