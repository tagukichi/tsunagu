/**
 * つなぐ依頼ページ 運用マニュアル（クライアント納品用）
 * 実行: FONT=Meiryo node build.js out.pptx
 */
const pptxgen = require('pptxgenjs');

const FONT = process.env.FONT || 'Meiryo';
const OUT = process.argv[2] || 'tsunagu-manual.pptx';

// ---- サイトのブランドカラーに合わせたパレット ----
const NAVY = '11295A';
const BRAND = '1F6FE0';
const BRAND_DK = '0D3FA6';
const LIGHT = 'EAF3FF';
const SOFT = 'F3F7FC';
const GOLD = 'C98A16';
const GOLD_BG = 'FDF3DC';
const INK = '233244';
const MUTED = '5D6B7E';
const LINE = 'E4EAF2';
const W = 13.33, H = 7.5;

const pres = new pptxgen();
pres.layout = 'LAYOUT_WIDE';
pres.author = 'つなぐ';
pres.title = 'つなぐ依頼ページ 運用マニュアル';

/* ============ ヘルパー ============ */

// 見出し（コンテンツスライド共通）
function heading(slide, no, title, sub) {
  const tx = no === '' || no === null || no === undefined ? 0.62 : 1.24;
  if (tx > 0.7) {
    slide.addShape(pres.ShapeType.ellipse, { x: 0.6, y: 0.52, w: 0.5, h: 0.5, fill: { color: BRAND } });
    slide.addText(String(no), { x: 0.6, y: 0.52, w: 0.5, h: 0.5, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 15, bold: true, color: 'FFFFFF', margin: 0 });
  }
  slide.addText(title, { x: tx, y: 0.48, w: 11.4, h: 0.58, fontFace: FONT, fontSize: 27, bold: true, color: NAVY, margin: 0, valign: 'middle' });
  if (sub) slide.addText(sub, { x: tx, y: 1.06, w: 11.4, h: 0.34, fontFace: FONT, fontSize: 13, color: MUTED, margin: 0, valign: 'middle' });
}

// 画像プレースホルダー（差し替え用）
function imgBox(slide, x, y, w, h, label) {
  slide.addShape(pres.ShapeType.roundRect, { x, y, w, h, rectRadius: 0.08, fill: { color: 'FFFFFF' }, line: { color: '9DB8DF', width: 1.5, dashType: 'dash' } });
  const cx = x + w / 2;
  const iy = y + h / 2 - 0.52;
  // 画像アイコン（枠・太陽・山）
  slide.addShape(pres.ShapeType.roundRect, { x: cx - 0.42, y: iy, w: 0.84, h: 0.62, rectRadius: 0.05, fill: { color: LIGHT }, line: { color: '9DB8DF', width: 1 } });
  slide.addShape(pres.ShapeType.ellipse, { x: cx - 0.26, y: iy + 0.11, w: 0.16, h: 0.16, fill: { color: BRAND } });
  slide.addShape(pres.ShapeType.triangle, { x: cx - 0.12, y: iy + 0.22, w: 0.46, h: 0.3, fill: { color: '9DB8DF' } });
  slide.addText('画像を挿入', { x, y: y + h / 2 + 0.16, w, h: 0.26, align: 'center', fontFace: FONT, fontSize: 12, bold: true, color: BRAND, margin: 0 });
  slide.addText(label, { x: x + 0.2, y: y + h / 2 + 0.44, w: w - 0.4, h: 0.5, align: 'center', fontFace: FONT, fontSize: 10.5, color: MUTED, margin: 0 });
}

// 章扉
function sectionSlide(no, jp, en, items) {
  const s = pres.addSlide();
  s.background = { color: NAVY };
  s.addShape(pres.ShapeType.ellipse, { x: 1.0, y: 2.42, w: 1.15, h: 1.15, fill: { color: BRAND } });
  s.addText(String(no), { x: 1.0, y: 2.42, w: 1.15, h: 1.15, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 40, bold: true, color: 'FFFFFF', margin: 0 });
  s.addText(jp, { x: 2.45, y: 2.5, w: 7.6, h: 0.72, fontFace: FONT, fontSize: 34, bold: true, color: 'FFFFFF', margin: 0, valign: 'middle' });
  s.addText(en, { x: 2.45, y: 3.24, w: 7.6, h: 0.36, fontFace: FONT, fontSize: 12.5, color: '9FC0F0', charSpacing: 2, margin: 0, valign: 'middle' });
  if (items) {
    items.forEach((t, i) => {
      s.addShape(pres.ShapeType.ellipse, { x: 10.35, y: 2.5 + i * 0.62, w: 0.2, h: 0.2, fill: { color: BRAND } });
      s.addText(t, { x: 10.7, y: 2.42 + i * 0.62, w: 2.3, h: 0.36, fontFace: FONT, fontSize: 12.5, color: 'CADCFC', margin: 0, valign: 'middle' });
    });
  }
  return s;
}

