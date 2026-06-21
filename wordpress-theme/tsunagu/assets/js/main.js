/**
 * つなぐ依頼ページ - フロントスクリプト
 *  1) スクロール出現アニメ
 *  2) PDFドロップダウン（左ボタン）… 1件＝直接表示／2件以上＝リスト表示
 *  3) ポップアップ（右ボタン）… モーダルで説明文を表示（ページ遷移なし）
 * WordPress テーマ化後もそのまま流用できます。
 */
(function () {
  'use strict';

  /* ---------- 1) 出現アニメ ---------- */
  (function () {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var targets = document.querySelectorAll('[data-reveal]');
    if (reduceMotion || !('IntersectionObserver' in window) || !targets.length) {
      targets.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    targets.forEach(function (el) { io.observe(el); });
  })();

  /* ---------- 2) PDFドロップダウン ---------- */
  var dropdowns = Array.prototype.slice.call(document.querySelectorAll('.pdf-dropdown'));

  function closePdfMenus(except) {
    dropdowns.forEach(function (dd) {
      if (dd === except) return;
      var m = dd.querySelector('.pdf-menu');
      var t = dd.querySelector('.pdf-trigger');
      if (m) m.hidden = true;
      if (t && t.hasAttribute('aria-expanded')) t.setAttribute('aria-expanded', 'false');
    });
  }

  function openPdf(link) {
    var href = link.getAttribute('href');
    if ((link.getAttribute('target') || '') === '_blank') window.open(href, '_blank', 'noopener');
    else window.location.href = href;
  }

  dropdowns.forEach(function (dd) {
    var trigger = dd.querySelector('.pdf-trigger');
    var menu = dd.querySelector('.pdf-menu');
    if (!trigger) return;
    var links = menu ? menu.querySelectorAll('a') : [];

    // 0〜1件：リスト不要 → クリックで直接表示
    if (links.length <= 1) {
      dd.classList.add('is-single');
      trigger.setAttribute('aria-haspopup', 'false');
      trigger.removeAttribute('aria-expanded');
      trigger.addEventListener('click', function () {
        if (links.length) openPdf(links[0]);
      });
      return;
    }

    // 2件以上：リストの開閉
    trigger.addEventListener('click', function (e) {
      e.stopPropagation();
      var willOpen = menu.hidden;
      closePdfMenus(dd);
      menu.hidden = !willOpen;
      trigger.setAttribute('aria-expanded', String(willOpen));
    });
    menu.addEventListener('click', function () {
      menu.hidden = true;
      trigger.setAttribute('aria-expanded', 'false');
    });
  });

  document.addEventListener('click', function () { closePdfMenus(); });

  /* ---------- 3) ポップアップ（モーダル） ---------- */
  var modal = document.getElementById('modal');
  if (modal) {
    var titleEl = modal.querySelector('.modal__title');
    var bodyEl = modal.querySelector('.modal__body');
    var closeBtn = modal.querySelector('.modal__close');
    var lastFocus = null;

    var openModal = function (title, html) {
      titleEl.textContent = title || '';
      bodyEl.innerHTML = html || '';
      modal.hidden = false;
      document.body.classList.add('is-locked');
      lastFocus = document.activeElement;
      if (closeBtn) closeBtn.focus();
    };
    var closeModal = function () {
      modal.hidden = true;
      document.body.classList.remove('is-locked');
      if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    };

    document.querySelectorAll('.popup-trigger').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var src = document.getElementById(btn.getAttribute('data-popup') || '');
        openModal(btn.getAttribute('data-popup-title') || btn.textContent.trim(), src ? src.innerHTML : '');
      });
    });

    modal.addEventListener('click', function (e) {
      if (e.target.hasAttribute('data-close')) closeModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' || e.key === 'Esc') {
        if (!modal.hidden) closeModal();
        closePdfMenus();
      }
    });
  }

  /* ---------- 4) TOPIC マーキー ---------- */
  (function () {
    var track = document.querySelector('.topics__track');
    if (!track) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var items = Array.prototype.slice.call(track.children);
    if (!items.length) return;
    // シームレスなループのため、項目を1セット複製する
    items.forEach(function (li) {
      var clone = li.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
    });
    track.classList.add('is-marquee');
  })();

  /* ---------- 5) パスワード表示切替（ログイン画面） ---------- */
  document.querySelectorAll('.pass-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = btn.parentNode.querySelector('input');
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.classList.toggle('is-on', show);
      btn.setAttribute('aria-pressed', String(show));
      btn.setAttribute('aria-label', show ? 'パスワードを隠す' : 'パスワードを表示');
    });
  });
})();
