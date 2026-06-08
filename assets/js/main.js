/**
 * つなぐ依頼ページ - フロントスクリプト
 * 現状は装飾的な挙動のみ。WordPress テーマ化の際もそのまま流用できます。
 */
(function () {
  'use strict';

  // スクロールで各カードをふわっと表示（prefers-reduced-motion を尊重）
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var targets = document.querySelectorAll('[data-reveal]');

  if (reduceMotion || !('IntersectionObserver' in window) || !targets.length) {
    targets.forEach(function (el) { el.classList.add('is-visible'); });
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  targets.forEach(function (el) { observer.observe(el); });
})();
