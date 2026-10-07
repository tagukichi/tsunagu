// つなぐ依頼ページ 拡張設計書（Word版）の生成
// 使い方: node build.js <出力.docx>
const fs = require('fs');
const path = require('path');
const {
  Document, Packer, Paragraph, TextRun, ImageRun, Table, TableRow, TableCell,
  Header, Footer, PageNumber, AlignmentType, LevelFormat, BorderStyle,
  ShadingType, WidthType, VerticalAlign, HeadingLevel, PageBreak,
} = require('docx');

const OUT = process.argv[2] || 'out.docx';
const DIR = __dirname;

// ---------- パレット・寸法 ----------
const NAVY = '11295A', BRAND = '1F6FE0', BRAND_DK = '0D3FA6', INK = '233244', MUTED = '5D6B7E';
const LINE = 'D5DDE8', SOFT = 'F3F7FC', LIGHT = 'EAF3FF', GOLD = 'C98A16', GOLD_BG = 'FDF3DC', GOLD_INK = '7A5410';
const OK_INK = '2C6B41';
const PAGE_W = 11906, PAGE_H = 16838, MARGIN_X = 1134;
const CONTENT_W = PAGE_W - MARGIN_X * 2;               // 9638 DXA
const PX_PER_DXA = 96 / 1440;                          // 画像サイズ換算
const CONTENT_PX = Math.floor(CONTENT_W * PX_PER_DXA); // 約642px

const FONT = { ascii: 'Yu Gothic', hAnsi: 'Yu Gothic', eastAsia: '游ゴシック', cs: 'Yu Gothic' };
const MONO = { ascii: 'Consolas', hAnsi: 'Consolas', eastAsia: 'ＭＳ ゴシック', cs: 'Consolas' };

// ---------- 章タイトル（目次と見出しで共用） ----------
const CHAPTERS = {
  '結論：3件とも実現可能': { no: '1', title: '結論：3件とも実現可能' },
  '（1）広告枠の追加': { no: '2', title: '広告枠の追加', tag: 'ご相談（1）' },
  '外部連携の共通基盤（（2）（3）共通）': { no: '3', title: '外部連携の共通基盤', tag: 'ご相談（2）（3）共通' },
  '（3）Excel（M365／SharePoint）連携': { no: '4', title: 'Excel（M365／SharePoint）連携', tag: 'ご相談（3）' },
  '（2）調速（Next.js／Supabase／Vercel）連携：概要': { no: '5', title: '調速（Next.js／Supabase／Vercel）連携の概要', tag: 'ご相談（2）' },
  '実施順序・スケジュール案': { no: '6', title: '実施順序・スケジュール案' },
  '決めていただきたいこと': { no: '7', title: '決めていただきたいこと' },
  '付録：送信データの共通形式と項目一覧': { no: '付録', title: '送信データの共通形式と項目一覧' },
};

// ---------- 図 ----------
function pngSize(file) {
  const b = fs.readFileSync(file);
  return { w: b.readUInt32BE(16), h: b.readUInt32BE(20) };
}
const FIGS = {
  layout: { file: 'fig/layout.png', width: CONTENT_PX, caption: '広告枠の配置案（PC／スマホ）' },
  arch: { file: 'fig/arch.png', width: 390, caption: '外部連携の全体構成' },
  seq: { file: 'fig/seq.png', width: CONTENT_PX, caption: 'Excel連携の処理の流れ' },
  phase: { file: 'fig/phase.png', width: 560, caption: '実施フェーズと依存関係' },
};
let figNo = 0;