// 手順ステップ（縦並び）
function steps(slide, x, y, w, list, gap) {
  const g = gap || 0.92;
  list.forEach((it, i) => {
    const yy = y + i * g;
    slide.addShape(pres.ShapeType.ellipse, { x, y: yy, w: 0.42, h: 0.42, fill: { color: LIGHT } });
    slide.addText(String(i + 1), { x, y: yy, w: 0.42, h: 0.42, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 13, bold: true, color: BRAND_DK, margin: 0 });
    slide.addText(it[0], { x: x + 0.58, y: yy - 0.03, w: w - 0.58, h: 0.3, fontFace: FONT, fontSize: 14.5, bold: true, color: NAVY, margin: 0, valign: 'middle' });
    slide.addText(it[1], { x: x + 0.58, y: yy + 0.28, w: w - 0.58, h: 0.5, fontFace: FONT, fontSize: 12, color: MUTED, margin: 0, lineSpacingMultiple: 1.15 });
  });
}

// 注意ボックス
function noteBox(slide, x, y, w, h, title, body) {
  slide.addShape(pres.ShapeType.roundRect, { x, y, w, h, rectRadius: 0.06, fill: { color: GOLD_BG }, line: { color: 'EBD3A0', width: 1 } });
  slide.addShape(pres.ShapeType.ellipse, { x: x + 0.24, y: y + 0.24, w: 0.34, h: 0.34, fill: { color: GOLD } });
  slide.addText('!', { x: x + 0.24, y: y + 0.24, w: 0.34, h: 0.34, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 15, bold: true, color: 'FFFFFF', margin: 0 });
  slide.addText(title, { x: x + 0.7, y: y + 0.2, w: w - 0.95, h: 0.34, fontFace: FONT, fontSize: 14, bold: true, color: '7A5410', margin: 0, valign: 'middle' });
  slide.addText(body, { x: x + 0.7, y: y + 0.56, w: w - 0.95, h: h - 0.72, fontFace: FONT, fontSize: 12, color: '6B5316', margin: 0, lineSpacingMultiple: 1.25 });
}

// 表（ラベル/内容）
function table(slide, x, y, w, rows, labelW) {
  const lw = labelW || 3.1;
  const rh = 0.52;
  rows.forEach((r, i) => {
    const yy = y + i * rh;
    if (i % 2 === 0) slide.addShape(pres.ShapeType.rect, { x, y: yy, w, h: rh, fill: { color: SOFT }, line: { color: 'FFFFFF', width: 0 } });
    slide.addText(r[0], { x: x + 0.18, y: yy, w: lw, h: rh, fontFace: FONT, fontSize: 12, bold: true, color: NAVY, margin: 0, valign: 'middle' });
    slide.addText(r[1], { x: x + lw + 0.24, y: yy, w: w - lw - 0.42, h: rh, fontFace: FONT, fontSize: 11.5, color: MUTED, margin: 0, valign: 'middle' });
  });
}

/* ============ 1. 表紙 ============ */
{
  const s = pres.addSlide();
  s.background = { color: NAVY };
  // 装飾（右下の同心円）
  s.addShape(pres.ShapeType.ellipse, { x: 9.6, y: 3.5, w: 5.2, h: 5.2, fill: { color: '17356F' } });
  s.addShape(pres.ShapeType.ellipse, { x: 10.7, y: 4.6, w: 3.0, h: 3.0, fill: { color: '1D4287' } });

  s.addShape(pres.ShapeType.roundRect, { x: 1.0, y: 1.75, w: 2.75, h: 0.42, rectRadius: 0.2, fill: { color: BRAND } });
  s.addText('会員限定ポータルサイト', { x: 1.0, y: 1.75, w: 2.75, h: 0.42, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 11.5, bold: true, color: 'FFFFFF', margin: 0 });

  s.addText('つなぐ依頼ページ', { x: 1.0, y: 2.42, w: 9.0, h: 0.96, fontFace: FONT, fontSize: 46, bold: true, color: 'FFFFFF', margin: 0, valign: 'middle' });
  s.addText('運用マニュアル', { x: 1.0, y: 3.36, w: 9.0, h: 0.8, fontFace: FONT, fontSize: 34, bold: true, color: 'CADCFC', margin: 0, valign: 'middle' });
  s.addText('WordPress 管理者向け  ｜  ログイン・コンテンツ修正・フォーム・ユーザー登録', { x: 1.0, y: 4.35, w: 9.2, h: 0.4, fontFace: FONT, fontSize: 13, color: '9FC0F0', margin: 0, valign: 'middle' });

  s.addShape(pres.ShapeType.rect, { x: 1.0, y: 5.42, w: 0.5, h: 0.03, fill: { color: BRAND } });
  s.addText('発行日：____年__月__日 ／ 版数：1.0', { x: 1.0, y: 5.62, w: 6.0, h: 0.34, fontFace: FONT, fontSize: 11.5, color: '7F9BC9', margin: 0, valign: 'middle' });
  s.addNotes('納品用マニュアル。スクリーンショットは各スライドの点線枠に差し替えてください。');
}

