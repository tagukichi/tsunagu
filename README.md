# つなぐ依頼ページ（TSUNAGU MEMBER PORTAL）

つなぐ会員向けの依頼・相談受付ページ。デザインカンプを静的サイト（HTML / CSS / JS）として実装したものです。
**将来的に WordPress + ACF テーマ化**することを前提に、文言・リンクをカスタムフィールドへ移しやすい構造で作成しています。

## ディレクトリ構成

```
tsunagu/
├── index.html                 … ページ本体（ACFフィールド名をコメントで明記）
├── assets/
│   ├── css/style.css          … スタイル（色テーマは data-theme で切替）
│   ├── js/main.js             … スクロール出現アニメのみ
│   └── img/
│       ├── skyline.svg        … ヒーロー背景の街並み（★プレースホルダー）
│       └── chousoku-graphic.svg … 調速ツールのイメージ（★プレースホルダー）
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
| 画像 | ヒーロー背景の街並み | `assets/img/skyline.svg` |
| 画像 | 調速ツールのイメージ | `assets/img/chousoku-graphic.svg` |
| アイコン | ヘッダー / ヒーロー / 各カード / CTA | `index.html` 内のインライン SVG（`<!-- PLACEHOLDER ICON: ... -->`） |

インライン SVG は `currentColor` で塗っているため、差し替え時もカードのカラーテーマ（`--c`）がそのまま反映されます。

## デザイン仕様メモ

- 配色：ブランド青 `#1f6fe0`。カードは 6 色テーマ（blue / teal / green / purple / orange / cyan）を `data-theme` で付与。
- フォント：Noto Sans JP（Google Fonts）。
- レスポンシブ：PC（3カラム）→ タブレット 1024px 以下（2カラム）→ スマホ 680px 以下（1カラム）。
- サービスカードは**固定6枚**。

## WordPress / ACF 移行ガイド

固定ページのカスタムフィールドで編集する想定です。`index.html` の各セクション冒頭に `[ACF] フィールド名` をコメントで記載しています。下記が対応表です。

### 共通・ヘッダー / フッター
| 項目 | フィールド名 | 種別 |
|------|------------|------|
| ロゴ文言 | `site_logo_text` | テキスト |
| お問い合わせ ラベル / URL | `header_contact_label` / `header_contact_url` | テキスト / URL |
| コピーライト | `footer_copyright` | テキスト |

### ヒーロー
| 項目 | フィールド名 |
|------|------------|
| バッジ文言 | `hero_badge` |
| タイトル | `hero_title` |
| リード文 | `hero_lead`（テキストエリア） |
| 利用案内 タイトル / 説明 | `info_card_title` / `info_card_desc` |
| 依頼シートを確認：ラベル / リンク先 | `info_item_1_label` / `info_item_1_file`（**PDFファイル**） |
| 料金表を確認：ラベル / リンク先 | `info_item_2_label` / `info_item_2_file`（**PDFファイル**） |
| 連絡方法を確認：ラベル / リンク先 | `info_item_3_label` / `info_item_3_url`（URL or ページリンク） |

> 依頼シート・料金表は ACF の **ファイルフィールド（PDF）** を想定。出力時はファイルの URL を `href` に出し、`target="_blank"` で別タブ表示します。連絡方法はリンク（URL）フィールドです。

### おすすめツール（調速）
| 項目 | フィールド名 |
|------|------------|
| リボン文言 | `featured_ribbon` |
| ツール名 | `featured_name` |
| 製品名 | `featured_product` |
| ピル文言 | `featured_pill` |
| 説明文 | `featured_text` |
| ボタン ラベル / URL | `featured_btn_label` / `featured_btn_url` |
| イメージ画像 | `featured_image`（画像） |

### サービスカード（固定6枚 / n = 1〜6）
各カードで以下を用意します。

| 項目 | フィールド名 | 種別 |
|------|------------|------|
| タグ | `card_{n}_tag` | テキスト |
| タイトル | `card_{n}_title` | テキスト |
| 説明文 | `card_{n}_desc` | テキストエリア |
| **左ボタン** ラベル | `card_{n}_btn1_label` | テキスト |
| 左ボタン PDF（複数可） | `card_{n}_pdfs`（**リピーター**）<br>└ `pdf_label`（テキスト） / `pdf_file`（**ファイル＝PDF**） | リピーター |
| **右ボタン** ラベル | `card_{n}_btn2_label` | テキスト |
| 右ボタン ポップアップ 見出し | `card_{n}_popup_title` | テキスト |
| 右ボタン ポップアップ 本文 | `card_{n}_popup_text` | WYSIWYG / テキストエリア |

#### ボタンの挙動

- **左ボタン（PDF）**：`card_{n}_pdfs` の登録件数で動きが変わります。
  - **1件** … クリックでその PDF を直接開く（別タブ）。
  - **2件以上** … クリックでリスト（ドロップダウン）を表示し、項目クリックで各 PDF を開く。
  - 出力時は `pdf_file` の URL を各リンクの `href` に、`pdf_label` をリンク文言に流し込みます。
- **右ボタン（ポップアップ）**：クリックで共通モーダルを開き、`card_{n}_popup_title` を見出し、`card_{n}_popup_text` を本文として表示します（**ページ遷移なし**）。

> カラーテーマ・アイコンはテーマ側で固定（テンプレートに直書き）。文言・リンク（PDF）・ポップアップ本文を ACF 化する想定です。
> ダミー実装では PDF はすべて `assets/pdf/sample.pdf`、ポップアップ本文は各カード内の `#popup-c{n}` を参照しています。

### 相談CTA
| 項目 | フィールド名 |
|------|------------|
| タイトル | `help_title` |
| 説明文 | `help_desc` |
| ボタン ラベル / URL | `help_btn_label` / `help_btn_url` |