// ---------- 表の列幅（見出し行の1列目で判定） ----------
const TABLE_COLS = {
  '項目|可否': [3.0, 0.7, 1.0, 2.4, 4.6],
  '位置': [2.6, 1.3, 3.0, 4.4],
  '項目|型': [2.3, 1.6, 6.0],
  '指標': [1.6, 8.0],
  '枠': [1.6, 3.4, 2.4, 1.6],
  '項目|内容': [1.9, 4.6, 2.8],
  '再送回数': { ratios: [1, 1], width: 4400 },
  '作業|担当': [6.4, 1.8, 1.0],
  '案': [2.8, 4.6, 2.8],
  'データ': [2.6, 7.0],
  '事象': [3.6, 6.0],
  '作業|日数': { ratios: [7, 1.6], width: 7200 },
  '受け口': [2.8, 3.6, 3.6],
  'フェーズ': [1.7, 4.5, 2.1, 2.3],
  'セクション': { ratios: [1.6, 2.5, 2.0, 1.05, 0.65, 3.7], size: 16 },
};
function colSpec(headers) {
  const key2 = headers.slice(0, 2).join('|');
  const spec = TABLE_COLS[key2] || TABLE_COLS[headers[0]];
  if (!spec) throw new Error('列幅未定義の表: ' + headers.join(' | '));
  const s = Array.isArray(spec) ? { ratios: spec } : spec;
  if (s.ratios.length !== headers.length) throw new Error('列数不一致: ' + headers.join(' | '));
  const total = s.width || CONTENT_W;
  const sum = s.ratios.reduce((a, b) => a + b, 0);
  const widths = s.ratios.map((r) => Math.floor((total * r) / sum));
  widths[widths.length - 1] += total - widths.reduce((a, b) => a + b, 0);
  return { widths, total, size: s.size || 18 };
}

// ---------- インライン（`code` と **太字**） ----------
function runs(text, base = {}) {
  const out = [];
  const re = /(`[^`]+`|\*\*[^*]+\*\*)/g;
  let last = 0, m;
  while ((m = re.exec(text))) {
    if (m.index > last) out.push(new TextRun({ text: text.slice(last, m.index), ...base }));
    const t = m[0];
    if (t.startsWith('`')) {
      out.push(new TextRun({ text: t.slice(1, -1), ...base, font: MONO, size: Math.max((base.size || 21) - 2, 15), shading: { type: ShadingType.CLEAR, fill: 'EEF2F7', color: 'auto' } }));
    } else {
      out.push(new TextRun({ text: t.slice(2, -2), ...base, bold: true }));
    }
    last = m.index + t.length;
  }
  if (last < text.length) out.push(new TextRun({ text: text.slice(last), ...base }));
  return out;
}

