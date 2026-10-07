# 拡張設計書（広告枠・外部連携）

`つなぐ依頼ページ_拡張設計書.docx` が成果物です（相談段階の設計案・未実装）。

## 再生成

```bash
cd docs/design
npm install docx mermaid      # 初回のみ
# 図を変更した場合のみ：diagrams.html を Playwright で開き、#fig-layout / #fig-arch / #fig-seq / #fig-phase を fig/*.png に書き出す
node build.js つなぐ依頼ページ_拡張設計書.docx
```

- `content.md` … 本文（章見出し・表・箇条書き）。`[[FIG:xxx]]` の位置に図が入る
- `diagrams.html` … 図の元データ（配置案のレイアウト図＋Mermaid 3点）
- `build.js` … Word 生成（A4縦・表紙と目次つき・章ごとに改ページ）
