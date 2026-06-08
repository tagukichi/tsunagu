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
| 利用案内 3項目ラベル | `info_item_1_label` / `info_item_2_label` / `info_item_3_label` |

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

| 項目 | フィールド名 |
|------|------------|
| タグ | `card_{n}_tag` |
| タイトル | `card_{n}_title` |
| 説明文 | `card_{n}_desc` |
| ボタン1 ラベル / URL | `card_{n}_btn1_label` / `card_{n}_btn1_url` |
| ボタン2 ラベル / URL | `card_{n}_btn2_label` / `card_{n}_btn2_url` |

> カラーテーマ・アイコンはテーマ側で固定（テンプレートに直書き）。今回の要件「全テキスト＋リンクを編集可能」に合わせ、文言とリンクのみ ACF 化する想定です。

### 相談CTA
| 項目 | フィールド名 |
|------|------------|
| タイトル | `help_title` |
| 説明文 | `help_desc` |
| ボタン ラベル / URL | `help_btn_label` / `help_btn_url` |