// ---------- 部品 ----------
function h1(md, isFirst) {
  const c = CHAPTERS[md];
  if (!c) throw new Error('章の対応がありません: ' + md);
  const children = [
    new TextRun({ text: c.no === '付録' ? '付録　' : c.no + '　', color: BRAND }),
    new TextRun({ text: c.title }),
  ];
  if (c.tag) children.push(new TextRun({ text: '　' + c.tag, size: 20, color: BRAND_DK, bold: false }));
  return new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children });
}
function h2(text) {
  return new Paragraph({ heading: HeadingLevel.HEADING_2, children: runs(text) });
}
function para(text, opts = {}) {
  return new Paragraph({ children: runs(text), ...opts });
}
function listItem(text, ref, instance) {
  return new Paragraph({ numbering: { reference: ref, level: 0, instance }, spacing: { after: 60 }, children: runs(text) });
}
function figure(key) {
  const f = FIGS[key];
  const { w, h } = pngSize(path.join(DIR, f.file));
  const width = Math.min(f.width, CONTENT_PX);
  const height = Math.round((width * h) / w);
  figNo += 1;
  return [
    new Paragraph({
      alignment: AlignmentType.CENTER, keepNext: true, spacing: { before: 160, after: 60 },
      children: [new ImageRun({ type: 'png', data: fs.readFileSync(path.join(DIR, f.file)), transformation: { width, height }, altText: { title: f.caption, description: f.caption, name: key } })],
    }),
    new Paragraph({
      alignment: AlignmentType.CENTER, spacing: { after: 220 },
      children: [new TextRun({ text: `図${figNo}　${f.caption}`, size: 18, color: MUTED })],
    }),
  ];
}
function codeBlock(lines) {
  const none = { style: BorderStyle.NONE, size: 0, color: 'FFFFFF' };
  const thin = { style: BorderStyle.SINGLE, size: 4, color: LINE };
  return [new Table({
    width: { size: CONTENT_W, type: WidthType.DXA },
    columnWidths: [CONTENT_W],
    rows: [new TableRow({ cantSplit: true, children: [new TableCell({
      width: { size: CONTENT_W, type: WidthType.DXA },
      shading: { type: ShadingType.CLEAR, fill: SOFT, color: 'auto' },
      margins: { top: 140, bottom: 140, left: 220, right: 220 },
      borders: { top: thin, bottom: thin, right: thin, left: { style: BorderStyle.SINGLE, size: 18, color: BRAND } },
      children: lines.map((ln) => new Paragraph({
        spacing: { before: 0, after: 0, line: 260 },
        children: [new TextRun({ text: ln.length ? ln : ' ', font: MONO, size: 17, color: INK })],
      })),
    })] })],
  }), spacer(120)];
}
function table(headers, rows) {
  const { widths, total, size } = colSpec(headers);
  const cellBorder = { style: BorderStyle.SINGLE, size: 4, color: LINE };
  const borders = { top: cellBorder, bottom: cellBorder, left: cellBorder, right: cellBorder };
  const margins = { top: 70, bottom: 70, left: 110, right: 110 };
  const cell = (text, i, header, zebra) => new TableCell({
    width: { size: widths[i], type: WidthType.DXA },
    borders, margins, verticalAlign: VerticalAlign.CENTER,
    shading: { type: ShadingType.CLEAR, fill: header ? NAVY : (zebra ? SOFT : 'FFFFFF'), color: 'auto' },
    children: [new Paragraph({
      spacing: { before: 0, after: 0, line: 300 },
      children: header
        ? [new TextRun({ text, bold: true, color: 'FFFFFF', size })]
        : text === '可'
          ? [new TextRun({ text, bold: true, color: OK_INK, size })]
          : runs(text, { size }),
    })],
  });
  return new Table({
    width: { size: total, type: WidthType.DXA },
    columnWidths: widths,
    rows: [
      new TableRow({ tableHeader: true, cantSplit: true, children: headers.map((t, i) => cell(t, i, true)) }),
      ...rows.map((r, ri) => new TableRow({ cantSplit: true, children: r.map((t, i) => cell(t, i, false, ri % 2 === 1)) })),
    ],
  });
}
const spacer = (after = 120) => new Paragraph({ spacing: { before: 0, after }, children: [] });

// ---------- 本文（content.md）の変換 ----------
function convert(md) {
  const L = md.replace(/\r/g, '').split('\n');
  const out = [];
  let i = 0, firstH1 = true, olInstance = 0, inOl = false;
  const splitRow = (s) => s.trim().replace(/^\|/, '').replace(/\|$/, '').split('|').map((c) => c.trim());
  while (i < L.length) {
    const line = L[i];
    if (!/^\d+\. /.test(line)) inOl = false;
    if (line.startsWith('## ')) { out.push(h1(line.slice(3).trim(), firstH1)); firstH1 = false; i++; continue; }
    if (line.startsWith('### ')) { out.push(h2(line.slice(4).trim())); i++; continue; }
    const fig = line.match(/^\[\[FIG:(\w+)\]\]$/);
    if (fig) { out.push(...figure(fig[1])); i++; continue; }
    if (line.startsWith('```')) {
      const buf = []; i++;
      while (i < L.length && !L[i].startsWith('```')) buf.push(L[i++]);
      i++; out.push(...codeBlock(buf)); continue;
    }
    if (line.startsWith('|')) {
      const buf = [];
      while (i < L.length && L[i].startsWith('|')) buf.push(L[i++]);
      const headers = splitRow(buf[0]);
      const rows = buf.slice(2).map(splitRow);
      out.push(table(headers, rows), spacer(160));
      continue;
    }
    if (line.startsWith('- [ ] ')) { out.push(listItem(line.slice(6), 'checks', 0)); i++; continue; }
    if (line.startsWith('- ')) { out.push(listItem(line.slice(2), 'bullets', 0)); i++; continue; }
    const ol = line.match(/^(\d+)\. (.*)$/);
    if (ol) {
      if (!inOl) { olInstance += 1; inOl = true; }
      out.push(listItem(ol[2], 'numbers', olInstance)); i++; continue;
    }
    if (line.trim() === '') { i++; continue; }
    out.push(para(line));
    i++;
  }
  return out;
}