/* ============ 2. 目次 ============ */
{
  const s = pres.addSlide();
  heading(s, '', 'このマニュアルの内容', '管理画面での基本操作をまとめています');
  // 見出し番号なしのため上書き調整
  const items = [
    ['1', 'WordPressへのログイン', '管理画面に入る方法と、2種類のログインの違い', 'ログイン手順 ／ 管理画面の見方'],
    ['2', 'コンテンツの修正', '固定ページのカスタムフィールドで文言・リンクを変更', '基本の流れ ／ 修正例3種'],
    ['3', 'フォームについて', '入力〜送信の流れ、宛先メールや文言の変更場所', '文言・宛先の変更 ／ 紐付け'],
    ['4', '新規ユーザーの登録', '会員アカウントの作り方（会社名の入力は必須）', '登録手順 ／ 運用の注意点'],
  ];
  items.forEach((it, i) => {
    const y = 1.72 + i * 1.22;
    s.addShape(pres.ShapeType.roundRect, { x: 0.6, y, w: 12.13, h: 1.02, rectRadius: 0.06, fill: { color: SOFT }, line: { color: LINE, width: 1 } });
    s.addShape(pres.ShapeType.ellipse, { x: 0.95, y: y + 0.22, w: 0.58, h: 0.58, fill: { color: BRAND } });
    s.addText(it[0], { x: 0.95, y: y + 0.22, w: 0.58, h: 0.58, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 19, bold: true, color: 'FFFFFF', margin: 0 });
    s.addText(it[1], { x: 1.78, y: y + 0.16, w: 5.2, h: 0.36, fontFace: FONT, fontSize: 17, bold: true, color: NAVY, margin: 0, valign: 'middle' });
    s.addText(it[2], { x: 1.78, y: y + 0.53, w: 6.4, h: 0.32, fontFace: FONT, fontSize: 12, color: MUTED, margin: 0, valign: 'middle' });
    // 右側に章の内容を表示して余白を活かす
    s.addShape(pres.ShapeType.roundRect, { x: 8.42, y: y + 0.29, w: 3.95, h: 0.44, rectRadius: 0.22, fill: { color: 'FFFFFF' }, line: { color: LINE, width: 1 } });
    s.addText(it[3], { x: 8.42, y: y + 0.29, w: 3.95, h: 0.44, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 11, color: BRAND_DK, bold: true, margin: 0 });
  });
}

/* ============ 3. サイトの全体像 ============ */
{
  const s = pres.addSlide();
  heading(s, '', 'サイトの全体像', 'ログインした会員だけが閲覧できる構成です');
  const boxes = [
    ['ログイン画面', '会員がID・パスワードを\n入力する画面', LIGHT, NAVY],
    ['TOPページ', '依頼メニュー6つと\nおすすめツールを掲載', BRAND, 'FFFFFF'],
    ['フォームページ', '依頼内容を入力して\n送信する画面', LIGHT, NAVY],
  ];
  // 本文カラム（0.85〜12.48）に3枠を収める：幅3.5 × 3 + 間隔0.565 × 2
  boxes.forEach((b, i) => {
    const x = 0.85 + i * 4.065;
    s.addShape(pres.ShapeType.roundRect, { x, y: 1.95, w: 3.5, h: 1.85, rectRadius: 0.08, fill: { color: b[2] }, line: { color: i === 1 ? BRAND : LINE, width: 1 } });
    s.addText(b[0], { x, y: 2.2, w: 3.5, h: 0.42, align: 'center', fontFace: FONT, fontSize: 17, bold: true, color: b[3], margin: 0, valign: 'middle' });
    s.addText(b[1], { x: x + 0.2, y: 2.7, w: 3.1, h: 0.85, align: 'center', fontFace: FONT, fontSize: 12, color: i === 1 ? 'DCEAFF' : MUTED, margin: 0, lineSpacingMultiple: 1.2 });
    if (i < 2) {
      s.addText('▶', { x: x + 3.5, y: 2.62, w: 0.565, h: 0.5, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 16, color: BRAND, margin: 0 });
    }
  });
  noteBox(s, 0.85, 4.15, 11.63, 1.32, 'ログインしていない人はサイトを見られません',
    'ログインせずにページを開こうとすると、自動的にログイン画面に移動します。会員登録（ユーザーの追加）は管理者が行います（→ 4章）。');
  s.addText('※ 管理画面（WordPress）は、この会員用ログインとは別の入口から入ります（→ 1章）。', { x: 0.85, y: 5.66, w: 11.63, h: 0.34, fontFace: FONT, fontSize: 11.5, color: MUTED, margin: 0 });
}

/* ============ 章1 ============ */
sectionSlide(1, 'WordPressへのログイン', 'LOGIN', ['ログイン手順', '管理画面の見方', '2つのログインの違い']);

/* 1-1 ログイン手順 */
{
  const s = pres.addSlide();
  heading(s, 1, 'WordPressにログインする', '管理画面から、サイトの内容を編集します');
  steps(s, 0.75, 1.75, 5.5, [
    ['ログインページを開く', 'アドレス欄に「サイトのURL + /wp-admin」と入力します。\n例：https://（サイトURL）/wp-admin'],
    ['ユーザー名とパスワードを入力', '管理者用のユーザー名（またはメールアドレス）と\nパスワードを入力します。'],
    ['「ログイン」をクリック', 'ダッシュボード（管理画面のトップ）が表示されれば完了です。'],
  ], 1.15);
  imgBox(s, 6.85, 1.75, 5.75, 3.5, 'WordPressのログイン画面のスクリーンショット');
  noteBox(s, 0.75, 5.42, 11.85, 1.05, 'パスワードを忘れた場合',
    'ログイン画面の「パスワードをお忘れですか？」から、登録メールアドレス宛に再設定用のメールを送れます。');
}

