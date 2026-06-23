# つなぐ依頼ページ（TSUNAGU MEMBER PORTAL）

つなぐ会員向けの依頼・相談受付ページ。デザインカンプを静的サイト（HTML / CSS / JS）として実装したものです。
**将来的に WordPress + ACF テーマ化**することを前提に、文言・リンクをカスタムフィールドへ移しやすい構造で作成しています。

## ディレクトリ構成

```
tsunagu/
├── index.html                 … ページ本体（ACFフィールド名をコメントで明記）
├── login.html                 … 会員ログイン画面（ID/PASS・WP連携前提）
├── assets/
│   ├── css/style.css          … スタイル（色テーマは data-theme で切替）
│   ├── js/main.js             … 出現アニメ / PDFドロップダウン / モーダル / TOPICマーキー
│   ├── img/
│   │   ├── tsungau_back.png   … ヒーロー背景の街並み（支給画像 2104×747）
│   │   ├── cho-sokulogo_yoko.png … 調速 横ロゴ（支給画像 1774×887）
│   │   └── tsunaglogo.png     … ヘッダーロゴ（支給画像 500×500・透過）
│   └── pdf/
│       ├── print-pricing.pdf  … 印刷の料金表（支給PDF・印刷ポップアップで表示）
│       └── sample.pdf         … 依頼シート等のダミーPDF（★差し替え）
└── README.md
```

## プレビュー

ビルド不要です。`index.html` をブラウザで直接開くか、簡易サーバで確認してください。

```bash
# 例）Python の簡易サーバ
python3 -m http.server 8000
# → http://localhost:8000
```

## 差し替え前提（プレースホルダー）

支給予定のため、以下は**仮素材**です。本番アセットが届き次第差し替えてください。

| 種別 | 箇所 | 現状 |
|------|------|------|
| 画像 | ヒーロー背景の街並み | `assets/img/tsungau_back.png`（**支給画像で設定済み**。下端にフルワイド配置、上端はマスクで背景へフェード） |
| 画像 | 調速 横ロゴ | `assets/img/cho-sokulogo_yoko.png`（**支給画像で設定済み**。バナー左に白チップで配置） |
| 画像 | ヘッダーロゴ（つなぐ） | `assets/img/tsunaglogo.png`（**支給画像で設定済み**。正方形・透過） |
| PDF | 印刷の料金表 | `assets/pdf/print-pricing.pdf`（**支給PDFで設定済み**。「印刷を依頼する」のポップアップに `<object>` で埋め込み表示＋別タブリンク） |
| PDF | 依頼シート等のダミー | `assets/pdf/sample.pdf`（★仮。各カード左ボタンの選択肢が参照） |
| アイコン | ヘッダー / ヒーロー / 各カード / CTA | `index.html` 内のインライン SVG（`<!-- PLACEHOLDER ICON: ... -->`） |

インライン SVG は `currentColor` で塗っているため、差し替え時もカードのカラーテーマ（`--c`）がそのまま反映されます。

## デザイン仕様メモ

- 配色：ブランド青 `#1f6fe0`。カードは 6 色テーマ（blue / teal / green / purple / orange / cyan）を `data-theme` で付与。
- フォント：Noto Sans JP（Google Fonts）。
- レスポンシブ：PC（3カラム）→ タブレット 1024px 以下（2カラム）→ スマホ 680px 以下（1カラム）。
- サービスカードは**固定6枚**。

## 会員限定アクセス（ログイン）

WordPress でユーザー登録された会員だけが、ID・パスワードで閲覧できるようにする想定です。
`login.html` がログイン画面のデザインです（サイトと同じトーンの中央カード＋背景の街並み）。

- **フォームの仕様**：入力欄の `name` は WordPress 準拠（`log` / `pwd` / `rememberme`）。テーマ化時はフォームの `action` を `wp_login_url()` に、必要に応じて hidden の `redirect_to` を出力すれば、`wp-login.php` がそのまま認証を処理します。
- **パスワード表示切替**：目アイコンで表示/非表示を切り替え（`assets/js/main.js` の 5) ブロック）。
- **新規登録リンクは無し**：会員登録は管理者（事務局）が WP 管理画面で行う想定。公開登録は無効化を推奨（`設定 > 一般 >「だれでも登録できるようにする」をオフ`）。

### テーマ化時の実装メモ（functions.php）

ログインしていない訪問者を、すべてログインページへリダイレクトします。

```php
// 未ログインは全ページをログインページ（固定ページ slug = login）へ誘導
add_action('template_redirect', function () {
    if (is_user_logged_in()) return;

    // ログインページ自身・wp-login・管理画面・REST/AJAX は除外
    if (is_page('login')) return;
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    foreach (['wp-login.php', '/wp-admin', '/wp-json', 'admin-ajax.php'] as $allow) {
        if (strpos($uri, $allow) !== false) return;
    }

    wp_safe_redirect( home_url('/login/?redirect_to=' . rawurlencode(home_url($uri))) );
    exit;
});
```

- ログイン画面は固定ページ（slug `login`）＋専用テンプレート `page-login.php` に `login.html` のマークアップを移植。
- フォーム例：`<form method="post" action="<?php echo esc_url( wp_login_url() ); ?>">` ＋ `name="log"/"pwd"/"rememberme"` ＋ `<input type="hidden" name="redirect_to" value="<?php echo esc_url( home_url('/') ); ?>">`。
- エラー時は `login.html` の `.login__error`（既定 `hidden`）を表示。