// ---------- 表紙 ----------
function cover() {
  const items = [
    '（1）依頼サイトに「広告枠」を追加する',
    '（2）依頼内容を調速の管理表へ自動転記する',
    '（3）依頼内容をExcel（M365）へ自動転記する',
  ];
  const box = new Table({
    width: { size: CONTENT_W, type: WidthType.DXA },
    columnWidths: [CONTENT_W],
    rows: [new TableRow({ children: [new TableCell({
      width: { size: CONTENT_W, type: WidthType.DXA },
      shading: { type: ShadingType.CLEAR, fill: LIGHT, color: 'auto' },
      margins: { top: 200, bottom: 200, left: 280, right: 280 },
      borders: {
        top: { style: BorderStyle.NONE, size: 0, color: 'FFFFFF' }, bottom: { style: BorderStyle.NONE, size: 0, color: 'FFFFFF' },
        right: { style: BorderStyle.NONE, size: 0, color: 'FFFFFF' }, left: { style: BorderStyle.SINGLE, size: 24, color: BRAND },
      },
      children: [
        new Paragraph({ spacing: { after: 100 }, children: [new TextRun({ text: 'ご相談事項', bold: true, color: NAVY, size: 22 })] }),
        ...items.map((t, k) => new Paragraph({ spacing: { after: k === items.length - 1 ? 0 : 60 }, children: [new TextRun({ text: t, size: 21 })] })),
      ],
    })] })],
  });
  const toc = Object.values(CHAPTERS).map((c) => new Paragraph({
    spacing: { after: 70 },
    indent: { left: 200 },
    children: [
      new TextRun({ text: (c.no === '付録' ? '付録' : c.no) + '　', bold: true, color: BRAND, size: 21 }),
      new TextRun({ text: c.title, size: 21 }),
      ...(c.tag ? [new TextRun({ text: '　' + c.tag, size: 17, color: MUTED })] : []),
    ],
  }));
  return [
    new Paragraph({ spacing: { before: 1400, after: 120 }, children: [new TextRun({ text: 'TSUNAGU MEMBER PORTAL', bold: true, color: BRAND, size: 20, characterSpacing: 40 })] }),
    new Paragraph({ spacing: { after: 60, line: 420 }, children: [new TextRun({ text: 'つなぐ依頼ページ', bold: true, color: NAVY, size: 52 })] }),
    new Paragraph({ spacing: { after: 160, line: 420 }, children: [new TextRun({ text: '拡張設計書', bold: true, color: NAVY, size: 52 })] }),
    new Paragraph({
      spacing: { after: 360 },
      border: { bottom: { style: BorderStyle.SINGLE, size: 12, color: BRAND, space: 10 } },
      children: [new TextRun({ text: '広告枠の追加・外部連携（調速／Excel）', color: MUTED, size: 28 })],
    }),
    new Paragraph({
      spacing: { after: 360 },
      children: [new TextRun({ text: '　相談段階の設計案（未実装）　', bold: true, color: GOLD_INK, size: 20, shading: { type: ShadingType.CLEAR, fill: GOLD_BG, color: 'auto' } })],
    }),
    box,
    new Paragraph({ spacing: { before: 480, after: 140 }, border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: LINE, space: 6 } }, children: [new TextRun({ text: '目次', bold: true, color: NAVY, size: 24 })] }),
    ...toc,
    new Paragraph({ spacing: { before: 600, after: 40 }, children: [new TextRun({ text: '作成日　2026年10月7日', color: MUTED, size: 19 })] }),
    new Paragraph({ spacing: { after: 0 }, children: [new TextRun({ text: '版数　　1.0', color: MUTED, size: 19 })] }),
  ];
}