/* 1-2 管理画面の見方 */
{
  const s = pres.addSlide();
  heading(s, 1, '管理画面の見方', 'よく使うメニューは左側にまとまっています');
  imgBox(s, 0.75, 1.75, 6.6, 4.35, '管理画面（ダッシュボード）のスクリーンショット\n※左メニューが写るように撮影してください');
  s.addText('よく使うメニュー', { x: 7.7, y: 1.75, w: 4.9, h: 0.36, fontFace: FONT, fontSize: 15, bold: true, color: NAVY, margin: 0, valign: 'middle' });
  table(s, 7.7, 2.2, 4.92, [
    ['固定ページ', '文言・リンクの修正'],
    ['お問い合わせ', 'フォームの設定'],
    ['ユーザー', '会員の追加・削除'],
    ['メディア', '画像・PDFの登録'],
    ['外観', 'テーマの管理'],
  ], 1.75);
  noteBox(s, 7.7, 5.0, 4.92, 1.1, '編集後は必ず保存',
    '各画面の「更新」「保存」ボタンを押すまで、変更は反映されません。');
}

/* 1-3 2つのログインの違い */
{
  const s = pres.addSlide();
  heading(s, 1, '【重要】2種類のログインがあります', '入口と目的が違うので、混同しないようご注意ください');
  // 本マニュアルの対象である「管理画面ログイン」（右）を強調
  const cards = [
    ['会員用ログイン', 'サイトを閲覧するための入口', ['URL：サイトのトップ（/login など）', '対象：つなぐ登録会社の皆さま', 'できること：依頼ページの閲覧・依頼'], SOFT, NAVY, MUTED],
    ['管理画面ログイン', 'サイトを編集するための入口', ['URL：サイトのURL + /wp-admin', '対象：管理者（貴社ご担当者）', 'できること：文言修正・ユーザー追加'], LIGHT, NAVY, BRAND],
  ];
  cards.forEach((c, i) => {
    const x = 0.75 + i * 6.15;
    s.addShape(pres.ShapeType.roundRect, { x, y: 1.8, w: 5.7, h: 3.3, rectRadius: 0.08, fill: { color: c[3] }, line: { color: i === 1 ? BRAND : LINE, width: 1.5 } });
    s.addShape(pres.ShapeType.roundRect, { x: x + 0.32, y: 2.08, w: 2.35, h: 0.36, rectRadius: 0.18, fill: { color: c[5] } });
    s.addText(i === 0 ? '会員の方' : '管理者の方（本書の対象）', { x: x + 0.32, y: 2.08, w: 2.35, h: 0.36, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 10, bold: true, color: 'FFFFFF', margin: 0 });
    s.addText(c[0], { x: x + 0.32, y: 2.56, w: 5.06, h: 0.44, fontFace: FONT, fontSize: 20, bold: true, color: c[4], margin: 0, valign: 'middle' });
    s.addText(c[1], { x: x + 0.32, y: 3.0, w: 5.06, h: 0.32, fontFace: FONT, fontSize: 12, color: MUTED, margin: 0, valign: 'middle' });
    c[2].forEach((t, j) => {
      s.addText('・' + t, { x: x + 0.32, y: 3.45 + j * 0.44, w: 5.06, h: 0.4, fontFace: FONT, fontSize: 12, color: INK, margin: 0, valign: 'middle' });
    });
  });
  noteBox(s, 0.75, 5.35, 11.85, 1.05, 'このマニュアルで説明するのは「管理画面ログイン」です',
    '会員の方がログインできない場合は、管理画面の「ユーザー」からアカウントの有無・メールアドレスをご確認ください。');
}

/* ============ 章2 ============ */
sectionSlide(2, 'コンテンツの修正', 'EDIT CONTENT', ['基本の流れ', '編集できる項目', 'よくある修正例']);