## WordPress / ACF 移行ガイド

固定ページのカスタムフィールドで編集する想定です。`index.html` の各セクション冒頭に `[ACF] フィールド名` をコメントで記載しています。下記が対応表です。

### 共通・ヘッダー / フッター
| 項目 | フィールド名 | 種別 |
|------|------------|------|
| ロゴ（ヘッダー / フッター共通） | `site_logo` | 画像 |
| ヘッダーボタン ラベル / URL | `header_btn_label` / `header_btn_url`（利用規約・ダウンロード） | テキスト / URL |
| コピーライト | `footer_copyright` | テキスト |

> ヘッダーとフッターのロゴは同じ `site_logo`（`tsunaglogo.png`）を使用。ヘッダー右は「利用規約・ダウンロード」の1ボタンに統合しています。

### ヒーロー
| 項目 | フィールド名 |
|------|------------|
| バッジ文言 | `hero_badge` |
| タイトル | `hero_title` |
| リード文 | `hero_lead`（テキストエリア） |

> 「ご利用について」枠は廃止。リード文1行（つなぐ登録会社の皆様からの〜）のみの構成です。

### おすすめツール（調速）
| 項目 | フィールド名 |
|------|------------|
| リボン文言 | `featured_ribbon` |
| ロゴ画像（調速 横ロゴ） | `featured_logo`（画像） |
| 製品名 | `featured_product` |
| ピル文言 | `featured_pill` |
| 説明文 | `featured_text` |
| ボタン ラベル / URL | `featured_btn_label` / `featured_btn_url` |

> 調速ロゴはワードマーク（調速／チョーソク）＋アイコン＋「不動産調査アプリ」が一体の横ロゴ画像です。暗色バナー上で視認できるよう、白の角丸チップに乗せて表示しています。

### サービスカード（固定6枚 / n = 1〜6）
各カードで以下を用意します。

| 項目 | フィールド名 | 種別 |
|------|------------|------|
| タグ | `card_{n}_tag` | テキスト |
| タイトル | `card_{n}_title` | テキスト |
| 説明文 | `card_{n}_desc` | テキストエリア |
| **左ボタン** ラベル | `card_{n}_btn1_label` | テキスト |
| 左ボタン PDF（最大4件） | `card_{n}_pdf1〜4_label`（テキスト） / `card_{n}_pdf1〜4_file`（**ファイル＝PDF**） | 固定スロット（ACF無料版対応） |
| **右ボタン** ラベル | `card_{n}_btn2_label` | テキスト |
| 右ボタン ポップアップ 見出し | `card_{n}_popup_title` | テキスト |
| 右ボタン ポップアップ 本文 | `card_{n}_popup_text` | WYSIWYG / テキストエリア |

#### ボタンの挙動

- **左ボタン（PDF）**：`card_{n}_pdfs` の登録件数で動きが変わります。
  - **1件** … クリックでその PDF を直接開く（別タブ）。
  - **2件以上** … クリックでリスト（ドロップダウン）を表示し、項目クリックで各 PDF を開く。
  - 出力時は `pdf_file` の URL を各リンクの `href` に、`pdf_label` をリンク文言に流し込みます。
- **右ボタン（ポップアップ）**：クリックで共通モーダルを開き、`card_{n}_popup_title` を見出し、`card_{n}_popup_text` を本文として表示します（**ページ遷移なし**）。
  - 本文は **WYSIWYG** 想定で、**見出し・リンク・画像・リスト**などを自由に挿入できます（モーダル側で `h2〜h4 / a / img / ul / ol / hr` を整形済み）。
  - 本文が長い場合はモーダル内でスクロール表示されます（最大幅 680px・最大高さ 88vh）。
  - **PDF / 画像のポップアップも可**：カード4「印刷を依頼する」は料金表PDF `assets/pdf/print-pricing.pdf` を `<object>` で埋め込み表示する例です（テーマ化時は ACF ファイルフィールド `card_4_popup_pdf` のURLを `data` / リンクの `href` に流し込み）。

> カラーテーマ・アイコンはテーマ側で固定（テンプレートに直書き）。文言・リンク（PDF）・ポップアップ本文を ACF 化する想定です。
> ダミー実装では左ボタンの選択肢PDFは `assets/pdf/sample.pdf`、ポップアップ本文は各カード内の `#popup-c{n}`、カード4は料金表PDF `assets/pdf/print-pricing.pdf` を参照しています。

### TOPIC ＋ 相談CTA
| 項目 | フィールド名 | 種別 |
|------|------------|------|
| TOPIC（お知らせ） | `topics_text`（テキストエリア・**1行＝1件**） | テキストエリア（ACF無料版対応） |
| タイトル | `help_title` | テキスト |
| 説明文 | `help_desc` | テキストエリア |
| ボタン ラベル / URL | `help_btn_label` / `help_btn_url` | テキスト / URL |

> TOPIC は「まとめて相談する」ボタンの上に表示。`topics` を1件以上登録すると反映され、**横に流れるマーキー**になります（`prefers-reduced-motion` 時は折り返し表示）。シームレスなループのため、JS が項目を1セット複製しています。
