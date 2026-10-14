/* 管理画面「広告」：枠ごとにメディアライブラリから画像を選択／削除 */
(function () {
  'use strict';
  if (!window.wp || !wp.media) return;

  document.querySelectorAll('.tsg-ads-slot').forEach(function (slot) {
    var input = slot.querySelector('.tsg-ads-image-id');
    var preview = slot.querySelector('.tsg-ads-preview');
    var selectBtn = slot.querySelector('.tsg-ads-select');
    var removeBtn = slot.querySelector('.tsg-ads-remove');
    var status = slot.querySelector('.tsg-ads-status');
    var frame = null;

    function render(src) {
      preview.innerHTML = '';
      if (src) {
        var img = document.createElement('img');
        img.src = src;
        img.alt = '';
        preview.appendChild(img);
      } else {
        var empty = document.createElement('span');
        empty.textContent = '画像未設定';
        preview.appendChild(empty);
      }
      slot.classList.toggle('is-empty', !src);
      status.textContent = src ? '表示中' : '未設定';
      selectBtn.textContent = src ? '画像を変更' : '画像を選択';
      removeBtn.hidden = !src;
    }

    selectBtn.addEventListener('click', function () {
      if (!frame) {
        frame = wp.media({
          title: '広告画像を選択',
          button: { text: 'この画像を使う' },
          library: { type: 'image' },
          multiple: false
        });
        frame.on('select', function () {
          var a = frame.state().get('selection').first().toJSON();
          var size = a.sizes && (a.sizes.medium_large || a.sizes.large || a.sizes.medium);
          input.value = a.id;
          render(size ? size.url : a.url);
        });
      }
      frame.open();
    });

    removeBtn.addEventListener('click', function () {
      input.value = '';
      render('');
    });
  });
})();