/* 2-1 基本の流れ */
{
  const s = pres.addSlide();
  heading(s, 2, 'コンテンツ修正の基本の流れ', 'ページの文言やリンクは「カスタムフィールド」で変更します');
  const flow = [
    ['固定ページを開く', '左メニュー →\n「固定ページ」'],
    ['ページを選ぶ', 'TOPページ（依頼\nページ）を編集'],
    ['入力欄を修正', 'カスタムフィールドを\n編集します'],
    ['「更新」を押す', 'サイトに反映\nされます'],
  ];
  flow.forEach((f, i) => {
    const x = 0.75 + i * 3.1;
    s.addShape(pres.ShapeType.roundRect, { x, y: 1.8, w: 2.7, h: 2.0, rectRadius: 0.08, fill: { color: i === 3 ? BRAND : 'FFFFFF' }, line: { color: i === 3 ? BRAND : LINE, width: 1.5 } });
    s.addShape(pres.ShapeType.ellipse, { x: x + 0.28, y: 2.05, w: 0.46, h: 0.46, fill: { color: i === 3 ? 'FFFFFF' : LIGHT } });
    s.addText(String(i + 1), { x: x + 0.28, y: 2.05, w: 0.46, h: 0.46, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 14, bold: true, color: i === 3 ? BRAND : BRAND_DK, margin: 0 });
    s.addText(f[0], { x: x + 0.28, y: 2.6, w: 2.15, h: 0.62, fontFace: FONT, fontSize: 14, bold: true, color: i === 3 ? 'FFFFFF' : NAVY, margin: 0, lineSpacingMultiple: 1.1 });
    s.addText(f[1], { x: x + 0.28, y: 3.2, w: 2.15, h: 0.5, fontFace: FONT, fontSize: 11, color: i === 3 ? 'DCEAFF' : MUTED, margin: 0, lineSpacingMultiple: 1.15 });
    if (i < 3) s.addText('▶', { x: x + 2.72, y: 2.6, w: 0.38, h: 0.4, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 14, color: BRAND, margin: 0 });
  });
  imgBox(s, 0.75, 4.05, 11.85, 2.35, '固定ページ一覧、または編集画面（カスタムフィールドが写っているもの）のスクリーンショット');
}

/* 2-2 カスタムフィールドの構成 */
{
  const s = pres.addSlide();
  heading(s, 2, 'どこで何が編集できるか', '編集画面のタブごとに項目が分かれています');
  imgBox(s, 0.75, 1.78, 5.5, 4.3, 'カスタムフィールドのタブ表示部分のスクリーンショット');
  s.addText('タブと編集できる内容', { x: 6.6, y: 1.78, w: 6.0, h: 0.34, fontFace: FONT, fontSize: 15, bold: true, color: NAVY, margin: 0, valign: 'middle' });
  table(s, 6.6, 2.22, 6.03, [
    ['ヘッダー / 共通', 'ロゴ・上部ボタン・著作権表記'],
    ['ヒーロー', 'タイトル・問い合わせメール'],
    ['おすすめツール（調速）', 'ロゴ・説明文・ボタンのリンク'],
    ['カード1〜6', 'カードの文言・依頼先フォーム'],
    ['TOPIC', '流れるお知らせ（1行＝1件）'],
  ], 2.55);
  noteBox(s, 6.6, 4.95, 6.03, 1.15, '入力欄を空にすると',
    'あらかじめ設定された初期の文言が表示されます。消しても崩れません。');
}

/* 2-3 修正例① */
{
  const s = pres.addSlide();
  heading(s, 2, '修正例①　問い合わせ先・上部ボタン', 'よく変更する項目です');
  imgBox(s, 0.75, 1.78, 6.1, 4.3, '「ヒーロー」タブ、または「ヘッダー / 共通」タブの入力欄のスクリーンショット');
  const rows = [
    ['問い合わせ先メール', '「ヒーロー」タブ →\n問い合わせ先 メールアドレス'],
    ['買付証明書・売却合意書', '「ヘッダー / 共通」タブ →\nExcelファイルを登録'],
    ['利用規約・ダウンロード', '「ヘッダー / 共通」タブ →\nリンクURLを入力'],
  ];
  rows.forEach((r, i) => {
    const y = 1.95 + i * 1.4;
    s.addShape(pres.ShapeType.roundRect, { x: 7.2, y, w: 5.42, h: 1.2, rectRadius: 0.06, fill: { color: SOFT }, line: { color: LINE, width: 1 } });
    s.addText(r[0], { x: 7.48, y: y + 0.16, w: 4.9, h: 0.34, fontFace: FONT, fontSize: 14, bold: true, color: NAVY, margin: 0, valign: 'middle' });
    s.addText(r[1], { x: 7.48, y: y + 0.52, w: 4.9, h: 0.58, fontFace: FONT, fontSize: 11.5, color: MUTED, margin: 0, lineSpacingMultiple: 1.15 });
  });
  s.addText('※ ファイルは入力欄から直接アップロードできます。', { x: 7.2, y: 6.16, w: 5.42, h: 0.34, fontFace: FONT, fontSize: 10.5, color: MUTED, margin: 0, valign: 'middle' });
}

/* 2-4 修正例② カード */
{
  const s = pres.addSlide();
  heading(s, 2, '修正例②　6つのカード', 'ボタンの名前・リンク先・説明の内容を変更できます');
  imgBox(s, 0.75, 1.78, 6.1, 4.3, '「カード1」タブの入力欄のスクリーンショット');
  table(s, 7.2, 1.85, 5.42, [
    ['タグ／タイトル', 'カード上部の見出し'],
    ['説明文', 'タイトル下の説明'],
    ['左ボタン ラベル', '「依頼」などの文字'],
    ['フォーム1〜4', '押した時に開くページ'],
    ['右ボタン ラベル', '「詳細」などの文字'],
    ['ポップアップ本文', '「詳細」で出る説明'],
  ], 2.3);
  noteBox(s, 7.2, 5.0, 5.42, 1.25, '左ボタンの動き',
    'フォームを1つだけ設定＝直接開く／\n2つ以上＝選択リストが出ます。');
}

