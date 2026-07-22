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

  /* ---------- 6) CF7 入力→確認→送信（確認画面の切替＋内容表示） ----------
     対応マークアップ（CF7フォーム内）:
       .cf7-input-screen   … 入力画面に表示する要素（複数可）
       .cf7-confirm-screen … 確認画面に表示する要素（複数可）
       .cf7-confirm-btn    … 「確認する」ボタン（type=button）
       .cf7-back-btn       … 「修正する」ボタン（type=button）
       .cf7c-list          … 入力内容の一覧を差し込む空要素
  ------------------------------------------------------------------- */
  (function () {
    function esc(s) {
      return String(s).replace(/[&<>"]/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
      });
    }
    function scrollToForm(form) {
      var top = form.getBoundingClientRect().top + window.pageYOffset - 24;
      window.scrollTo({ top: top < 0 ? 0 : top, behavior: 'smooth' });
    }
    function labelOf(p) {
      var t = '';
      for (var i = 0; i < p.childNodes.length; i++) {
        var node = p.childNodes[i];
        if (node.nodeType === 3) { t += node.textContent; }
        else if (node.nodeName === 'BR') { break; }
        else if (node.nodeType === 1) {
          if (node.querySelector && node.querySelector('input,textarea,select')) break;
          t += node.textContent;
        }
      }
      return t.replace(/[\s　]+/g, ' ').replace(/[（(]必須[）)]/g, '').trim();
    }
    function optLabel(el) {
      var lab = el.closest ? el.closest('label') : null;
      return lab ? lab.textContent.trim() : el.value;
    }
    function valueOf(p) {
      var vals = [];
      p.querySelectorAll('input[type=text],input[type=email],input[type=tel],input[type=url],input[type=number],input[type=date],textarea,select').forEach(function (el) {
        if (el.value && el.value.trim() !== '') vals.push(el.value.trim());
      });
      p.querySelectorAll('input[type=radio]:checked,input[type=checkbox]:checked').forEach(function (el) {
        vals.push(optLabel(el));
      });
      return vals.join(' / ');
    }
    function buildSummary(form) {
      var rows = [];
      form.querySelectorAll('.cf7-input-screen p').forEach(function (p) {
        if (!p.querySelector('input:not([type=submit]):not([type=button]):not([type=hidden]), textarea, select')) return;
        var label = labelOf(p);
        var val = valueOf(p);
        if (label && val !== '') {
          rows.push('<div class="cf7c-row"><span class="cf7c-label">' + esc(label) + '</span><span class="cf7c-value">' + esc(val) + '</span></div>');
        }
      });
      return rows.length ? rows.join('') : '<p>入力内容がありません。</p>';
    }

    document.querySelectorAll('form.wpcf7-form').forEach(function (form) {
      var confirmBtn = form.querySelector('.cf7-confirm-btn');
      var backBtn = form.querySelector('.cf7-back-btn');
      var list = form.querySelector('.cf7c-list');
      if (!confirmBtn) return;

      // 必須項目：日本語メッセージ「この項目は必須です。」＋赤ハイライト
      var requireds = [];
      form.querySelectorAll('[aria-required="true"]').forEach(function (el) {
        if (el.type === 'radio' || el.type === 'checkbox') return;
        requireds.push(el);
        el.addEventListener('invalid', function () {
          if (el.validity.valueMissing) el.setCustomValidity('この項目は必須です。');
          else if (el.validity.typeMismatch) el.setCustomValidity('入力形式が正しくありません。');
          else el.setCustomValidity('');
          el.classList.add('is-invalid');
        });
        el.addEventListener('input', function () { el.setCustomValidity(''); el.classList.remove('is-invalid'); });
      });

      confirmBtn.addEventListener('click', function () {
        // CF7必須（aria-required）を一時的にHTML5必須にしてブラウザ検証
        requireds.forEach(function (el) { el.required = true; });
        if (typeof form.reportValidity === 'function' && !form.reportValidity()) return;
        if (list) list.innerHTML = buildSummary(form);
        form.classList.add('is-confirming');
        scrollToForm(form);
      });

      if (backBtn) {
        backBtn.addEventListener('click', function () {
          form.classList.remove('is-confirming');
          scrollToForm(form);
        });
      }
    });

    // 送信完了／検証エラー等では入力画面に戻す（エラー表示を見えるように）
    ['wpcf7mailsent', 'wpcf7invalid', 'wpcf7spam', 'wpcf7mailfailed'].forEach(function (ev) {
      document.addEventListener(ev, function () {
        document.querySelectorAll('form.wpcf7-form.is-confirming').forEach(function (f) {
          f.classList.remove('is-confirming');
        });
      });
    });
  })();

  /* ---------- 7) ヘッダー ハンバーガー（スマホ） ---------- */
  (function () {
    var toggle = document.querySelector('.header-toggle');
    var nav = document.getElementById('header-nav');
    if (!toggle || !nav) return;
    function close() {
      nav.classList.remove('is-open');
      toggle.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'メニューを開く');
    }
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = !nav.classList.contains('is-open');
      nav.classList.toggle('is-open', open);
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'メニューを閉じる' : 'メニューを開く');
    });
    document.addEventListener('click', function (e) {
      if (nav.classList.contains('is-open') && !nav.contains(e.target) && !toggle.contains(e.target)) close();
    });
    document.addEventListener('keydown', function (e) {
      if ((e.key === 'Escape' || e.key === 'Esc') && nav.classList.contains('is-open')) close();
    });
  })();
})();