// ---------- 文書 ----------
const body = convert(fs.readFileSync(path.join(DIR, 'content.md'), 'utf8'));

const doc = new Document({
  creator: 'つなぐ依頼ページ 制作担当',
  title: 'つなぐ依頼ページ 拡張設計書（広告枠・外部連携）',
  description: '広告枠の追加、調速・Excel（M365）への自動転記の設計案',
  styles: {
    default: {
      document: {
        run: { font: FONT, size: 21, color: INK },
        paragraph: { spacing: { after: 120, line: 340 } },
      },
    },
    paragraphStyles: [
      {
        id: 'Heading1', name: 'Heading 1', basedOn: 'Normal', next: 'Normal', quickFormat: true,
        run: { font: FONT, size: 32, bold: true, color: NAVY },
        paragraph: {
          spacing: { before: 0, after: 280, line: 360 }, outlineLevel: 0, keepNext: true,
          border: { bottom: { style: BorderStyle.SINGLE, size: 12, color: BRAND, space: 8 } },
        },
      },
      {
        id: 'Heading2', name: 'Heading 2', basedOn: 'Normal', next: 'Normal', quickFormat: true,
        run: { font: FONT, size: 24, bold: true, color: NAVY },
        paragraph: {
          spacing: { before: 320, after: 140, line: 320 }, outlineLevel: 1, keepNext: true,
          border: { left: { style: BorderStyle.SINGLE, size: 24, color: BRAND, space: 8 } },
          indent: { left: 140 },
        },
      },
    ],
  },
  numbering: {
    config: [
      { reference: 'bullets', levels: [{ level: 0, format: LevelFormat.BULLET, text: '•', alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 440, hanging: 260 } }, run: { color: BRAND } } }] },
      { reference: 'numbers', levels: [{ level: 0, format: LevelFormat.DECIMAL, text: '%1.', alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 460, hanging: 320 } }, run: { color: BRAND, bold: true } } }] },
      { reference: 'checks', levels: [{ level: 0, format: LevelFormat.BULLET, text: '☐', alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 500, hanging: 360 } }, run: { font: { ascii: 'MS Gothic', hAnsi: 'MS Gothic', eastAsia: 'ＭＳ ゴシック' }, color: NAVY } } }] },
    ],
  },
  sections: [{
    properties: {
      titlePage: true,
      page: {
        size: { width: PAGE_W, height: PAGE_H },
        margin: { top: 1300, bottom: 1200, left: MARGIN_X, right: MARGIN_X, header: 620, footer: 600 },
      },
    },
    headers: {
      first: new Header({ children: [new Paragraph({ children: [] })] }),
      default: new Header({ children: [new Paragraph({
        alignment: AlignmentType.RIGHT,
        border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: LINE, space: 4 } },
        children: [new TextRun({ text: 'つなぐ依頼ページ 拡張設計書（広告枠・外部連携）', size: 16, color: MUTED })],
      })] }),
    },
    footers: {
      first: new Footer({ children: [new Paragraph({ children: [] })] }),
      default: new Footer({ children: [new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ children: [PageNumber.CURRENT, ' / ', PageNumber.TOTAL_PAGES], size: 17, color: MUTED })],
      })] }),
    },
    children: [...cover(), ...body],
  }],
});

Packer.toBuffer(doc).then((buf) => {
  fs.writeFileSync(OUT, buf);
  console.log('wrote', OUT, buf.length, 'bytes / 図', figNo, '点');
});