/* 2-5 修正例③ TOPIC */
{
  const s = pres.addSlide();
  heading(s, 2, '修正例③　TOPIC（流れるお知らせ）', 'カードの上を横に流れるテキストです');
  imgBox(s, 0.75, 1.78, 6.1, 3.15, 'サイト上のTOPIC表示部分のスクリーンショット');
  s.addText('入力のしかた', { x: 7.2, y: 1.78, w: 5.42, h: 0.34, fontFace: FONT, fontSize: 15, bold: true, color: NAVY, margin: 0, valign: 'middle' });
  s.addText('「TOPIC」タブの入力欄に、1行につき1件を入力します。', { x: 7.2, y: 2.18, w: 5.42, h: 0.34, fontFace: FONT, fontSize: 12, color: MUTED, margin: 0, valign: 'middle' });
  s.addShape(pres.ShapeType.roundRect, { x: 7.2, y: 2.62, w: 5.42, h: 1.18, rectRadius: 0.06, fill: { color: '1B2A44' }, line: { color: '1B2A44', width: 1 } });
  s.addText('1棟案件アパート求む\nボロ戸建て投資家に提案可能\n500万円以下の案件は是非！', { x: 7.44, y: 2.74, w: 5.0, h: 0.94, fontFace: FONT, fontSize: 12.5, color: 'CADCFC', margin: 0, lineSpacingMultiple: 1.32 });
  s.addText('↑ 入力例（3件）', { x: 7.2, y: 3.88, w: 5.42, h: 0.3, fontFace: FONT, fontSize: 10.5, color: MUTED, margin: 0, valign: 'middle' });
  noteBox(s, 0.75, 5.1, 11.87, 1.3, '件数は自由に増減できます',
    '行を増やせば表示件数が増え、すべて削除すると初期の内容が表示されます。長すぎる文章は読みにくくなるため、20文字程度を目安にしてください。');
}

/* ============ 章3 ============ */
sectionSlide(3, 'フォームについて', 'FORM', ['入力〜送信の流れ', '文言・宛先の変更', 'ボタンとの紐付け']);

/* 3-1 フォームの流れ */
{
  const s = pres.addSlide();
  heading(s, 3, 'フォームの流れ', '会員は「入力 → 確認 → 送信」の3ステップで依頼します');
  const flow = [['入力', '必要事項を記入し\n「確認する」を押す'], ['確認', '入力内容を確認し\n「送信する」を押す'], ['送信完了', 'お礼のメッセージが\n表示されます']];
  flow.forEach((f, i) => {
    const x = 0.9 + i * 4.15;
    s.addShape(pres.ShapeType.roundRect, { x, y: 1.85, w: 3.5, h: 1.75, rectRadius: 0.08, fill: { color: i === 2 ? BRAND : 'FFFFFF' }, line: { color: i === 2 ? BRAND : LINE, width: 1.5 } });
    s.addShape(pres.ShapeType.ellipse, { x: x + 1.5, y: 2.05, w: 0.5, h: 0.5, fill: { color: i === 2 ? 'FFFFFF' : LIGHT } });
    s.addText(String(i + 1), { x: x + 1.5, y: 2.05, w: 0.5, h: 0.5, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 15, bold: true, color: i === 2 ? BRAND : BRAND_DK, margin: 0 });
    s.addText(f[0], { x, y: 2.62, w: 3.5, h: 0.36, align: 'center', fontFace: FONT, fontSize: 17, bold: true, color: i === 2 ? 'FFFFFF' : NAVY, margin: 0, valign: 'middle' });
    s.addText(f[1], { x: x + 0.2, y: 2.98, w: 3.1, h: 0.55, align: 'center', fontFace: FONT, fontSize: 11.5, color: i === 2 ? 'DCEAFF' : MUTED, margin: 0, lineSpacingMultiple: 1.2 });
    if (i < 2) s.addText('▶', { x: x + 3.56, y: 2.5, w: 0.5, h: 0.4, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 15, color: BRAND, margin: 0 });
  });
  imgBox(s, 0.9, 3.95, 5.75, 2.45, 'フォームの入力画面のスクリーンショット');
  imgBox(s, 6.9, 3.95, 5.72, 2.45, '確認画面（入力内容の一覧）のスクリーンショット');
}

/* 3-2 文言・宛先の変更 */
{
  const s = pres.addSlide();
  heading(s, 3, 'フォームの文言・宛先を変える', '編集する場所は2か所です');
  const cards = [
    ['ページ側で変えるもの', '固定ページ（フォームのページ）', ['ページタイトル（画面の見出し）', 'フォーム上部の説明文', '送信完了メッセージ']],
    ['フォーム側で変えるもの', '左メニュー「お問い合わせ」', ['入力項目・項目名', '必須にするかどうか', '送信先メールアドレス・本文']],
  ];
  cards.forEach((c, i) => {
    const x = 0.8 + i * 6.1;
    s.addShape(pres.ShapeType.roundRect, { x, y: 1.8, w: 5.62, h: 2.75, rectRadius: 0.08, fill: { color: i === 0 ? SOFT : LIGHT }, line: { color: i === 0 ? LINE : BRAND, width: 1.5 } });
    s.addText(c[0], { x: x + 0.3, y: 2.02, w: 5.0, h: 0.4, fontFace: FONT, fontSize: 17, bold: true, color: NAVY, margin: 0, valign: 'middle' });
    s.addText('編集場所：' + c[1], { x: x + 0.3, y: 2.44, w: 5.0, h: 0.32, fontFace: FONT, fontSize: 11.5, color: BRAND_DK, bold: true, margin: 0, valign: 'middle' });
    c[2].forEach((t, j) => {
      s.addText('・' + t, { x: x + 0.3, y: 2.86 + j * 0.44, w: 5.0, h: 0.4, fontFace: FONT, fontSize: 12, color: INK, margin: 0, valign: 'middle' });
    });
  });
  imgBox(s, 0.8, 4.75, 11.72, 1.65, 'フォーム設定画面（「お問い合わせ」→ フォーム編集、またはメールタブ）のスクリーンショット');
}

