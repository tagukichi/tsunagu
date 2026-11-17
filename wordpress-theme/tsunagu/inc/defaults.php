<?php
/**
 * 既定コンテンツ（ACF未入力時のフォールバック＝現行の静的サイトと同じ内容）
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function tsunagu_defaults() {
	static $d = null;
	if ( $d !== null ) return $d;

	$d = array(
		// ヘッダー / 共通
		'header_title'         => '会員限定依頼ページ',
		'header_company_label' => '会社紹介',
		'header_forms_label'   => '指定書式',
		'header_ext1_label'    => '買付証明書',
		'header_ext2_label'    => '売却合意書',
		'header_btn_label'     => '利用規約・ダウンロード',
		'footer_copyright'     => '© TSUNAGU. All rights reserved.',

		// 問い合わせ先（フッターに表示）※フィールド名はヒーローにあった頃のまま
		'hero_contact_label' => 'お問い合わせ：',
		'hero_contact_email' => 'info@tsunagu.example',

		// カードの並び順（カード番号をカンマ区切り。PC＝左上から右へ、スマホ＝上から）
		'cards_order_pc' => '6,3,4,5,1,2',
		'cards_order_sp' => '1,2,6,3,4,5',

		// TOPIC
		'topics' => array(
			'1棟案件アパート求む',
			'ボロ戸建て投資家に提案可能',
			'500万円以下の案件は是非！',
		),

		// フォームページ
		'form_desc'         => '必要事項をご記入の上、内容をご確認いただき送信してください。担当者より順次ご連絡いたします。',
		'form_thanks_title' => '送信が完了しました',
		'form_thanks_text'  => 'お問い合わせ・ご依頼ありがとうございます。担当者より順次ご連絡いたしますので、少々お待ちください。',

		// ログイン
		'login_title' => '会員ログイン',
		'login_lead'  => 'つなぐ登録会社の会員専用ページです。<br>登録済みのID・パスワードでログインしてください。',
		'login_note'  => 'アカウントをお持ちでない場合は、つなぐ事務局までお問い合わせください。<br>会員登録は事務局にて承ります。',

		// サービスカード（固定6枚）
		'cards' => array(
			1 => array(
				'theme'       => 'blue',
				'tag'         => '売却の相談・依頼',
				'title'       => '売却物件の依頼',
				'desc'        => '売却したい物件のご相談・ご依頼を受け付けています。',
				'btn1'        => '依頼',
				'forms'       => array(
					array( 'label' => '自社物件の売却依頼', 'url' => '#' ),
					array( 'label' => '仲介物件の売却依頼', 'url' => '#' ),
				),
				'btn2'        => '詳細',
				'popup_title' => '売却のご相談について',
				'popup'       => '<p>自社物件の売却や、一般のお客様から預かった物件の売却依頼はこちらからご依頼ください。</p><ul><li>水面下で進めたい</li><li>まずは強気価格で出したい</li><li>仲介手数料を事前に定めた上で案件を出したい。</li></ul><p>など、柔軟なご依頼が可能です。</p>',
			),
			2 => array(
				'theme'       => 'teal',
				'tag'         => '仕入・エンド情報',
				'title'       => '仕入物件の依頼・<br>エンド情報の依頼',
				'desc'        => '仕入れたい物件や、エンド（購入希望者）情報のご提供をお願いします。',
				'btn1'        => '依頼',
				'forms'       => array(
					array( 'label' => '自社購入の仕入基準を依頼', 'url' => '#' ),
					array( 'label' => '一般買主の購入ニーズを依頼', 'url' => '#' ),
				),
				'btn2'        => '詳細',
				'popup_title' => '仕入・エンド情報について',
				'popup'       => '<p>自社物件の仕入や、一般のお客様が希望されるニーズ情報の共有はこちらからご依頼ください。</p><p>案件情報のうち、約70%が未公開案件であり、値下げ案件も多くございます。仕入にも適した案件情報をご提供致します。</p>',
			),
			3 => array(
				'theme'       => 'green',
				'tag'         => '工事・リフォーム・派遣',
				'title'       => '工事の見積・相談・<br>派遣の依頼',
				'desc'        => '解体、造成、リフォーム、建設工事の見積からご依頼可能。',
				'btn1'        => '依頼',
				'forms'       => array(
					array( 'label' => '工事の見積依頼', 'url' => '#' ),
					array( 'label' => 'リフォームの見積依頼', 'url' => '#' ),
				),
				'btn2'        => '詳細',
				'popup_title' => '工事・リフォーム・派遣について',
				'popup'       => '<p>つなぐ提携会社様をご紹介可能です。まずは実際の現場の見積からおつなぎ致します。</p><p>大切な弊社のパートナー企業様をおつなぎ致しますので、軽率な依頼はご遠慮ください。</p>',
			),
			4 => array(
				'theme'       => 'purple',
				'tag'         => '印刷・制作',
				'title'       => '名刺・チラシ印刷の依頼',
				'desc'        => '名刺やチラシなどの印刷物制作をサポートします。',
				'btn1'        => '面談調整',
				'forms'       => array(), // 1件以下＝クリックで直接遷移（ACFで form1 を設定）
				'btn2'        => 'ご料金',
				'popup_title' => '印刷メニュー・料金表',
				// card4 はPDFポップアップ（popup は使わず popup_pdf を使用）
				'popup'       => '',
				'popup_pdf'   => tsunagu_asset( 'pdf/print-pricing.pdf' ),
			),
			5 => array(
				'theme'       => 'orange',
				'tag'         => 'Web制作・DX支援',
				'title'       => 'HP制作・DX支援',
				'desc'        => 'LP・HP制作から業務の自動化、自社アプリの開発までご相談いただけます。',
				'btn1'        => '依頼',
				// メニューが1件の間はボタンから直接フォームへ。2件以上でプルダウン表示
				'forms'       => array(
					array( 'label' => 'HP制作', 'url' => '#' ),
				),
				'btn2'        => 'サービス・料金案内',
				'popup_title' => 'サービス・料金案内',
				// 料金表PDFを登録すると、PDFを表示（この本文は使われない）
				'popup'       => '<p>以下のサービスをご提供しています。</p><ul><li>LP／HP／掲載型</li><li>業務自動化の相談</li><li>自社アプリ開発等の相談</li></ul>',
				'popup_pdf'   => '',
			),
			6 => array(
				'theme'       => 'cyan',
				'tag'         => '不動産調査AIツール',
				'title'       => '調速サービス',
				'desc'        => '「10秒検索」で物件概要を簡易調査、提案資料を「AI」が自動作成する不動産調査AIツールです。',
				'btn1'        => 'ご利用メニュー',
				'forms'       => array(
					array( 'label' => 'ログイン', 'url' => '#' ),
					array( 'label' => '申込フォーム', 'url' => '#' ),
					array( 'label' => 'サービス内容（営業資料）', 'url' => '#' ),
					array( 'label' => '料金表', 'url' => '#' ),
				),
				'btn2'        => '詳細',
				'popup_title' => '調速（チョーソク）について',
				'popup'       => '<p>調速（チョーソク）は、不動産の調査・相場確認を効率化するAIツールです。</p><ul><li>「10秒検索」で物件概要を簡易調査</li><li>提案資料を「AI」が自動作成</li></ul>',
			),
		),
	);

	return $d;
}

/** カードの固定アイコン（インラインSVG）。テーマ側で固定。 */
function tsunagu_card_icon( $n ) {
	$icons = array(
		1 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 11l8-6 8 6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 10v9h12v-9" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><rect x="10.5" y="13" width="3" height="6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>',
		2 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="9.5" r="3.2" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M5.5 19c0-3 2.6-5 5.5-5s5.5 2 5.5 5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="16.5" cy="15" r="2.6" fill="none" stroke="currentColor" stroke-width="1.5"/><line x1="18.4" y1="16.9" x2="20.5" y2="19" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
		3 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 7a3 3 0 0 1 4 4l-9 9-2-2 9-9a1 1 0 0 0-1-1z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M6.5 4l3.5 3.5-2.5 2.5L4 6.5a3 3 0 0 1 2.5-2.5z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M13 13l5 5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		4 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7 9V4h10v5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><rect x="4" y="9" width="16" height="7" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><rect x="7" y="14" width="10" height="6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="16.5" cy="11.5" r="0.9" fill="currentColor"/></svg>',
		5 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3.5" y="4.5" width="17" height="11.5" rx="1.6" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M9 20h6M12 16v4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10 8.3L8 10.2l2 1.9M14 8.3l2 1.9-2 1.9" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		6 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="10.5" cy="10.5" r="6" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M15 15l5 5" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><path d="M8 12.2l1.8-2 1.6 1.2 2-2.6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	);
	return isset( $icons[ $n ] ) ? $icons[ $n ] : '';
}
