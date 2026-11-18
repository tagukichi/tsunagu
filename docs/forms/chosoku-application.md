# 調速 利用申込フォーム（Contact Form 7）

Googleフォーム「【調速】利用申込書」をサイト内のフォームに置き換えるための設定です。
金額は現在のGoogleフォームの表記のままです。最新の料金が決まったら、「フォーム」タブの選択肢の文字を差し替えてください。

## 設定の流れ

1. WordPress管理画面「お問い合わせ」→「新規追加」。タイトルは「調速 利用申込」
2. 下の「フォーム」「メール」「メール (2)」をそれぞれのタブに貼り付けて保存
3. 固定ページを新規作成（タイトル「調速 利用申込」、テンプレート「つなぐ フォームページ」）。
   ACF「Contact Form 7 ショートコード」に、手順2で表示されたショートコードを貼り付け
4. 依頼ページの管理画面「カード6：調速サービス」→「右ボタン ページ」で、手順3のページを選択

## フォーム（「フォーム」タブ）

- 会社名とご担当者メールは、ログイン中の会員情報（表示名・メールアドレス）が最初から入ります。
  会社名は管理表（顧客一覧）と一致させるため、変更できない設定にしています。
- 「（必須）」の文字は確認画面で自動的に外れます。

```
<div class="cf7-input-screen">
<p>本申込書及び「調速利用規約」の内容を確認し、これに同意のうえ、本サービスの利用を申し込みます。<br>※月額費用は選択プランに応じて年払い（12か月分）でご請求いたします。</p>

<h3>【1】お申込者情報</h3>
<p>会社名（社名）（必須）<br>[text* company readonly default:user_display_name]<br><small>ログイン中の会社名が入ります。変更が必要な場合は事務局までご連絡ください。</small></p>
<p>住所（必須）<br>[text* address]</p>
<p>代表者名（必須）<br>[text* representative]</p>
<p>電話番号（必須）<br>[tel* tel placeholder "例）03-1234-5678"]</p>
<p>請求書送付先メールアドレス（必須）<br>[email* invoice_email]</p>

<h3>ご担当者情報</h3>
<p>ご担当者 お名前（必須）<br>[text* contact_name]</p>
<p>部署<br>[text department]</p>
<p>ご担当者 メールアドレス（必須）<br>[email* contact_email default:user_email]</p>

<h3>【2】ご契約内容</h3>
<p>ご希望のプラン・利用開始日をご選択ください。プランは弊社にて最終調整いたします。</p>
<p>ご希望プラン（必須）<br>[radio plan use_label_element "特別プラン（年額112,200円 税込）" "Sプラン（年額158,400円 税込）" "Mプラン（年額237,600円 税込）" "Lプラン（年額316,800円 税込）" "メガプラン（料金は別途お見積り）"]</p>
<p>オプション（複数選択可）<br>[checkbox options use_label_element "資料格納（年額33,000円 税込）" "Excel書式（年額26,400円 税込）" "レポート（年額19,800円 税込）"]</p>
<p>ご希望のご利用開始日（必須）<br>[date* start_date]</p>
<p>ご利用者のメールアドレス（全員分）（必須）<br>[textarea* user_emails placeholder "1行に1件ずつご入力ください"]</p>
<p>主な業務内容・ご要望<br>[text business placeholder "例）仕入・買取 など"]</p>

<p>利用規約への同意（必須）<br>[acceptance agree]<a href="#" target="_blank" rel="noopener">「調速利用規約」</a>の内容に同意します[/acceptance]</p>

<p><button type="button" class="wpcf7-submit cf7-confirm-btn">入力内容を確認する</button></p>
</div>

<div class="cf7-confirm-screen">
<h3>入力内容の確認</h3>
<div class="cf7c-list"></div>
</div>
<div class="cf7c-buttons cf7-confirm-screen">
<button type="button" class="wpcf7-submit cf7-back-btn">修正する</button>
[submit "送信する"]
</div>
```

利用規約のリンク先（`href="#"`）は、規約のURL（またはPDFのURL）に差し替えてください。

## メール（「メール」タブ：事務局あて）

| 項目 | 設定 |
| --- | --- |
| 送信先 | 事務局のメールアドレス |
| 送信元 | `[_site_title] <wordpress@サイトのドメイン>` |
| 題名 | `【調速】利用申込：[company]` |
| 追加ヘッダー | `Reply-To: [contact_email]` |

メッセージ本文：

```
調速の利用申込がありました。

■お申込者情報
会社名：[company]
住所：[address]
代表者名：[representative]
電話番号：[tel]
請求書送付先メールアドレス：[invoice_email]

■ご担当者情報
お名前：[contact_name]
部署：[department]
メールアドレス：[contact_email]

■ご契約内容
ご希望プラン：[plan]
オプション：[options]
ご希望のご利用開始日：[start_date]
ご利用者のメールアドレス：
[user_emails]
主な業務内容・ご要望：[business]

--
送信日時：[_date] [_time]
送信元ページ：[_url]
```

## メール (2)（自動返信：申込者あて）

「メール (2) を使用」にチェックを入れます。

| 項目 | 設定 |
| --- | --- |
| 送信先 | `[contact_email]` |
| 送信元 | `[_site_title] <wordpress@サイトのドメイン>` |
| 題名 | `【調速】利用申込を受け付けました` |
| 追加ヘッダー | `Reply-To: 事務局のメールアドレス` |

メッセージ本文：

```
[company]
[contact_name] 様

このたびは調速の利用をお申し込みいただき、ありがとうございます。
以下の内容で受け付けました。担当者より、ご請求とアカウント発行のご案内をお送りします。

ご希望プラン：[plan]
オプション：[options]
ご希望のご利用開始日：[start_date]

ご不明な点は、このメールにご返信ください。
```

## 管理表（Excel「調速」シート）との対応（M365連携で使用）

| フォームの項目 | 名前 | 調速シートの列 |
| --- | --- | --- |
| 会社名 | company | 顧客名 |
| ご希望プラン | plan | プラン（S・M・L に変換） |
| オプション | options | 資料格納・Excel書式・レポート（選ばれたものに印） |
| ご希望のご利用開始日 | start_date | （列なし。申込日・支払期限の扱いは要確認） |
| 主な業務内容・ご要望 | business | 業務内容 |
| 送信日 | — | 申込日 |
| — | — | 登録元＝依頼サイト、送信ID |

住所・代表者・請求書送付先・ご担当者は、顧客一覧の会社情報（空欄の欄だけ埋める）に使う想定です。