/* 3-3 ボタンとの紐付け */
{
  const s = pres.addSlide();
  heading(s, 3, '「依頼」ボタンとフォームの紐付け', 'カードのボタンを押した時に開くページを指定します');
  steps(s, 0.8, 1.8, 5.7, [
    ['フォーム用のページを用意', '固定ページを新規作成し、テンプレートで\n「つなぐ フォームページ」を選びます。'],
    ['フォームを設定', 'そのページのカスタムフィールドに、\n使用するフォームを設定します。'],
    ['TOPページで紐付け', 'TOPページ →「カード◯」タブ →\n「フォーム◯ 遷移先ページ」で選択します。'],
  ], 1.2);
  imgBox(s, 6.95, 1.8, 5.65, 3.45, '「カード1」タブのフォーム設定部分のスクリーンショット');
  noteBox(s, 0.8, 5.42, 11.82, 1.05, 'フォームを増やしたいとき',
    '1つのカードにつき最大4つまで登録できます。2つ以上登録すると、ボタンを押した時に選択リストが表示されます。');
}

/* ============ 章4 ============ */
sectionSlide(4, '新規ユーザーの登録', 'ADD USER', ['登録の手順', '会社名の入力', '運用の注意点']);

/* 4-1 登録手順 */
{
  const s = pres.addSlide();
  heading(s, 4, '新しい会員を登録する', '会員アカウントは管理者が作成します');
  steps(s, 0.8, 1.75, 5.7, [
    ['「ユーザー」→「新規追加」', '左メニューの「ユーザー」から\n「新規追加」を開きます。'],
    ['ユーザー名・メールを入力', 'ユーザー名（ログインID）と\nメールアドレスを入力します。'],
    ['名前に会社名を入力', '「姓」または「名」の欄に会社名を入れます。\n（※次のページで詳しく説明します）'],
    ['権限グループを選ぶ', '会員は「購読者」を選択します。\n最後に「新規ユーザーを追加」を押します。'],
  ], 1.12);
  imgBox(s, 6.95, 1.75, 5.65, 4.0, '「新規ユーザーを追加」画面のスクリーンショット');
  s.addText('※ パスワードは自動生成されます。「ユーザーに通知を送信」にチェックを入れると、本人にメールで通知されます。', { x: 0.8, y: 6.28, w: 11.8, h: 0.3, fontFace: FONT, fontSize: 10.5, color: MUTED, margin: 0 });
}

/* 4-2 会社名（最重要） */
{
  const s = pres.addSlide();
  s.background = { color: SOFT };
  s.addShape(pres.ShapeType.roundRect, { x: 0.75, y: 0.5, w: 11.83, h: 0.75, rectRadius: 0.08, fill: { color: GOLD } });
  s.addText('【必ずお守りください】ユーザーの「名前」に会社名を入れてください', { x: 1.05, y: 0.5, w: 11.3, h: 0.75, fontFace: FONT, fontSize: 21, bold: true, color: 'FFFFFF', margin: 0, valign: 'middle' });
  s.addText('誰からの依頼か判別できなくなるため、会社名の入力は必須です。', { x: 0.78, y: 1.42, w: 11.8, h: 0.36, fontFace: FONT, fontSize: 13.5, color: INK, margin: 0, valign: 'middle' });

  // 良い例 / 悪い例
  const ok = ['姓：株式会社つなぐ不動産', '名：山田 太郎', '→ 一覧で会社名がすぐ分かる'];
  const ng = ['姓：山田', '名：太郎', '→ どの会社の方か分からない'];
  [['◯ 良い例', ok, '1BA24D', 'E6F6EA'], ['× 避けたい例', ng, 'C0392B', 'FDECEC']].forEach((c, i) => {
    const x = 0.78 + i * 6.12;
    s.addShape(pres.ShapeType.roundRect, { x, y: 1.95, w: 5.6, h: 2.5, rectRadius: 0.08, fill: { color: 'FFFFFF' }, line: { color: c[2], width: 1.5 } });
    s.addShape(pres.ShapeType.roundRect, { x: x + 0.3, y: 2.2, w: 1.65, h: 0.4, rectRadius: 0.2, fill: { color: c[3] } });
    s.addText(c[0], { x: x + 0.3, y: 2.2, w: 1.65, h: 0.4, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 12.5, bold: true, color: c[2], margin: 0 });
    c[1].forEach((t, j) => {
      s.addText(t, { x: x + 0.3, y: 2.76 + j * 0.5, w: 5.0, h: 0.44, fontFace: FONT, fontSize: j === 2 ? 12 : 14, bold: j !== 2, color: j === 2 ? MUTED : NAVY, margin: 0, valign: 'middle' });
    });
  });
  noteBox(s, 0.78, 4.65, 11.82, 1.6, 'なぜ必要？',
    '依頼フォームの送信や会員一覧の確認時に、担当者名だけでは「どの登録会社からの依頼か」が判断できません。\n姓の欄に「会社名」、名の欄に「担当者名」を入れる運用をおすすめします（例：姓＝株式会社つなぐ不動産／名＝山田 太郎）。');
}

/* 4-3 運用の注意点 */
{
  const s = pres.addSlide();
  heading(s, 4, 'ユーザー運用の注意点', '安全に運用するためのポイントです');
  const list = [
    ['権限は「購読者」で登録', '会員には編集権限を与えないでください。管理画面を操作できてしまいます。'],
    ['退会時はユーザーを削除', '「ユーザー」一覧から削除すると、その方はサイトを閲覧できなくなります。'],
    ['自由登録は行わない', '会員登録は管理者のみが行う設定です。設定は変更しないでください。'],
    ['パスワードの再発行', 'ログインできない場合は、ユーザー編集画面から新しいパスワードを設定できます。'],
  ];
  list.forEach((it, i) => {
    const y = 1.8 + i * 1.12;
    s.addShape(pres.ShapeType.roundRect, { x: 0.8, y, w: 7.4, h: 0.95, rectRadius: 0.06, fill: { color: SOFT }, line: { color: LINE, width: 1 } });
    s.addShape(pres.ShapeType.ellipse, { x: 1.05, y: y + 0.26, w: 0.42, h: 0.42, fill: { color: BRAND } });
    s.addText('✓', { x: 1.05, y: y + 0.26, w: 0.42, h: 0.42, align: 'center', valign: 'middle', fontFace: FONT, fontSize: 13, bold: true, color: 'FFFFFF', margin: 0 });
    s.addText(it[0], { x: 1.62, y: y + 0.12, w: 6.4, h: 0.33, fontFace: FONT, fontSize: 14, bold: true, color: NAVY, margin: 0, valign: 'middle' });
    s.addText(it[1], { x: 1.62, y: y + 0.45, w: 6.4, h: 0.42, fontFace: FONT, fontSize: 11.5, color: MUTED, margin: 0, valign: 'middle' });
  });
  imgBox(s, 8.6, 1.8, 4.02, 4.27, 'ユーザー一覧画面のスクリーンショット');
}

/* ============ 最終ページ ============ */
{
  const s = pres.addSlide();
  s.background = { color: NAVY };
  // 装飾円は本文テキストに掛からない位置に配置
  s.addShape(pres.ShapeType.ellipse, { x: -2.0, y: -1.3, w: 4.2, h: 4.2, fill: { color: '17356F' } });
  s.addText('困ったときは', { x: 1.0, y: 1.5, w: 8.0, h: 0.7, fontFace: FONT, fontSize: 32, bold: true, color: 'FFFFFF', margin: 0, valign: 'middle' });
  const faq = [
    ['ログインできない', 'ログイン画面の「パスワードをお忘れですか？」から再設定できます。'],
    ['修正が反映されない', '「更新」ボタンを押したか、ブラウザの再読み込み（更新）をお試しください。'],
    ['フォームのメールが届かない', '迷惑メールフォルダをご確認のうえ、フォームの「メール」設定をご確認ください。'],
  ];
  faq.forEach((f, i) => {
    const y = 2.5 + i * 1.02;
    s.addShape(pres.ShapeType.roundRect, { x: 1.0, y, w: 11.33, h: 0.86, rectRadius: 0.06, fill: { color: '17356F' }, line: { color: '2A4C8C', width: 1 } });
    s.addText('Q', { x: 1.28, y: y + 0.1, w: 0.4, h: 0.32, fontFace: FONT, fontSize: 14, bold: true, color: '7FB0F0', margin: 0, valign: 'middle' });
    s.addText(f[0], { x: 1.7, y: y + 0.1, w: 10.3, h: 0.32, fontFace: FONT, fontSize: 14, bold: true, color: 'FFFFFF', margin: 0, valign: 'middle' });
    s.addText(f[1], { x: 1.7, y: y + 0.44, w: 10.3, h: 0.32, fontFace: FONT, fontSize: 11.5, color: 'A8C4EE', margin: 0, valign: 'middle' });
  });
  s.addText('その他ご不明な点は、サイト制作担当までお問い合わせください。', { x: 1.0, y: 5.9, w: 11.33, h: 0.36, fontFace: FONT, fontSize: 12.5, color: '9FC0F0', margin: 0, valign: 'middle' });
  s.addText('つなぐ依頼ページ 運用マニュアル ／ 版数 1.0', { x: 1.0, y: 6.32, w: 11.33, h: 0.32, fontFace: FONT, fontSize: 11, color: '9FC0F0', margin: 0, valign: 'middle' });
}

pres.writeFile({ fileName: OUT }).then(() => console.log('created:', OUT));
